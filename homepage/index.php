<html>
<?php include '../includes/header.php'?>
<body>   
<?php include '../includes/nav.php';
include '../admin/functions.php';
$table="session";
$column="*";
$sessions=select_data($table,$column);

?>
<section class="container-fluid">
        <p>Only people between the age of 13 and 58 year can book</p>
                <div id="user-info" class="text-danger">
                After booking scroll down to confirm so that you do not make a double booking <br>
                You can book for as many people as possible but provide their real names
                </div>
            <div class="row mx-auto  ">
            <?php foreach($sessions as $key => $value): 
              $s_id=$value['session_id'];
              $condition=array("session_id"=>$value['session_id']);
              $slots=count(select_data('booking','*', $condition));
              $remaining=(100-$slots);
              ?>
                <div class="col-md-4 mb-3 ">
                    <div class="card mx-auto" style="width: 18rem;">
                        <div class="card-header gradient text-white">
                       Session<?=$value['session_number'];?>
                        </div>
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item">Topic: <?=$value['topic'];?> </li>
                          <li class="list-group-item">Speaker: <?=$value['speaker'];?></li>
                          <li class="list-group-item">Time: <?=$value['start_time'];?>-<?=$value['end_time'];?></li>
                          <li class="list-group-item">Date:  <?=$value['Date'];?></li>
                          <li class="list-group-item">Slots Remaining: <?=$remaining?></li>

                        </ul>
                        <a href="#" type="button" class="w-75 text-white mx-auto btn gradient" data-toggle="modal" data-target="#sessionModal" data-whatever="<?=$s_id?>">Book session<?=$value['session_number'];?></a>
                      </div>
                </div>
                <?php endforeach; ?>
            </div>
       </section>
       <div class=" col modal fade" id="sessionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header gradient text-center">
              <h5 class="modal-title text-white " id="exampleModalLabel">Fill this form to Book 1st session</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="post" id="booking-form">
              <div class="form-group">
                  <input id="sessionNo" name="session_no" class="form-control" type="hidden" value="" readonly>
                </div>
                <div class="form-group">
                  <label for="recipient-name" class="col-form-label">First name:</label>
                  <input type="text" id="f_name" name="f_name" required class="form-control" >
                </div>
                <div class="form-group">
                  <label for="message-text" class="col-form-label">Last name:</label>
                  <input type="text" id="s_name" name="l_name" required class="form-control" >
                </div>
            
            </div>
            <div id="feedback" class="text-danger feedback"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button id="modalButton" type="submit" class="btn btn-primary">Book this session</button>
            
            </div>
            </form>
          </div>
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
      <td><?=$date?></td>
    </tr>
    <?php 

  endforeach; 
  createExtraSession();
  ?>

  </tbody>
</table>   
</div>  
<?php endforeach; ?>
       </div>
       </div>
      <?php include '../includes/footer.php'?>

</body>
</html>
