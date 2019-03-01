$(document).ready(function(){
  //When click on Delete link, open modal and set its id input value to caller id
  $("a.delete").click(function(e){
    e.preventDefault();
    let id = $(this).parents("tr").data("gig");
    $(".modal input[name=id]").val(id);
    $(".modal").fadeIn();
  });
});
