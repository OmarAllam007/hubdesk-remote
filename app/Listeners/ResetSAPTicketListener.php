<?php

namespace App\Listeners;

use App\Jobs\TicketReplyJob;
use App\Mail\ReplyTicketMail;
use App\Mail\SAPResetPasswordEmail;
use App\TicketReply;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laminas\Soap\Client;
use phpDocumentor\Reflection\Types\Self_;

class ResetSAPTicketListener
{
    const SUCCESS_MESSAGE = "User Changed ,Password Updated";
    const NOT_EXIST = "User does not exist";

    private $user;
    private $client;
    public static $SELECTION_MAP_URLS;

    public function __construct()
    {
        $this->user = auth()->user();

        self::$SELECTION_MAP_URLS = [
            'SAP ECC Quality 400 (ECC QAS 400)' => config('sap.ecc.qas400'),
            'SAP ECC Quality 500 (ECC QAS 500)' => config('sap.ecc.qas500'),
            'SAP ECC Quality 920 (ECC QAS 920)' => config('sap.ecc.qas920'),
            'SAP ECC Production 900 (ECC PRD 900)' => config('sap.ecc.prd'),
            'SAP S4Hana Dev 100 (S4Hana Dev 100)' => config('sap.s4hana.dev'),
            'SAP S4Hana Quality 220 (S4Hana QAS 220)' => config('sap.s4hana.qas'),
            'SAP S4Hana Production 300 (S4Hana PRD 300)' => config('sap.s4hana.prd'),
            'SolMan' => config('sap.solman'),
        ];
    }

    private function initClient($serverObject)
    {
        $this->client = new Client();
        $this->client->setUri($serverObject['url']);
        $this->client->setOptions([
            'soap_version' => SOAP_1_2,
            'wsdl' => $serverObject['url'],
            'login' => $serverObject['user'],
            'password' => $serverObject['password'],
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            'streamcontext' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,

                ],
                'http' => array(
                    "timeout" => 60,
                ),
            ])
        ]);
    }

    private function tryResetPassword($ticket)
    {
        try{
            $employeeID = "E{$this->user->employee_id}";
            $result = $this->client->ZHUBDESK_CHANGE_USER_PASSWORD(['IM_BNAME' => $employeeID]);

            if (strtolower($result->EX_MESSAGE) == strtolower(self::SUCCESS_MESSAGE)) {
                $this->createReply($ticket, $result, true);
            } else {
                $this->createReply($ticket, $result, false);
            }
        }catch (\Throwable $e) {
            if($e->getMessage() == "Could not connect to host"){
                $this->tryResetPassword($ticket);
            }
        }

    }

    public function handle($ticket)
    {
        if ($ticket->subcategory_id == 786) {
            $userInformation = $this->user->loadFromSAP(true);

            if ($userInformation && $userInformation['is_active']) {
                $server = $ticket->fields->first();

                if (!isset(self::$SELECTION_MAP_URLS[$server->value])) {
                    return;
                }

                $serverObject = self::$SELECTION_MAP_URLS[$server->value];

                if (!$serverObject['url']) return;

                $this->initClient($serverObject);
                $this->tryResetPassword($ticket);

            } else {
                // @TODO: send email with invalid user information
                $this->createReply($ticket, null, false);
            }
        }
    }

    private function createReply($ticket, $result, $isValidUser)
    {
        $reply = TicketReply::create([
            'user_id' => env('SYSTEM_USER'),
            'ticket_id' => $ticket->id,
            'content' => $this->makeContent($isValidUser, $result),
            'status_id' => 7,
        ]);


        if ($this->user->email) {
            $cc = [];
            if ($isValidUser) {
                $directManagerEmail = $this->user->manager ? ($this->user->manager->email ?? null) : null;
                $cc[] = $directManagerEmail;
            }

            \Mail::to($this->user->email)
                ->cc($cc)
                ->later(2, new SAPResetPasswordEmail($reply));
        }

        $ticket->update([
            'status_id' => 7
        ]);
    }

    private function makeContent($isValidUser, $result)
    {
        $resultStr = "";
        if ($isValidUser && $result->EX_MESSAGE == self::SUCCESS_MESSAGE) {
            $resultStr .= "<p>Username: e{$this->user->employee_id}</p>";
            $resultStr .= "<p>Password: {$result->EX_PASSWORD}</p>";
        } else {
            $resultStr .= "User does not exist on the selected server";
        }

        return $resultStr;
    }
}
