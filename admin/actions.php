<?php
session_start();
include 'functions.php';
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
        insert_data($table,$data);  
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
     insert_data($table,$data);  
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
}
 $table="session";
 $data=array("session_id"=>NULL, "speaker"=>$speaker,"topic"=>$topic,"start_time"=>$start_time,
 "end_time"=>$end_time,"session_number"=>$s_no,"date"=>$date);
 insert_data($table,$data);  
}
if (isset($_POST['session_no'])) {
 $s_no=$_POST['session_no'];
 if(isset($_POST['f_name'])){
   $firstname=$_POST['f_name']; 
 }
 if(isset($_POST['l_name'])){
   $lastname=$_POST['l_name']; 
 }
 $table="booking";
 $data=array("booking_id"=>NULL, "first_name"=>$firstname,"last_name"=>$lastname,"session_id"=>$s_no);
 if(checkBookingLimit($s_no)){
 if(insert_data($table,$data)){
   $table="session";
   $column="*";
   $condition=array("session_number"=>$s_no);
   $session=select_data($table,$column,$condition); 
   echo "Booking successful! click close and scroll down to see your name";
 }  
}
}
if(isset($_POST['login_admin'])){
   $table="Admin";
   $column="*";
   if(isset($_POST['pwd'])){
      $pwd=$_POST['pwd'];
   }
   if(isset($_POST['email'])){
      $email=$_POST['email'];
      }
   $condition=array('email'=>$email);
   $data=select_data($table,$column,$condition);
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
   deleteBooking('session',$condition);
};
if(isset($_POST['deleteSpeaker'])){
   $speakerId=$_POST['id'];
   $condition=array('speaker_id'=>$speakerId);
   deleteBooking('speaker',$condition);
};
if(isset($_POST['delete'])){
   $bookingId=$_POST['id'];
   $condition=array('booking_id'=>$bookingId);
   deleteBooking('booking',$condition);
};
if(isset($_POST['deleteAdmin'])){
   $adminId=$_POST['id'];
   $condition=array('admin_id'=>$adminId);
   deleteBooking('admin',$condition);
};