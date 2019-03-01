$(document).ready(function(){
  $(".add-promoter").click(function(){
    $(".modal#add-promoter").fadeIn();
  });
  $(".edit-promoter").click(function(){
    $("#edit-promoter input[name=id]").attr("value",$(this).parents("tr").data("promoter"));
    $("#edit-promoter input[name=name]").attr("value",$(this).parents("tr").children("td:nth-child(1)").text());
    $("#edit-promoter input[name=company]").attr("value",$(this).parents("tr").children("td:nth-child(2)").text());
    $("#edit-promoter input[name=email]").attr("value",$(this).parents("tr").children("td:nth-child(3)").text());
    $("#edit-promoter input[name=phone]").attr("value",$(this).parents("tr").children("td:nth-child(4)").text());
    $(".modal#edit-promoter").fadeIn();
  });
  $(".delete-promoter").click(function(){
    $("#delete-promoter input[name=id]").attr("value",$(this).parents("tr").data("promoter"));
    $("#delete-promoter .name").html($(this).parents("tr").children("td:nth-child(1)").text()+" ("+$(this).parents("tr").children("td:nth-child(2)").text()+")");
    $(".modal#delete-promoter").fadeIn();
  });
});
