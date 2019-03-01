$(document).ready(function(){
  $(".new-member").click(function(){
    $(".modal#new-member").fadeIn();
  });
  $(".edit-member").click(function(){
    $("#edit-member input[name=id]").attr("value",$(this).parents("tr").prev("tr").data("member"));
    $("#edit-member input[name=nickname]").attr("value",$(this).parents("tr").prev("tr").children("td.nickname").text());
    $("#edit-member input[name=role]").attr("value",$(this).parents("tr").prev("tr").children("td.role").text());
    $("#edit-member input[name=fullname]").attr("value",$(this).parents("tr").prev("tr").children("td.fullname").text());
    $("#edit-member input[name=email]").attr("value",$(this).parents("tr").children("td.email").text());
    $("#edit-member input[name=phone]").attr("value",$(this).parents("tr").children("td.phone").text());
    let p = $(this).parents("tr").prev("tr").data("public");
    $("#edit-member input[name=public]").attr("value",p);
    if(p){
      $("#edit-member input[data-name=secret]").removeClass("checked");
      $("#edit-member input[data-name=public]").addClass("checked");
    } else {
      $("#edit-member input[data-name=public]").removeClass("checked");
      $("#edit-member input[data-name=secret]").addClass("checked");
    }
    $(".modal#edit-member").fadeIn();
  });
  $(".delete-member").click(function(){
    $("#delete-member input[name=id]").attr("value",$(this).parents("tr").prev("tr").data("member"));
    $("#delete-member .name").text($(this).parents("tr").prev("tr").children("td.fullname").text());
    $(".modal#delete-member").fadeIn();
  });
  $("input[type=button]").click(function(){
    $("input[name=public]").val($(this).data("value"));
    $(this).parents(".group").children("input").removeClass("checked");
    $(this).addClass("checked");
  });
});
