$(document).ready(function(){

  $(".modal-body").on('click','.close',function(){
    $(this).parents(".modal").stop().fadeOut();
  });

});
