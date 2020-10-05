<?php
class Matches{
  
    // database connection and table name
    private $conn;
    private $table_name = "matches";
  
    // object properties
    public $id;
    public $solids_id;
    public $stripes_id;
    public $date;
    public $winner;
    public $absencent;
    public $solids_left;
    public $stripes_left;
    
    public function __construct($db){
        $this->conn = $db;
    }

    function read(){
    
        $query = "SELECT m.id, m.solids_id, p1.name as 'solids_name', m.stripes_id, p2.name as 'stripes_name', m.date, m.winner, m.absencent, m.solids_left, m.stripes_left FROM $this->table_name m LEFT JOIN people p1 ON p1.id = m.solids_id LEFT JOIN people p2 ON p2.id = m.stripes_id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt;
    }

    function create(){
    
        $query = "INSERT INTO $this->table_name SET solids_id=:solids_id, stripes_id=:stripes_id, date=:date";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(":solids_id", $this->solids_id);
        $stmt->bindParam(":stripes_id", $this->stripes_id);
        $stmt->bindParam(":date", $this->date);
    
        if($stmt->execute()){
            return true;
        }
        return false;

    }

    function readOne(){
        
        $query = "SELECT m.id, m.solids_id, p1.name as 'solids_name', m.stripes_id, p2.name as 'stripes_name', m.date, m.winner, m.absencent, m.solids_left, m.stripes_left, p2.points as 'stripes_points', p1.points as 'solids_points' FROM $this->table_name m LEFT JOIN people p1 ON p1.id = m.solids_id LEFT JOIN people p2 ON p2.id = m.stripes_id WHERE m.id=:id";
        $stmt = $this->conn->prepare( $query );
        
        $stmt->bindParam(":id", $this->id);
        
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    // update the matches
    function update(){

        if ($this->solids_left != null) {
            $query = "UPDATE $this->table_name SET solids_left=:solids_left WHERE id = :id";
    
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // bind new values
            $stmt->bindParam(':solids_left', $this->solids_left);
            $stmt->bindParam(':id', $this->id);
        
            // execute the query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }

        if ($this->stripes_left != null) {
            $query = "UPDATE $this->table_name SET stripes_left=:stripe_left WHERE id = :id";
    
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // bind new values
            $stmt->bindParam(':stripes_left', $this->stripes_left);
            $stmt->bindParam(':id', $this->id);
        
            // execute the query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }
    
        if ($this->absencent != null) {
            $query = "UPDATE $this->table_name SET winner = :winner, absencent=:absencent WHERE id = :id";
    
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // bind new values
            $stmt->bindParam(':winner', $this->winner);
            $stmt->bindParam(':absencent', $this->absencent);
            $stmt->bindParam(':id', $this->id);
        
            // execute the query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }else if ($this->winner != null) {
            $query = "UPDATE $this->table_name SET winner = :winner WHERE id = :id";
    
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // bind new values
            $stmt->bindParam(':winner', $this->winner);
            $stmt->bindParam(':id', $this->id);
        
            // execute the query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        } else{
            $query = "UPDATE $this->table_name SET solids_id = :solids_id, stripes_id = :stripes_id WHERE id = :id";
    
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // bind new values
            $stmt->bindParam(':solids_id', $this->solids_id);
            $stmt->bindParam(':stripes_id', $this->stripes_id);
            $stmt->bindParam(':id', $this->id);
        
            // execute the query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }
        
    }

    // search matches
    function search($keywords){
    
        // select all query
        $query = "SELECT
                    c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                FROM
                    " . $this->table_name . " p
                    LEFT JOIN
                        categories c
                            ON p.category_id = c.id
                WHERE
                    p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
                ORDER BY
                    p.created DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }
}
?>