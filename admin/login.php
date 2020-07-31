<?php session_start();
  if(isset($_SESSION["error"])){
     $error=$_SESSION["error"];
}
else{
    $error="";
}
?>
<!doctype html>
<html lang="en">
  <?php include '../includes/header.php' ?>
  <body>
    <?php
     include '../includes/nav.php';?>
     <?php include '../includes/footer.php';?>
     <div id="login" class=" row ">
        <div class="col-md-6 mx-auto jumbotron">
        <div class="text-danger"><?=$error;?></div>

            <h3 class="gradient text-white" >Log in as an admin</h3>
        <form   id="login-form" method="post" action="actions.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control"  name="email" required id="email">
            </div>
            <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" name="pwd" required  id="pwd">
            </div>
           
            <input type='submit' id="login_admin" value="log in" name="login_admin"
             class="btn text-white gradient">  
</form>
<div class="text-info feedback" ></div>
       </div>
     </body>
</html>