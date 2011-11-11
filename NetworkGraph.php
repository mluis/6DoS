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
    public function createGraphFromDB(){
        $friends0 = $this->db->getFriends("1");
//        $users = $this->db->getUsers();
//        foreach ($users as $key => $value) {
//            $friends0 = $this->db->getFriends($value['1']);
//            print_r($friends0);
//        }
        print_r($friends0);
    }
    
    public function createGraph(){
//      $friends = $this->cbapi->getFriends();
        echo "start .\n";
        $friends = $this->cbapi->getFriendOfFriend(1);
        $this->graph[1]=array_values($friends);
        $this->db->addUser(1,'Celso Martinho');
        array_push($this->onbd,1);
        
        foreach ($friends as $key => $value) {
            
            if(!in_array($value['id'], $this->onbd)){
                $this->db->addUser($value['id'], $key);
            }
            $array_keys = array_keys($this->graph);
            if(!in_array($value['id'], $array_keys)){
                $this->db->setFriends($value['id'], 1);
                array_push($this->onbd,$value['id']);
                echo "Friend id ".$value['id'].".\n";
            }
        }
        
        foreach ($friends as $key => $value) {
            $array_keys = array_keys($this->graph);
            if(!in_array($value['id'], $array_keys)){
                $this->getFriendsOfFriends($value['id']);
            }
        }
        
        
    }
    private function getFriendsOfFriends($user){
        echo "user id ".$user.".\n";
        $friends = $this->cbapi->getFriendOfFriend($user);
        $this->graph[$user] = array_values($friends);
        foreach ($friends as $key => $val){
            if(!in_array($val['id'], $this->onbd)){
                $this->db->addUser($val['id'], $key);
            }
            
            $array_keys = array_keys($this->graph);
            if(!in_array($val['id'], $array_keys)){
                $this->db->setFriends($val['id'], $user);
                array_push($this->onbd,$val['id']);
                echo "Friend id ".$val['id'].".\n";
            }
        }
        
        foreach ($friends as $key => $val){
            $array_keys = array_keys($this->graph);
            if(!in_array($val['id'], $array_keys)){
                $this->getFriendsOfFriends($val['id']);
            }
        }
    }
}
?>
