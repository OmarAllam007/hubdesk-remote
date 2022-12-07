<?php

namespace App\Listeners;

use App\Jobs\TicketReplyJob;
use App\Mail\ReplyTicketMail;
use App\TicketReply;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laminas\Soap\Client;

class SolManTicketListener
{
    const SUCCESS_MESSAGE = "User Changed & Password Updated";

    private $user;

    public function __construct()
    {
        $this->user = auth()->user();
    }


    public function handle($ticket)
    {
        if ($ticket->subcategory_id == 786) {
            $user = \App\User::where('employee_id', $this->user->employee_id)->first();
            $userInformation = $user->loadFromSAP(true);

            if ($userInformation && $userInformation['is_active']) {

                $url = env('SOLMAN_RESET_PASSWORD_SERVICE');

                if (!$url) return;
                $client = new Client();
                $client->setUri($url);
                $client->setOptions([
                    'soap_version' => SOAP_1_2,
                    'wsdl' => $url,
                    'login' => config('sap.SAP_USER_Q'),
                    'password' => config('sap.SAP_PASS_Q'),
                    'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP
                ]);

                try {
                    $employeeID = "E{$user->employee_id}";
                    $result = $client->ZHUBDESK_CHANGE_USER_PASSWORD(['IM_BNAME' => $employeeID]);

                    if ($result->EX_MESSAGE == self::SUCCESS_MESSAGE) {
                        $this->createReply($ticket, $result, true);
                    } else {
                        $this->createReply($ticket, $result, false);
                    }
                } catch (\Throwable $e) {
                    return $e->getMessage();
                }
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

        if($this->user->email){
            \Mail::to($this->user->email)
                ->queue(new ReplyTicketMail($reply));
        }

        $ticket->update([
            'status_id' => 7
        ]);
    }

    private function makeContent($isValidUser, $result)
    {
        $resultStr = "";

        if ($isValidUser) {
            $resultStr .= "<p>Username: e{$this->user->employee_id}</p>";
            $resultStr .= "<p>Password: {$result->EX_PASSWORD}</p>";
        } else {
            $resultStr .= "User not exist on SolMan";
        }

        return $resultStr;
    }
}
