<?php
require_once 'class.database.php';
class NetworkGraph{
    private $graph = array();
    private $cbapi;
    private $db;
    private $onbd = array();
    public function __construct(CodeBitsDatabase $codebitsdb) {
        $this->cbapi = new CodebitsApiUtils('eriksson.monteiro@ua.pt', 'mon9teiro');
        $this->db = $codebitsdb;
    }
    public function getPath($username1, $username2){
        if(isset ($this->graph[$username1])&&isset ($this->graph[$username2])){
            
        }
    }
    
    public function createGraphFromDB(){
        unset ($this->graph);
        $users = $this->db->getUsers();
        foreach ($users as $key => $value) {
            $friends = $this->db->getFriends($value['id']);
            $this->graph[$value['name']]=array('info'=> $value,'friends'=>  array_values($friends));
        }
        print_r($this->graph);
    }
    
    public function createGraph(){
//      $friends = $this->cbapi->getFriends();
        echo "start .\n";
        $friends = $this->cbapi->getFriendOfFriend(1);
        
        $this->graph[1]=array_values($friends);
        $this->db->addUser($this->cbapi->getUser(1) ,'Celso Martinho');
        array_push($this->onbd,1);
        
        foreach ($friends as $key => $value) {
            if( !in_array($value, $this->onbd)){
                $user = $this->cbapi->getUser($value);
                $this->db->addUser($user, $key);
                array_push($this->onbd,$value);
            }
            $array_keys = array_keys($this->graph);
            if(!in_array($value, $array_keys)){
                $this->db->setFriends($value, 1);
            }
        }
        
        foreach ($friends as $key => $value) {
            $array_keys = array_keys($this->graph);
            if(!in_array($value, $array_keys)){
                $this->getFriendsOfFriends($value);
            }
        }
        
        
    }
    private function getFriendsOfFriends($user){
        echo "user id ".$user.".\n";
        $friends = $this->cbapi->getFriendOfFriend($user);
        $this->graph[$user] = array_values($friends);
        foreach ($friends as $key => $val){
            if(!in_array($val, $this->onbd)){
                $u = $this->cbapi->getUser($val);
                $this->db->addUser($u, $key);
                array_push($this->onbd,$val);
            }
            
            $array_keys = array_keys($this->graph);
            if(!in_array($val, $array_keys)){
                $this->db->setFriends($val, $user);
            }
        }
        
        foreach ($friends as $key => $val){
            $array_keys = array_keys($this->graph);
            if(!in_array($val, $array_keys)){
                $this->getFriendsOfFriends($val);
            }
        }
    }
}
?>
