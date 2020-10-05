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

    // create scores
    function create(){

        $query = "INSERT INTO $this->table_name SET matches_id=:matches_id, people_id=:people_id, ball=:ball";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":matches_id", $this->matches_id);
        $stmt->bindParam(":people_id", $this->people_id);
        $stmt->bindParam(":ball", $this->ball);
    
        if($stmt->execute()){
            return true;
        }
        return false;
        
    }

    // get score
    function getScore(){
        
        $query = "SELECT matches_id, people_id, ball, name FROM $this->table_name, people WHERE matches_id=:matches_id AND people.id = scores.people_id";
        $stmt = $this->conn->prepare( $query );
        
        $stmt->bindParam(":matches_id", $this->matches_id);
        
        $stmt->execute();

        return $stmt;
    }
}
?>