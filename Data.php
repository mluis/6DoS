<?php
require_once 'class.database.php';
class Data{
    private static $db;
    private static $graph;
    private static $instance;
    public static function getInstance() {
            if (!self::$instance instanceof self) { 
              self::$instance = new Data();
            }
            return self::$instance;
    }
    
    public static function getGraph(){
        return Data::$graph;
    }

    private function __construct() {
        Data::$db = new CodeBitsDatabase();
        Data::createGraphFromDB();
    }
    
    private static function createGraphFromDB(){
        Data::$graph=array();
        $users = Data::$db->getUsers();
        foreach ($users as $key => $value) {
            $friends = Data::$db->getFriends($value['id']);
            Data::$graph[$value['name']]=array('info'=> $value,'friends'=>  array_values($friends));
        }
    }
}
?>
