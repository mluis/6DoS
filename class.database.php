<?PHP
class CodeBitsDatabase {
	private $db;

	function __construct() {
		$this->db = new SQlite3('configs.sqlite3', 0666);
                $this->createTables();
	}
        

	public function addUser($values,$name) {
		$statement = "INSERT INTO Users (id, name, url, karma) VALUES ("
			. "'" . $this->db->escapeString($values['id']) . "',"
			. "'" . $this->db->escapeString($name) . "',"
                        . "'" . $this->db->escapeString($values['avatar']) . "',"
                        . "'" . $this->db->escapeString($values['karma']) . "'"
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
           $statement ="SELECT Users.* FROM Friends inner join Users on Users.id = Friends.user1 where Friends.user1 !=".$this->db->escapeString($user)." and Friends.user2=".$this->db->escapeString($user);
           $query = $this->db->query($statement);
           $ret = $this->fetchAll($query);
           $statement ="SELECT Users.* FROM Friends inner join Users on Users.id = Friends.user2 where Friends.user2 !=".$this->db->escapeString($user)." and Friends.user1=".$this->db->escapeString($user);
           $query = $this->db->query($statement);
           $ret_ = $this->fetchAll($query);
           
           return array_merge($ret,$ret_);
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
                                . 'karma VARCHAR NOT NULL'
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
