<?php session_start();
if(!isset($_SESSION['login'])){
header("location:login.php");
    } 
?>
<!doctype html>
<html lang="en">
  <?php include '../includes/header.php'?>
  <body>
    <?php
     include '../includes/nav.php';
     include 'functions.php';     
     $table="speaker";
     $column="*";
     $data=select_data($table, $column);
    
     $table="Admin";
     $column="*";
     $current_admins=select_data($table,$column);

     $table="session";
     $column="*";
     $sessions=select_data($table,$column);
    
    ?>
    <div class="sidebar">
        <a class="active"  href="#home">Dashboard</a>
        <a class="tablinks" onclick="openCity(event, 'create-admin')" href="#news">Create Admin</a>
        <a class="tablinks" onclick="openCity(event, 'add-speaker')" href="#contact">Add speaker</a>
        <a class="tablinks" onclick="openCity(event, 'add-session')" href="#">Add session</a>
    </div>
    <div class="content  ">
    
        <div id="create-admin" class="tabcontent row">
        <div class="col-md-6 shadow">
            <h3 class="gradient text-white" >Fill this form to create a new admin</h3>
        <form   id="admin-form" method="post" action="actions.php">
            <div class="form-group">
                <label for="f_name">First name:</label>
                <input type="text" class="form-control" name="f_name" required id="f_name">
            </div>
            <div class="form-group">
                <label for="s_name">Last name:</label>
                <input type="text" class="form-control" name="s_name" required id="s_name">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control"  name="email" required id="email">
            </div>
            <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" name="pwd" required  id="pwd">
            </div>
            <div class="form-group">
                <label for="pwd">Confirm password:</label>
                <input type="password" class="form-control" name="confirm_pwd" required  id="confirm_pwd">
            </div>
            <input type='submit' id="insert_admin" value="Create-admin" name="insert_admin"
             class="btn text-white gradient">  
</form>
<div class="text-info feedback" ></div>
       </div>
       <div class="col-md-6 ">
       <table class="table shadow table-striped">
       <h3 class="text-info" >Current Admins</h3>
  <thead>
    <tr>
      <th scope="col">serial</th>
      <th scope="col">Firstname</th>
      <th scope="col">Lastname</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($current_admins as $key => $value): ?>
    <tr>
      <td><?=$value['admin_id'];?></td>
      <td><?=$value['firstname'];?></td>
      <td><?=$value['lastname'];?></td>
      <td>
      <form class="delete-admin" method="post">
        <button id="<?=$value['admin_id']?>"  class="border-0 deleteAdmin" >
        <i class="fa fa-trash-o " aria-hidden="true"></i></button>
      </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
       </div>
        </div>

        <div id="add-speaker" class="tabcontent row">
        <div class="col-md-6 shadow">
        <h3 class="gradient text-white" >Fill this form to add a new speaker</h3>
        <form method="post" id="speaker-form">
            <div class="form-group">
                <label for="f_name">First name:</label>
                <input type="text" class="form-control" name="f_name" id="f_name">
            </div>
            <div class="form-group">
                <label for="s_name">last name:</label>
                <input type="text" class="form-control" name="l_name" id="l_name">
            </div>
            <input  id="insert-speaker" name="insert-speaker" value="create speaker" type="submit" class="btn text-white gradient">
        </form>
        <div class="text-info feedback"></div>
        </div>
        <div class="col-md-6 shadow"> 
        <table class="table shadow table-striped">
       <h3 class="text-info" >Speakers</h3>
  <thead>
    <tr>
      <th scope="col">serial</th>
      <th scope="col">Firstname</th>
      <th scope="col">Lastname</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($data as $key => $value): ?>
    <tr>
      <td><?=$value['speaker_id'];?></td>
      <td><?=$value['first_name'];?></td>
      <td><?=$value['last_name'];?></td>
      <td>
      <form class="delete-speaker" method="post">
        <button id="<?=$value['speaker_id']?>"  class="border-0 deleteSpeaker" >
        <i class="fa fa-trash-o " aria-hidden="true"></i></button>
      </form>


      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>        </div>
        </div>
            

        <div id="add-session" class="tabcontent row">
            <div class="col-md-6 shadow">
            <h3 class="gradient text-white" >Fill this form to add a new session</h3>
        <form id="session-form" method="post">
            <div class="form-group">
                <label for="s_no">Session number:</label>
                <input type="number" class="form-control" name="s_no" required id="s_no">
            </div>
            <div class="form-group">
                <label for="speaker">Select speaker</label>
                <select class="form-control" name="speaker"  id="speaker" >
                <?php foreach($data as $key => $value): ?>
                <option value="<?=$value['first_name'];?> <?=$value['last_name'];?>"><?=$value['first_name'];?> <?=$value['last_name'];?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="start-time">Start Time:</label>
                <input type="time" class="form-control timepicker" required name="start-time" id="start-time">
            </div>
            <div class="form-group">
                <label for="end-time">End Time:</label>
                <input type="time" class="form-control" required name="end-time" id="start-time">
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" required name="date" id="date">
            </div>
            <div class="form-group">
                <label for="topic">Topic:</label>
                <input type="text" class="form-control"  name="topic" id="topic">
            </div>
            <input id="insert-session" name="insert-session" value="create session" type="submit" class="btn text-white gradient">
        </form>
        <div class="text-info feedback" ></div>
            </div>
        <div class="col-md-6  ">
        <table class="table table-striped">
        <h3 class="text-info " >Sessions</h3>
  <thead>
    <tr>
      <th scope="col">Session no</th>
      <th scope="col">Speaker</th>
      <th scope="col">Topic</th>
      <th scope="col">Start-time</th>
      <th scope="col">End-time</th>
      <th scope="col">Date</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($sessions as $key => $value): ?>
    <tr>
      <td><?=$value['session_number'];?></td>
      <td><?=$value['speaker'];?></td>
      <td><?=$value['topic'];?></td>
      <td><?=$value['start_time'];?></td>
      <td><?=$value['end_time'];?></td>
      <td><?=$value['Date'];?></td>
      <td>
      <form class="delete-session" method="post">
        <button id="<?=$value['session_id']?>"  class="border-0 deleteSession" >
        <i class="fa fa-trash-o " aria-hidden="true"></i></button>
      </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>      
        </div>
    </div>     
<div class="container-fluid row mx-auto " >
       <?php foreach($sessions as $key => $value): 
        $date=$value['Date'];
        ?>       
       <div id="bookings" class="col-md-4 shadow  " >
       <table class="table table-striped">
        <h3 class="text-info " >Session<?=$value['session_number'];?> bookings</h3>
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Firstname</th>
      <th scope="col">Lastname</th>
      <th scope="col">Date</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php
  $condition=array("session_id"=>$value['session_id']);
  $i=0;
  foreach(select_data('booking','*', $condition) as $key => $value):
  $i++;
  ?>
    <tr>
     <td><?=$i;?></td>
      <td><?=$value['first_name'];?></td>
      <td><?=$value['last_name'];?></td>
      <td><?=$date;?></td>
      <td>
      <form class="delete-form" method="post">
      <button id="<?=$value['booking_id']?>"  class="border-0 delete" ><i class="fa fa-trash-o " aria-hidden="true"></i></button>
      </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>   
</div>  
<?php endforeach; ?>
       </div>
       </div>
       </div>
       </div>
    <?php include '../includes/footer.php'?>
</html>
