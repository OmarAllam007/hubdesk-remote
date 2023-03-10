<?php

namespace App\Helpers;

use App\Mail\SendSurveyEmail;
use App\UserSurvey;
use App\Ticket;
use App\TicketReply;
use GuzzleHttp\Client;

class ServiceDeskApi
{
    protected $key = '';

    protected $client;

    function __construct()
    {
        $this->key = config('services.sdp.key');
        $this->client = new Client(['base_uri' => config('services.sdp.base_url')]);
    }

    function getRequests()
    {
        $response = $this->send('/sdpapi/request', 'GET_REQUESTS', [
            'from' => 0,
            'limit' => 200,
            'filterby' => 'All_Pending_User'
        ]);

        $requests = [];

        if (isset($response->response->operation->Details->record)) {
            foreach ($response->response->operation->Details->record as $record) {
                $request = [];
                foreach ($record as $value) {
                    $key = (string)$value->name;
                    $request[$key] = ((string)$value->value);
                }
                $requests[] = $request;
            }
        }

        return $requests;
    }

    function getRequest($id)
    {
        $response = $this->send("/sdpapi/request/{$id}", 'GET_REQUEST');
        $request = [];
        if(count($response->response->operation->Details->parameter)){
            foreach ($response->response->operation->Details->parameter as $param) {
                $key = (string)$param->name;
                $request[$key] = ((string)$param->value);
            }
            return $request;
        }

    }

    function addReply(TicketReply $reply)
    {
        $this->send('/sdpapi/request/' . $reply->ticket->sdp_id, 'REPLY_REQUEST', [
            ['parameter' => ['name' => 'to', 'value' => $reply->ticket->requester->email]],
            ['parameter' => ['name' => 'subject', 'value' => "Re: [Request ID :##{$reply->ticket->sdp_id}##] : " . $reply->ticket->subject]],
            ['parameter' => ['name' => 'description', 'value' => 'Re: ' . $reply->content]],
        ]);

        $conversations = $this->getConversations($reply->ticket->sdp_id);
        $reply_id = 0;
        foreach ($conversations as $conversation) {
            $reply_id = intval($conversation['conversationid']);
        }
        $this->changeStatus($reply);
        return $reply_id;
    }

    function getConversations($id)
    {
        $result = $this->send('/sdpapi/request/' . $id . '/converations', 'GET_CONVERSATIONS');

        $conversations = [];
        if (isset($result->response->operation->Details->record)) {
            foreach ($result->response->operation->Details->record as $record) {
                $conversation = [];
                foreach ($record->parameter as $param) {
                    $conversation[strval($param->name)] = strval($param->value);
                }
                $conversations[] = $conversation;
            }
        }

        return $conversations;
    }

    function getConversation($request_id, $conversation_id)
    {
        $response = $this->send("/sdpapi/request/{$request_id}/conversation/{$conversation_id}", 'GET_CONVERSATION');

        $conversation = [];
        foreach ($response->response->operation->Details->record->parameter as $param) {
            $conversation[strval($param->name)] = strval($param->value);
        }

        return $conversation;
    }

    function addResolution(TicketReply $reply)
    {
        $this->send('/sdpapi/request/' . $reply->ticket->sdp_id . '/resolution', 'ADD_RESOLUTION', [
            'resolution' => ['resolutiontext' => $reply->content,]
        ]);

        $this->send('/sdpapi/request/' . $reply->ticket->sdp_id, 'EDIT_REQUEST', [
            ['parameter' => ['name' => 'status', 'value' => 'Resolved']]
        ]);

        $conversations = $this->getConversations($reply->ticket->sdp_id);
        $reply_id = 0;
        foreach ($conversations as $conversation) {
            $reply_id = intval($conversation['conversationid']);
        }

        return $reply_id;
    }

    function closeTicket(TicketReply $reply)
    {
        $this->send('/sdpapi/request/' . $reply->ticket->sdp_id, 'CLOSE_REQUEST', [
            ['parameter' => ['name' => 'closeAccepted', 'value' => 'Accepted']],
            ['parameter' => ['name' => 'closeComment', 'value' => $reply->content]]
        ]);
    }


    function addCompletedWithoutSolution(TicketReply $reply)
    {
        $this->send('/sdpapi/request/' . $reply->ticket->sdp_id . '/resolution', 'ADD_RESOLUTION', [
            'resolution' => ['resolutiontext' => $reply->content,]
        ]);

        $this->send('/sdpapi/request/' . $reply->ticket->sdp_id, 'EDIT_REQUEST', [
            ['parameter' => ['name' => 'status', 'value' => 'Completed without solution']]
        ]);
    }

    function getResolution()
    {

    }

    public function send($url, $operation, $data = '')
    {
        $params = [
            'TECHNICIAN_KEY' => $this->key,
            'OPERATION_NAME' => $operation,
            'format' => 'xml'
        ];

        if ($data) {
            if (is_string($data)) {
                $params['INPUT_DATA'] = $data;
            } else {
                $params['INPUT_DATA'] = $this->buildData($data);
            }
        }

        $response = $this->client->post($url, [
            'form_params' => $params
        ])->getBody()->getContents();

        return simplexml_load_string($response);
    }

    protected function buildData($data)
    {
        $operation = [
            'Operation' => ['Details' => $data]
        ];

        return $this->buildXML($operation);
    }

    protected function buildXML($data)
    {
        $string = '';

        foreach ($data as $key => $value) {
            if (!is_numeric($key)) {
                $string .= "<{$key}>";
            }

            if (is_array($value)) {
                $string .= $this->buildXML($value);
            } else {
                $string .= htmlspecialchars($value);
            }

            if (!is_numeric($key)) {
                $string .= "</{$key}>";
            }
        }

        return $string;
    }

    public function getRequester($name)
    {
        $result = $this->send('/sdpapi/requester/', 'GET_ALL', [
            'name' => $name
        ]);

        $attributes = [];
        foreach ($result->response->operation->Details->record->parameter as $param) {
            $attributes[strval($param->name)] = strval($param->value);
        }

        return $attributes;
    }


    public function changeStatus($reply)
    {
        if ($reply->status_id == 7) {
            $this->addResolution($reply);

        } elseif ($reply->status_id == 8) {

            if($reply->ticket->resolve_date){
                $this->closeTicket($reply);
                if ($survey = $reply->ticket->category->survey->first()) {
                    $this->sendSurvey($reply,$survey);
                }
            }
            else{
                $this->addCompletedWithoutSolution( $reply);
            }

        } elseif ($reply->status_id == 9) {
            $this->addCompletedWithoutSolution($reply);

        } elseif (in_array($reply->status_id, [4, 5, 6])) {
            $this->send('/sdpapi/request/' . $reply->ticket->sdp_id, 'EDIT_REQUEST', [
                ['parameter' => ['name' => 'status', 'value' => 'On Hold']],
            ]);

        } elseif ($reply->status_id == 1) {
            $this->send('/sdpapi/request/' . $reply->ticket->sdp_id, 'EDIT_REQUEST', [
                ['parameter' => ['name' => 'status', 'value' => 'Open']],
            ]);

        }
    }

    private function sendSurvey($reply,$survey)
    {
        UserSurvey::create([
            'ticket_id'=>$reply->ticket->id,
            'survey_id'=>$survey->id,
            'comment'=>'',
            'is_submitted'=>0,
            'notified'=>1
        ]);
        \Mail::send(new SendSurveyEmail($reply->ticket));
    }


}