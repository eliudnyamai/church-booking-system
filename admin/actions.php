<?php
session_start();
include 'functions.php';
$action=new Action;
$crud=new Crud;
if(isset($_POST['insert_admin'])){
    if(isset($_POST['f_name'])){
         $firstname=$_POST['f_name'];
    }
    if(isset($_POST['s_name'])){
        $lastname=$_POST['s_name'];
     }
     if(isset($_POST['pwd'])){
        $pwd=$_POST['pwd'];
     }
     if(isset($_POST['email'])){
         $email=$_POST['email'];
     }
     if(isset($_POST['confirm_pwd'])){
        $confirm_pwd=$_POST['confirm_pwd'];
     }
     if ($pwd===$confirm_pwd) {
       $hashPass = password_hash($pwd, PASSWORD_BCRYPT);
       $table="admin";
       $data=array("firstname"=>$firstname,"lastname"=>$lastname,"email"=>$email ,"password"=>$hashPass,"admin_id"=>NULL);
       if($crud->insertData($table,$data)){
           echo "Record created Successfully. Reload to see it";
        }
     }
     else{
         exit('passwords must match');
     }    
}
if (isset($_POST['insert-speaker'])) {
    if(isset($_POST['f_name'])){
        $firstname=$_POST['f_name'];
    }
   if(isset($_POST['l_name'])){
       $lastname=$_POST['l_name'];
     }
   $table="speaker";
   $data=array("first_name"=>$firstname,"last_name"=>$lastname);
   if($crud->InsertData($table,$data)){
      echo "Record created Successfully. Reload to see it";
     }
}
if (isset($_POST['insert-session'])) {
    if(isset($_POST['s_no'])){
      $s_no=$_POST['s_no'];
   }
if(isset($_POST['speaker'])){
    $speaker=$_POST['speaker'];
 }
 if(isset($_POST['start-time'])){
    $start_time=$_POST['start-time'];
 }
 if(isset($_POST['end-time'])){
    $end_time=$_POST['end-time'];
 }
 if(isset($_POST['topic'])){
     $topic=$_POST['topic'];
 }
 if(isset($_POST['date'])){
   $date=$_POST['date'];
   $date = new DateTime($date);
$now = new DateTime();
if($date < $now) {
   echo "You cannot choose a date in the past";
    exit();
}
}
 $table="session";
 $data=array("session_id"=>NULL, "speaker"=>$speaker,"topic"=>$topic,"start_time"=>$start_time,
 "end_time"=>$end_time,"session_number"=>$s_no,"date"=>$date);
 if($crud->InsertData($table,$data)){
   echo "Record created Successfully. Reload to see it";
 }  
}
if (isset($_POST['session_no'])) {
 $s_no=$_POST['session_no'];
 if(isset($_POST['f_name'])){
   $firstname=$_POST['f_name']; 
 }
 if(isset($_POST['l_name'])){
   $lastname=$_POST['l_name']; 
 }
 if(isset($_POST['phone'])){
   $phone=$_POST['phone']; 
 }
 $table="booking";
 $data=array("booking_id"=>NULL, "first_name"=>$firstname,"last_name"=>$lastname,"phone"=>$phone,"session_id"=>$s_no);
 if($action->checkBookingLimit($s_no)){
 if($crud->InsertData($table,$data)){
   $table="session";
   $column="*";
   $condition=array("session_id"=>$s_no);
   $session=$crud->selectData($table,$column,$condition);
   $bookedSession=$session[0]["session_number"];
   $startTime=$session[0]['start_time'];
   $body= "Thanks $firstname you have made a booking for session $bookedSession. Starting on $startTime keep time please and dont miss";
   $message=array($phone=>$body);
   //$action->sendSMS($message);
   echo "Booking successful! click close and scroll down to see your name and check your phone for a message";
 }  
}
}
if(isset($_POST['login_admin'])){
   $table="admin";
   $column="*";
   if(isset($_POST['pwd'])){
      $pwd=$_POST['pwd'];
   }
   if(isset($_POST['email'])){
      $email=$_POST['email'];
      }
   $condition=array('email'=>$email);
   $data=$crud->selectData($table,$column,$condition);
   if(count($data)>0){
      $hash=$data[0]['password'];
      if (password_verify($pwd, $hash)) {
          $_SESSION["login"]=$data[0]['email'];
          header("Location: index.php");
     }
     else {
          $_SESSION["error"]='Invalid credentials';
          header("Location: login.php");
     }
   
   }
   else{
       $_SESSION["error"]="Email does not exist";
       header("Location: login.php");
   }
}
if(isset($_POST['deleteSession'])){
   $sessionId=$_POST['id'];
   $condition=array('session_id'=>$sessionId);
   $crud->deleteData('session',$condition);
};
if(isset($_POST['deleteSpeaker'])){
   $speakerId=$_POST['id'];
   $condition=array('speaker_id'=>$speakerId);
   $crud->deleteData('speaker',$condition);
};
if(isset($_POST['delete'])){
   $bookingId=$_POST['id'];
   $condition=array('booking_id'=>$bookingId);
   $crud->deleteData('booking',$condition);
};
if(isset($_POST['deleteAdmin'])){
   $adminId=$_POST['id'];
   $condition=array('admin_id'=>$adminId);
   $crud->deleteData('admin',$condition);
};

if(isset($_POST['generate-pdf'])){
   if(isset($_POST['session_id'])){
      $sessionId=$_POST['session_id'];
    }
    $table="booking";
    $column='*';
    $condition=array("session_id"=>$sessionId);
    $data=$crud->selectData($table,$column,$condition);
    $pdf = new PDF();
    $data = $pdf->LoadData($data);
    $pdf->SetFont('Arial','',14);
    $pdf->AddPage();
    $pdf->printTable($data);
    $pdf->Output();
}