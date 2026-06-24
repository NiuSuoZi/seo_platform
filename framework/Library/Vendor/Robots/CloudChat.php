<?php


class CloudChat
{
    private $CC_API = "https://api.cloudchat.com/";
    private $token = "";


    public function __construct($token = '')
    {
        if(empty($token) || $token == ''){
            $this->token = C('CC_BOT_TOKEN');
        }else{
            $this->token = $token;
        }
    }

    public function configure(){}


}