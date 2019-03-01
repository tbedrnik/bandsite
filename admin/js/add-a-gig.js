$(document).ready(function(){

  $("input[name=venue]").focusout(function(){
    writeTripData($(this).val())
  })

  $("input[name=address]").focusout(function(){
    writeTripData($(this).val())
  })

  $("input[readonly]").focusin(function(){
    $(this).blur()
  })

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
})

function writeTripData(target) {
  let post = {trg:target}
  let url = "scripts/get-distance.php"
  $.post(url,post,function(mapsdata){
    var data = JSON.parse(mapsdata)
    console.log(data)
    if(data.status){
      $("input[name=address]").attr("value",data.address)
      $("input[name=trip-info]").attr("value",data.text)
      $("input[name=venue-info]").slideUp()
      $("input[name=trip-info]").parent().slideDown()
    }
    else {
      $("input[name=venue-info]").attr("value","Venue not found! Please specify the address.")
      $("input[name=trip-info]").parent().slideDown()
    }
  })
}
