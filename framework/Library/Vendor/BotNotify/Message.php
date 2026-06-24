<?php


class Message
{
    private $tg_api = 'https://api.telegram.org/bot';
    private $cc_api = 'https://api.cloudchat.org';
    private $dd_api = '';
    private $fs_api = '';


    public function SendTextMessage($type = 0,$data = array()){
        switch ($type){
            case 0:
                return $this->tg_message($data);
                break;
            case 1:
                return $this->cc_message($data);
                break;
            case 2:
                return $this->dd_message($data);
                break;
            case 3:
                return $this->fs_message($data);
                break;
        }
    }

    private function cc_message($data = array()){
        if($data['cloudchat_status'] == 0){
            $_data = array(
                'chat_id'=>$data['cloudchat_chatid'],
                'text'=>$data['_text'],
                'chat_type'=>4,
            );
            $_url = sprintf('%s/%s/sendTextMessage',$this->cc_api,$data['cloudchat_keys']);
            return curl_post_proxy($_url,$_data);
        }else{
            return json_encode(['status'=>false,'message'=>'开启未开启cc通知!']);
        }
    }

    private function tg_message($data = array()){
        if($data['telegram_status'] == 0){
            $_data = array(
                'chat_id'=>$data['telegram_chatid'],
                'text'=>$data['_text'],
            );
            $_url = sprintf('%s%s/sendMessage',$this->tg_api,$data['telegram_keys']);
            return curl_post_proxy($_url,$_data);
        }else{
            return json_encode(['status'=>false,'message'=>'开启未开启tg通知!']);
        }
    }

    private function dd_message($data = array()){

    }

    private function fs_message($data = array()){

    }


}