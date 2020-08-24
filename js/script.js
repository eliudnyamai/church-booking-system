function displayForm(evt, cityName) {
    var i, tabcontent, tablinks;
    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "flex";
    evt.currentTarget.className += " active";
}
function sendAjaxRequest( form, button,page) {    
    feedback=$('.feedback');
    $('#'+form).submit(function( e ) {
        e.preventDefault();
        $('#'+button).attr('value', 'Sending...');
        feedback.empty().text('Sending');
        serialdata=$(this).serialize();
        post=$.post( page,$(this).serialize()+"&"+button+"=true", )
            .done(function(Data){
                $('#'+button).attr('value', 'created');
                    setTimeout(function(){
                        $('#'+button).attr('value', 'create another');
                            feedback.empty().text(Data);
                                 }, 1000);
                                 return true;
             })
             .fail(function(){
              feedback.empty().text('An error occured, Try again');
              return false;
            })
            .always(function(data){
              feedback.text(data);
            });  
      });
  }
  function sendDeleteRequest(form, buttonId,buttonClass,page) {
    feedback=$('.feedback');
    $('.'+form).submit(function( e ) {
      e.preventDefault()
       post=$.post( page,$(this).serialize()+"&id="+buttonId+"&"+buttonClass+"=true")
         .done(function(data){
          setTimeout(function(){
          alert(data);
          location.reload(true);
           }, 1000);
         })
         .fail(function(){
          return false;
         })
     });
  }
  $('#insert_admin').one('click',function() {
    var r=confirm("You are about to  create an admin press ok to continue or cancel to stop");
    if (r==true)
    {
    sendAjaxRequest( 'admin-form', 'insert_admin','actions.php');
    }
    });

  $('#insert-speaker').one('click',function() {
    var r=confirm("You are about to  create a speaker press ok to continue or cancel to stop");
    if (r==true)
    {
    sendAjaxRequest( 'speaker-form', 'insert-speaker','actions.php');
    }
  });

  $('#insert-session').one('click',function() {
    var r=confirm("You are about to  create a session press ok to continue or cancel to stop");
    if (r==true)
    {
    sendAjaxRequest( 'session-form', 'insert-session','actions.php');
    }
  });

  $('#sessionModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var session = button.data('whatever') // Extract info from data-* attributes
    $('#sessionNo').attr('value', session);
    var modal = $(this)
    modal.find('.modal-title').text('Provide your names to book this session')
  });

  $('#modalButton').one('click',function() {
    var r=confirm("You are about to make a booking press ok to continue or cancel to stop");
    if (r==true)
    {
      if(!event.detail || event.detail == 1){//activate on first click only to avoid hiding again on multiple clicks
        // code here. // It will execute only once on multiple clicks
        sendAjaxRequest( 'booking-form', 'modalButton','../admin/actions.php');
      }    
    }
  });

  $('#sessionModal').on('hide.bs.modal', function (event) {
    location.reload(true);
  });
  
  $('.delete').one('click',function() {
    var r=confirm("You are about to  delete a booking press ok to continue or cancel to stop");
    if (r==true)
    {
  sendDeleteRequest('delete-form',this.id,'delete','actions.php');
    }
  });
  $('.deleteSession').one('click',function() {
    var r=confirm("You are about to  delete a session press ok to continue or cancel to stop");
    if (r==true)
    {
    sendDeleteRequest('delete-session',this.id,'deleteSession','actions.php');
    }
    });
  $('.deleteSpeaker').one('click',function() { 
    var r=confirm("You are about to  delete a speaker press ok to continue or cancel to stop");
    if (r==true)
    {
        sendDeleteRequest('delete-speaker',this.id,'deleteSpeaker','actions.php');
    }
        });
  $('.deleteAdmin').one('click',function() {
    var r=confirm("You are about to  delete an admin press ok to continue or cancel to stop");
    if (r==true)
    {
      sendDeleteRequest('delete-admin',this.id,'deleteAdmin','actions.php');
    }
    });
  


   