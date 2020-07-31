<?php
include 'database.php';
function insert_data($table, $datas = array()){
$database=new Connection();
$db=$database->openConnection();
    foreach($datas as $key => $value) {
        $field []= $key;
        $data []= $value;
    }
    $sql = "INSERT INTO ".$table." (";
    $i = 0;
    for($f=0; $f < count($field); $f++)
    {
        $sql .= ++$i === count($field) ? $field[$f] : $field[$f].", ";
    }
    $sql .= ") VALUES (";
    for($d=0; $d < count($data); $d++)
    {
        $sql .= end($data) === $data[$d] ? (is_string($data[$d]) ? $db->quote($data[$d]) : (is_null($data[$d]) ? "NULL" : $data[$d])) : (is_string($data[$d]) ? $db->quote($data[$d])."," : (is_null($data[$d]) ? "NULL," : $data[$d].","));
    }
    $sql .= ")";
     try {
        $db->exec($sql);
        return true;
        $database->closeConnection();
     } catch (PDOException $e) {
        echo "There is some problem during insertion: " . $e->getMessage();
        return false;
     }
}
function select_data($table, $column, $condition=array(), $condition2=array()){
    $database=new Connection();
    $db=$database->openConnection();
    foreach($condition as $key=> $value) {
        $conditionName= $key;
        $conditionValue= $value;
    } 
    if(count($condition)==0&&count($condition2)==0){
        $sql="SELECT $column FROM ".$table."";
    }
    else if(count($condition)>0&&count($condition2)==0){
        $sql="SELECT $column FROM ".$table." WHERE `$table`.`$conditionName`='$conditionValue'";
    }  
    elseif (count($condition2)>0) {
        foreach($condition2 as $key=> $value) {
            $condition2Name= $key;
            $condition2Value= $value;
        } 
        $sql = "SELECT * FROM `$table` WHERE `$conditionName` LIKE '$conditionValue' AND `$condition2Name` LIKE '$condition2Value'";
    }
   

    try {
        $data=$db->query($sql);
        $data=$data->fetchAll();
        return $data;
        $database->closeConnection();
     } catch (PDOException $e) {
         echo $sql;
        echo "There is some problem during selection: " . $e->getMessage();
        return false;
     }
}
function checkBookingLimit($sessionId){
    $condition=array("session_id"=>$sessionId);
    $bookings=select_data('booking','*', $condition);
    if(count($bookings)==100){
    echo $_SESSION['error']='Booking not successful,Session limit of 100 people reached, Please try to book another session';
     return false;
    }else{
        return true;
    }
}
function checkIfAllSessionFull(){
    $bookings=select_data('session','*'); 
    foreach($bookings as $key => $value) {
        $sessionids []= $value['session_id'];
      
        
    }
    foreach($sessionids as $key => $value) {
        $sessionId= $value;
        $condition=array("session_id"=>$sessionId);
        if(count(select_data('booking','*',$condition))==100){
           $sessionTrackingData[]=1;
        } 
        else{
            $sessionTrackingData[]=0;
        }  
    }
    if(in_array(0,$sessionTrackingData)){
        return false;
    }
    else{
        return count($sessionTrackingData);
    }
}
function createExtraSession(){
if(checkIfAllSessionFull()){  
    $s_no=checkIfAllSessionFull()+1;
    $table="session";
    $data=array("session_id"=>NULL, "speaker"=>'TBD',"topic"=>'TBD',"start_time"=>'TBD',
    "end_time"=>'TBD',"session_number"=>$s_no);
    insert_data($table,$data);  
}
}
function deleteBooking($table,$condition){
    $database=new Connection();
    $db=$database->openConnection();
    foreach($condition as $key=> $value) {
        $conditionName= $key;
        $conditionValue= $value;
    } 
    $sql="DELETE FROM `$table` WHERE `$table`.`$conditionName` = $conditionValue";
    try {
        $db->exec($sql);
        echo "the $table is deleted";
        $database->closeConnection();
     } catch (PDOException $e) {
         echo $sql;
        echo "There is some problem during Deletion: " . $e->getMessage();
        return false;
     }
}