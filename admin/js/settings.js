$(document).ready(function(){
  $("td > a").click(function(e){
    e.preventDefault();
    let id = $(this).parents("tr").data("set");
    $(".modal input[name=id]").val(id);
    $(".modal .name").html($(this).parents("tr").children("td:first").html());
    $(".modal input[name=value]").attr("value",$(this).parents("tr").children("td:last").html());
    $.get("scripts/get-description.php?g="+id,function(response){
      $(".modal .help p").html(response);
    });
    $(".modal").fadeIn();
  });

  $(".modal .help").on("click","a",function(e){
    $(".modal input[name=value]").attr("value",e.currentTarget.innerHTML);
  });
});
