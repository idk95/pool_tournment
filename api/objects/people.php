<?php
class People{
  
    // database connection and table name
    private $conn;
    private $table_name = "people";
  
    // object properties
    public $id;
    public $name;
    public $points;
    public $balls;
  
    public function __construct($db){
        $this->conn = $db;
    }

    // used by select drop-down list
    public function read(){
    
        $query = "SELECT p.id, p.name, p.points, m.solids_left as 'balls_left' FROM $this->table_name p INNER JOIN matches m ON p.id = m.solids_id
        UNION 
        SELECT p.id, p.name, p.points, m.stripes_left as 'balls_left' FROM $this->table_name p INNER JOIN matches m ON p.id = m.stripes_id
        ORDER BY points DESC, balls_left";
    
        $stmt = $this->conn->prepare( $query );
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

    function update(){
        $query = "UPDATE $this->table_name SET points = :points WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // bind new values
        $stmt->bindParam(':points', $this->points);
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    function readOne(){
        
        $query = "SELECT p.id, p.name, p.points, m.solids_left as 'balls' FROM $this->table_name p INNER JOIN matches m ON p.id = m.solids_id WHERE p.id=:id
        UNION 
        SELECT p.id, p.name, p.points, m.stripes_left as 'balls' FROM $this->table_name p INNER JOIN matches m ON p.id = m.stripes_id
        WHERE p.id = :id";

        $stmt = $this->conn->prepare( $query );
        
        $stmt->bindParam(":id", $this->id);
        
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
}
?>