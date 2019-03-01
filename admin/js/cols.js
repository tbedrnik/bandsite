/*
 *  Author: Tomas Bedrnik
 *  Description:
    Auto wrapping multiple label+inputs in divs and setting its parent div to grid display and col template to actual cols number
*/

$(document).ready(function(){
  $(".form-group.cols").each(function(){
    var columns=0;
    $(this).children("label").map(function(index){
      $(this).add($(this).next()).wrapAll('<div class="col" col='+index+'>');
      columns+=1;
    });
    $(this).css({
    'display': 'grid',
    'grid-gap': '10px',
    'grid-template-columns': 'repeat('+columns+',1fr)'
    });
  });

  $(".content > .cols").each(function(){
    let cols = $(this).children(".col").length;
    $(this).css({
      'display': 'grid',
      'grid-gap': '10px',
      'grid-template-columns': 'repeat('+cols+',auto)'
    });
  });
});
