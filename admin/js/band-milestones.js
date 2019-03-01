$(document).ready(function(){
  $(".add").click(function(){
    $(".modal#add").fadeIn();
  });
  $(".change").click(function(){
    $("#change input[name=id]").attr("value",$(this).parents("tr").data("milestone"));
    $("#change input[name=date]").attr("value",$(this).parents("tr").children("td:nth-child(1)").html());
    $("#change input[name=value]").attr("value",$(this).parents("tr").children("td:nth-child(2)").html());
    $(".modal#change").fadeIn();
  });
  $(".delete").click(function(){
    $("#delete input[name=id]").attr("value",$(this).parents("tr").data("milestone"));
    $("#delete .date").html($(this).parents("tr").children("td:nth-child(1)").html());
    $("#delete .value").html($(this).parents("tr").children("td:nth-child(2)").html());
    $(".modal#delete").fadeIn();
  });
});
