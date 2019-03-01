$(document).ready(function(){
  $("iframe, img").parents("td").css('padding', '3px 3px 0 3px');

  $("table#general-info, table#trip-info, table#gig-info").on('click','a',function(e){
    let what = e.currentTarget.innerHTML.toLowerCase();
    $(".modal#tour span.name").html(what);
    switch(what) {
      case "date":
        $(".modal#tour input[name=value]").attr("type","date");
        $(".modal#tour input[name=value]").attr("value",$(this).parents("td").data("date"));
        break;
      case "time":
        $(".modal#tour input[name=value]").attr("type","time");
        $(".modal#tour input[name=value]").attr("value",$(this).parents("tr").children("td:last").text());
        break;
      case "playtime":
      case "revenue":
        $(".modal#tour input[name=value]").attr("type","number");
        $(".modal#tour input[name=value]").attr("value",$(this).parents("tr").children("td:last").text());
        break;
      default:
        $(".modal#tour input[name=value]").attr("type","text");
        $(".modal#tour input[name=value]").attr("value",$(this).parents("tr").children("td:last").text());
        break;
    }
    $(".modal#tour input[name=what]").attr("value",what);
    $(".modal#tour").fadeIn();
  })

  $("table#social").on('click','a',function(e){
    let what = e.currentTarget.innerHTML.toLowerCase();
    switch(what) {
      case "facebook":
        what = "fb";
      case "fb":
      case "web":
      case "tickets":
        $(".modal#tour span.name").html(what);
        let link = $(this).parents("tr").children("td:last").find("a");
        if(link.length==1) {
          $(".modal#tour input[name=value]").attr("value",link.attr("href"));
        } else {
           $(".modal#tour input[name=value]").attr("value","");
        }
        $(".modal#tour input[name=what]").attr("value",what);
        $(".modal#tour").fadeIn();
        break;
      case "public":
        $(".modal#public p").text("Click change to either publish or hide gig.");
        $(".modal#public").fadeIn();
        break;
    }
  })

  $("a.delete").click(function(){
    $(".modal#delete").fadeIn();
  });
  $("a.clone").click(function(){
    $(".modal#clone").fadeIn();
  });
  $("a.changePoster").click(function(){
    $(".modal#changePoster").fadeIn();
  });
  $("a.deletePoster").click(function(){
    $(".modal#deletePoster").fadeIn();
  });
  $("a.changePromoter").click(function(){
    $(".modal#changePromoter").fadeIn();
  });

  $(".select-promoter").keyup(function(){
    let post = {promoter: $(this).val()}
    if(post.promoter.length>0) {
      let url = "scripts/get-promoters.php"
      $.post(url,post,function(response){
        let data = JSON.parse(response)
        var hint = "";
        data.forEach(function(p){
          let add = '<span data-promoter-id="'+p.promoter_id+'">'+p.promoter_name
          if(p.promoter_company) add += ' ('+p.promoter_company+')'
          add += '</span>'
          hint += add
        })
        $(".promoters-hint").html(hint).slideDown()
      })
    } else {
      $(".promoters-hint").slideUp()
    }
  })

  $(".promoters-hint").on('click','span',function(){
    let id = $(this).data("promoter-id")
    let name = $(this).text()
    $("input[name=selectedPromoter]").attr("value",name)
    $("input[name=promoter]").attr("value",id)
  })

  $("textarea[name=notes]").focusout(function(){
    $("th.notesStatus").removeClass("error success").html('<i class="fa fa-spinner fa-spin"></i>');
    let notes = $(this).val()
    let id = $("#tour input[name=id]").val()
    let postData = {id: id, notes: notes}
    let url = "scripts/write-notes.php"
    $.post(url,postData,function(response){
      setTimeout(function(){
        if(response) {
          $("th.notesStatus").addClass("success").html('<i class="fa fa-check-circle"></i> saved')
        } else {
          $("th.notesStatus").addClass("error").html('<i class="fa fa-times-circle"></i> error saving')
        }
      },1000) //simulace dlouhého ukládání, aby uživatel poznal, že se orpavdu data uložila
    })
  })

});
