var tmo;

$(document).ready(function(){
  $(".message").on('click','.close',function(){
    $(this).parents(".message").stop().slideUp();
  });
  $(".message").hover(stopOut,startOut);
  startOut();
});

function startOut() {
  tmo = setTimeout(function(){
    $(".message").slideUp();
  },4000);
}

function stopOut() {
  clearTimeout(tmo);
}
