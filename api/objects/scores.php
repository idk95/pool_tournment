<?php
class Scores{
  
    // database connection and table name
    private $conn;
    private $table_name = "scores";
  
    // object properties
    public $matches_id;
    public $people_id;
    public $ball;
  
    public function __construct($db){
        $this->conn = $db;
    }

    // used by select drop-down list
    public function read(){
    
        $query = "SELECT * FROM $this->table_name WHERE matches_id=:matches_id";
        $stmt = $this->conn->prepare( $query );
        
        $stmt->bindParam(":matches_id", $this->matches_id);
        $stmt->execute();
    
        return $stmt;
    }

    // create people
    function create(){

        $ids = array();

        foreach ($this->name as $value) {
            $query = "INSERT INTO $this->table_name SET name=:name";
        
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":name", $value);
        
            $stmt->execute();
            array_push($ids, $this->conn->lastInsertId());
        }
    
        return $ids;
        
    }
}
?>