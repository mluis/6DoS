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
    private $checked = array();
    private $tocheck = array();
    private $friends = array();
    private $par = array();
    public function  getUser($userid){
        return $this->cbapi->getUser($userid);
    }

    public function getPath($username1, $username2){
        $this->checked = array();
        $this->tocheck = array();
        $this->friends = array();
        $this->par = array();
        $ret = array();
        if($this->userGraph($username1, $username2,$ret,$v)){
            $ret[$username1]=array('info'=> $this->graph[$username1]['info'],'friends'=>  array($v));
            return $ret;
        }
        return false;
    }
    private function userGraph($username1, $username2, array &$ret, &$v){
        if($this->get_Path($username1, $username2,$ret,$v)){
            $ret[$this->par[$v['id']]]=array('info'=> $this->graph[$this->par[$v['id']]]['info'],'friends'=>  array($v));
            $v = $this->graph[$this->par[$v['id']]]['info'];
            return true;
        }
        return false;
    }
    
    private function get_Path($username1, $username2, array &$ret=NULL, &$v = NULL){
        if(isset ($this->graph[$username1]) && isset ($this->graph[$username2])){
            foreach ($this->graph[$username1]['friends'] as $key => $value) {
                if($value['name']==$username2){
                    $ret[$value['name']]=array('info'=> $value,'friends'=>  array());
                    $v = array_values($value);
                    return true;
                }else{
                    if(!in_array($value['id'], $this->tocheck)){
                        $this->tocheck[$value['name']]=$value['id'];
                        $this->par[$value['id']]=$username1;
                    }
                }
            }
        }
        
        if($this->process($username2, $ret, $v)){
            $ret[$this->par[$v['id']]]=array('info'=> $this->graph[$this->par[$v['id']]]['info'],'friends'=>  array($v));
            $v = $this->graph[$this->par[$v['id']]]['info'];
            return true;
        }
        return false;
    }

    private function process($fusername, array &$ret=NULL, &$v = NULL){
        
        foreach ($this->tocheck as $ikey => $ivalue) {
           
            if (!in_array($ivalue, $this->checked)) {
                array_push($this->checked, $ivalue);
                $friendsOfFriends = $this->graph[$ikey]['friends'];
                foreach ($friendsOfFriends as $fkey => $fval) {
                    if (!in_array($fval['id'], $this->checked) || !in_array($fval['id'], $this->tocheck) || !in_array($fval['id'], $this->friends)) {
                        if ($fval['name'] == $fusername) {
                            $this->par[$fval['id']]=$ikey;
                            $ret[$fval['name']]=array('info'=> $fval,'friends'=>  array());
                            $v = $fval;
                            return true;
                        } else {
                            $this->friends[$fval['name']]=$fval['id'];
                            $this->par[$fval['id']]=$ikey;
                        }
                    }
                    unset($friendsOfFriends[$fkey]);
                }
            }
        }
        
        $this->tocheck = $this->friends;
        $this->friends = array();
        if($this->process($fusername, $ret, $v)){
            $ret[$this->par[$v['id']]]=array('info'=> $this->graph[$this->par[$v['id']]]['info'],'friends'=>  array($v));
            $v = $this->graph[$this->par[$v['id']]]['info'];
            return true;
        }
        return false;
    }
    
    public function getGraph($data){
        $ret = array('nodes'=>array(), 'links'=>array());
        $index = array();
        
        foreach ($data as $key => $value){
            array_push($ret['nodes'],array('name'=>$value['info']['name'],'url'=>$value['info']['url'],'friends'=>  sizeof($this->graph[$value['info']['name']]['friends'])));
            $index[]=$value['info']['name'];
        }
        
        foreach ($data as $key => $value){
            $array_search = array_search($value['info']['name'], $index);
            foreach ($value['friends'] as $key => $value){
                $array_search0 = array_search($value['name'], $index);
                array_push($ret['links'], array('source'=>$array_search,'target'=>$array_search0, 'value' => 1));
            }
        }
        
        
        $json_encode = json_encode($ret,true);
        $json_encode = str_replace('"nodes"', "nodes", $json_encode);
        $json_encode = str_replace('"links"', "links", $json_encode);
        $json_encode = str_replace('"source"', "source", $json_encode);
        $json_encode = str_replace('"target"', "target", $json_encode);
        $json_encode = str_replace('"value"', "value", $json_encode);
        return "var miserables = ".$json_encode;
    }


    public function createGraphFromDB(){
        unset ($this->graph);
        $users = $this->db->getUsers();
        foreach ($users as $key => $value) {
            $friends = $this->db->getFriends($value['id']);
            $this->graph[$value['name']]=array('info'=> $value,'friends'=>  array_values($friends));
        }
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
