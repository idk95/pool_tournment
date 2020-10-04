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
    
        // update query
        $query = "UPDATE $this->table_name SET name = :name, price = :price, description = :description, category_id = :category_id WHERE id = :id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind new values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
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