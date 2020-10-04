<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
include_once '../config/database.php';
include_once '../objects/matches.php';
  
$database = new Database();
$db = $database->getConnection();
  
$matches = new Matches($db);

$stmt = $matches->read();
$num = $stmt->rowCount();
  
if($num>0){
  
    $matches_arr=array();
    $matches_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $matches_item = array(
            "id" => $id,
            "solids_id" => $solids_id,
            "solids_name" => $solids_name,
            "stripes_id" => $stripes_id,
            "stripes_name" => $stripes_name,
            "date" => $date,
            "winner" => $winner,
            "absencent" => $absencent,
            "solids_left" => $solids_left,
            "stripes_left" => $stripes_left

        );
  
        array_push($matches_arr["records"], $matches_item);
    }
  
    http_response_code(200);
    echo json_encode($matches_arr);
}
  
else{
    http_response_code(404);
    echo json_encode(array("message" => "No matches found."));
}
?>