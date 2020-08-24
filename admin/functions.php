<?php
use AfricasTalking\SDK\AfricasTalking;
include 'database.php';
require_once '../vendor/setasign/fpdf/fpdf.php';
class Crud extends Connection {
    //Start of  insert function
  public function InsertData($table, $datas = array()){
    $db=$this->openConnection();
        foreach($datas as $key => $value) {
            $field []= $key;
            $data []= $value;
        }
        // Making the insert querry
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
        //End of making sql query
         try {
            $db->exec($sql);
            return true;
            $this->closeConnection();
         } catch (PDOException $e) {
            echo "There is some problem during insertion: " . $e->getMessage();
            return false;
         }
  }
    // Start of select function
    // work still needs to be done to make it more universal
    public function selectData($table, $column,$condition=array(),$condition2=array()){
      $db=$this->openConnection();
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
        $this->closeConnection();
     } catch (PDOException $e) {
        echo "There is some problem during selection: " . $e->getMessage();
        return false;
     }
  }
  //Start of delete data function
 public function deleteData($table,$condition){
    $db=$this->openConnection();
    foreach($condition as $key=> $value) {
        $conditionName= $key;
        $conditionValue= $value;
    } 
    $sql="DELETE FROM `$table` WHERE `$table`.`$conditionName` = $conditionValue";
    try {
        $db->exec($sql);
        echo "the $table is deleted";
        $this->closeConnection();
        return true;
     } catch (PDOException $e) {
         echo $sql;
        echo "There is some problem during Deletion: " . $e->getMessage();
        $this->closeConnection();
        return false;
     }
}
}
class Action extends Crud {
// This function checks if a session has exceeded 100 people
public function checkBookingLimit($sessionId){
    $condition=array("session_id"=>$sessionId);
    $bookings=$this->selectData('booking','*', $condition);
    if(count($bookings)==100){
    echo $_SESSION['error']='Booking not successful,Session limit of 100 people reached, Please try to book another session';
     return false;
    }else{
        return true;
    }
}
// Checks if all sessions are fully booked
public function checkIfAllSessionFull(){
    //get ids of available sessions
    $bookings=$this->selectData('session','*'); 
    foreach($bookings as $key => $value) {
        $sessionids []= $value['session_id'];        
    }
    //loop through each session
    foreach($sessionids as $key => $value) {
        $sessionId= $value;
        $condition=array("session_id"=>$sessionId);
        //while checking if count is equal to 100
        if(count($this->selectData('booking','*',$condition))==100){
            //if equal to 100 push 1 (true) to the sessionTrackingData array
           $sessionTrackingData[]=1;
        } 
        else{
            //else push 0 (false)
            $sessionTrackingData[]=0;
        }  
    }
    if(in_array(0,$sessionTrackingData)){//there is a session which has not reached a limit of 100
        return false;
    }
    else{
        return count($sessionTrackingData);//This is basically the no of the available session
    }
}
// Automatically create a new session if all sessions are full
public function createExtraSession(){
$action=new Action;
if($action->checkIfAllSessionFull()){  
    //checkIfAllSessions full returns the no of available sessions
    $s_no=checkIfAllSessionFull()+1;// add 1 to the returned count to get a correct session no for the prompted session
    $table="session";
    $data=array("session_id"=>NULL, "speaker"=>'TBD',"topic"=>'TBD',"start_time"=>'TBD',
    "end_time"=>'TBD',"session_number"=>$s_no);
    $this->InsertData($table,$data);  
}
}

public function sendSMS($message=array()){
    require '../vendor/autoload.php';
    $username = 'kasaraniAic'; 
    $apiKey   = 'ea06313b6d8572832980b8d8e21b42cc36cd747b74c6a9ead36c5a2cf93067dd'; 
    $AT       = new AfricasTalking($username, $apiKey);
    foreach($message as $key=> $value) {
        $phone= $key;
        $messageBody= $value;
    } 
    $sms      = $AT->sms();
    $result   = $sms->send([
        'to'      => $phone,
        'message' => $messageBody
    ]);
}
}

class PDF extends FPDF
{
function LoadData($data)
{
    return $data;
}
   function Header()
   {
    $this->Cell(180,9,'churchname','B',1,'C');
    $header = array('No.', 'Firstname', 'Lastname', 'Phone','Nextofkin Phone','Temp','Sign');
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(247,250,252);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    $w = array(10, 30, 40, 40,40,40,40);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    $fill = false;
   }
   function Footer()
   {

    $this->Cell(250,9,'','T',1,'C');
   }
function printTable( $data)

{
    // Page header
    $w = array(10, 30, 40, 40,40,40,40);
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(82,86,89);
    $this->SetLineWidth(.3);
    $this->SetFont('','B'); 
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
    $x=0;
    foreach($data as $row)
 
    {
        $this->Cell($w[0],10,$x,'LR',0,'C',$fill);
        $this->Cell($w[1],10,$row['first_name'],'LR',0,'C',$fill);
        $this->Cell($w[2],10,$row['last_name'],'LR',0,'C',$fill);
        $this->Cell($w[3],10,$row['phone'],'LR',0,'C',$fill);
        $this->Cell($w[3],10,'','LR',0,'C',$fill);
        $this->Cell($w[3],10,'','LR',0,'C',$fill);
        $this->Cell($w[3],10,'','LR',0,'C',$fill);
        $this->Ln();
        $fill = !$fill;
        $x++;
      
    }    
}
}
