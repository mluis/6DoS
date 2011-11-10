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
        $this->login($u,$p);
        
    }
    
    private function login($user, $pwd){
        $ret =  $this->getUrl('https://services.sapo.pt/Codebits/gettoken?user='.$user.'&password='.$pwd);
        $json_decode = json_decode($ret,true);
        $this->uid = $json_decode['uid'];
        $this->token = $json_decode['token'];
    }
    
    public function getFriends(){
        $friends = $this->getUrl('https://services.sapo.pt/Codebits/foaf/'.$this->uid."?token=".$this->token);
        $jfriends = json_decode($friends,true);
        foreach ($jfriends as $key => $value) {
            array_push($this->friends, array($value['name']=>$value['id']));
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
