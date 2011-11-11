<?PHP
define('__ROOT__', dirname(dirname(__FILE__)));

class CodeBitsDatabase {
	private $db;

	function __construct() {
		$this->db = new SQlite3(__ROOT__.'/configs.sqlite3', 0666);
                $this->createTables();
	}
        

	public function addUser($id,$name) {
		$statement = "INSERT INTO Users (id, name) VALUES ("
			. "'" . $this->db->escapeString($id) . "',"
			. "'" . $this->db->escapeString($name) . "'"
			. ")";
                
                return $this->db->exec($statement);
	}

	public function setFriends($id1, $id2) {
            $statement = "INSERT INTO Friends VALUES ("
                    . "'" . $this->db->escapeString($id1) . "',"
                    . "'" . $this->db->escapeString($id2) . "'"
                    . ")";
            $ret = $this->db->exec($statement);
            if(!$ret){
                return ret;
            }
            return true;
	}

	  
        public function getUsers(){
           $statement ="SELECT * FROM Users";
           $query = $this->db->query($statement);
           return $this->fetchAll($query);
        }
        
        public function getFriends($user){
           $statement ="SELECT * FROM Friends inner join User on User.id = Friends.user1 where user1=".$this->db->escapeString($user)." or user2=".$this->db->escapeString($user);
           $query = $this->db->query($statement);
           $all = $this->fetchAll($query);
           $ret = array();
           foreach ($all as $key => $value) {
               if($value['user1']!=$user){
                   $ret[]=$value['user1'];
               }else{
                   $ret[]=$value['user2'];
               }
           }
           return $ret;
        }


        private function fetchAll($query) {
		$result = array();
		while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
			$result[] = $row;
		}
		return $result;
	}

	public function deleteUser($user) {
		// 'ON DELETE CASCADE' ensures all the associated Metrics are deleted as well
		$statement = 'DELETE FROM Users WHERE user="' . $this->db->escapeString($host->getHostname()) . '"';
		return $this->db->exec($statement);
	}

	public function clear() {
		$statement = 'DROP TABLE Users; DROP TABLE Friends;';
		$this->db->exec($statement);
		$this->createTables();
	}

	private function createTables() {
		$statement = 'CREATE TABLE IF NOT EXISTS Users ('
				. 'id INTEGER PRIMARY KEY NOT NULL,'
				. 'name VARCHAR NOT NULL,'
                                . 'url VARCHAR NOT NULL,'
                                . 'karma INTEGER NOT NULL'
				. ')';
                
		$this->db->exec($statement);

		$statement = 'CREATE TABLE IF NOT EXISTS Friends ('
			. 'user1 INTEGER REFERENCES Users(id) ON DELETE CASCADE,'
                        . 'user2 INTEGER REFERENCES Users(id) ON DELETE CASCADE,'
                        . 'UNIQUE(user1, user2)'
			. ')';
		$this->db->exec($statement);
	}
        public function getLastErrorMsg(){
            return $this->db->lastErrorMsg();
        }
}

?>
