<?php

namespace Sagordev\PowersmsGateway;

class PowerSms extends HttpAbstract
{
    protected $config;
    protected $messages = [];
    protected $phoneNumbers = [];
    protected $debug = false;
    protected $broadcastPhoneNumbers = [];

    public function __construct($config)
    {
        $this->config = $config;
        parent::__construct($config);
    }

    public function enableDebugMode(){
        $this->debug = true;
        return $this;
    }

    public function message( string $message = '', $to = null) : self
    {
        $this->message[] = $message;
        if(!is_null($to)){
            $this->to($to);
        }

        return $this;
    }

    public function to( $phoneNumbers ) : self
    {
        if(!is_array( $phoneNumbers )){
            $phoneNumbers = explode(',', $phoneNumbers);
        }

        end($this->message);
        $key = key($this->message);
        $this->phoneNumbers[$key] = $phoneNumbers;
        return $this;
    }

    public function cc( $phoneNumbers ){
        if(!is_array($phoneNumbers)){
            $phoneNumbers = explode(',', $phoneNumbers);
        }
        if(count($phoneNumbers) > 0){
            $this->broadcastPhoneNumbers = $phoneNumbers;
        }
        return $this;
    }

    public function broadcastNumbers( $phoneNumbers ){
        return $this->cc($phoneNumbers);
    }

    public function send( $messageAndNumbers = []){
        if(isset($messageAndNumbers['message'], $messageAndNumbers['to'])){
            $this->message($messageAndNumbers['message'], $messageAndNumbers['to']);
        }else if(count($messageAndNumbers) > 0){
            foreach($messageAndNumbers as $sms){
                if(isset($sms['message'], $sms['to'])){
                    $this->message($sms['message'], $sms['to']);
                }
            }
        }
        $this->ammendBroadcastPhoneNumbers();
        return $this->prepareAndCallApi();
    }


    private function ammendBroadcastPhoneNumbers(){
        foreach($this->message as $key => $message){
            $mergedNumbers = null;
            if(isset($this->phoneNumbers[$key])){
                $mergedNumbers = array_merge($this->phoneNumbers[$key], $this->broadcastPhoneNumbers);
            }else{
                $mergedNumbers = $this->broadcastPhoneNumbers;
            }
            $this->phoneNumbers[$key] = array_unique($mergedNumbers);
        }
    }

    private function prepareAndCallApi(){
        $responses = [];
        if($this->isSendable()){
            $count = 1;
            foreach($this->message as $key => $sms){
                if(isset($this->phoneNumbers[$key]) && $this->phoneNumbers[$key]){
                    $query = [
                        'commaSeperatedReceiverNumbers' => implode(',', $this->phoneNumbers[$key]),
                        'smsText' => $sms,
                    ];
                    $serverResp = $this->makeRequest($query);
                    $responses['resp-' . $count++] = $serverResp;
                }
            }
        }
        return $responses;
    }

    private function makeRequest($query){
        if($this->debug){
            return $query;
        }
        return $this->requestToServer($query);
    }

    private function isSendable(){
        return (count($this->message) > 0 && count($this->phoneNumbers) > 0);
    }
    
}