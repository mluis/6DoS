<?php

require_once 'CodebitsApi.php';

class TextAPI {

    private $cbapi;
    private $checked = array();
    private $theman = 'Eriksson Monteiro';
    private $tocheck = array();
    private $friends = array();

    public function __construct() {
        $this->cbapi = new CodebitsApiUtils('eriksson.monteiro@ua.pt', 'mon9teiro');
    }
    
    

    public function init() {
        $friends = $this->cbapi->getFriends();
        array_push($this->checked, $this->cbapi->getUID());
        //print_r($friends);

        foreach ($friends as $key => $value) {
            if ($key == $this->theman) {
                echo "found " . $key . " on level" . $level . "!.\n";
            } else {
                array_push($this->tocheck, $value);
            }
        }
        $this->getFriends(0);
    }

    
    
    private $counter = 0;

    private function getFriends($level) {
        echo "Next level " . $level . ".\n";

        foreach ($this->tocheck as $ikey => $ivalue) {
            if (!in_array($ivalue, $this->checked)) {
                array_push($this->checked, $ivalue);
                $friendsOfFriends = $this->cbapi->getFriendOfFriend($ivalue);
                foreach ($friendsOfFriends as $fkey => $fval) {
                    if (!in_array($fval, $this->checked) && !in_array($fval, $this->tocheck)) {

                        $this->counter+=1;
                        if ($fkey == $this->theman) {
                            echo "found " . $fkey . " id ".$fval." on level" . $level . "!.\n";
                        } else {
                            array_push($this->friends, $fval);
                        }
                    }
                    unset($friendsOfFriends[$fkey]);
                }
            }
        }

        $mlevel = $level + 1;
        $this->tocheck = $this->friends;
        $this->friends = array();
        print_r($this->checked);
        $this->getFriends($mlevel);
    }

}

?>
