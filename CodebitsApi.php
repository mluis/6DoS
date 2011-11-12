<?php

class CodebitsApiUtils {
    
    private $username;
    private $password;
    private $token;
    private $uid;
    private $friends = array();
    public function __construct($u, $p) {
        $this->username = $u;
        $this->password = $p;
    }
    
    public function login(){
        $ret =  $this->getUrl('https://services.sapo.pt/Codebits/gettoken?user='.$this->username.'&password='.$this->password);
        $json_decode = json_decode($ret,true);
        if(!isset ($json_decode['token'])){
            return false;
        }
        $this->uid = $json_decode['uid'];
        $this->token = $json_decode['token'];
        return true;
    }
    
    public function getUID(){
        return $this->uid;
    }
    public function getUser($userid){
        $result = $this->getUrl('https://services.sapo.pt/Codebits/user/'.$userid .'?token='.$this->token);
        $json = json_decode($result,true);
        return $json;
    }
    public function getFriendOfFriend($friendid){
        $friends = $this->getUrl('https://services.sapo.pt/Codebits/foaf/'.$friendid.'?token='.$this->token);
        $jfriends = json_decode($friends,true);
        $ret = array();
        if(is_array($jfriends)){
            foreach ($jfriends as $key => $value) {
                $ret[$value['name']]=$value['id'];
            }
        }
        return $ret;
    }


    public function getFriends(){
        $friends = $this->getUrl('https://services.sapo.pt/Codebits/foaf/'.$this->uid.'?token='.$this->token);
        $jfriends = json_decode($friends,true);
        if(is_array($jfriends)){
            foreach ($jfriends as $key => $value) {
                $this->friends[$value['name']] = $value['id'];
            }
        }
        return $this->friends;
    }   
    
    private function getUrl($url) {
        $r = new HttpRequest($url, HttpRequest::METH_GET);
        try {
            $r->send();
            if ($r->getResponseCode() == 200) {
                return $r->getResponseBody();
            }
        } catch (HttpException $ex) {
            echo $ex;
        }
        return false;
    }
}

?>
