// definice proměnných v_input = je validní input?
var v_name, v_email, v_event, v_date, v_message = v_name = v_email = v_event = v_date = false

document.addEventListener('ready',moveNavigation)

$(document).ready(function(){

  $(window).scroll(function(){
    moveNavigation()
    moveHomeBackground()
  })

  $(window).resize(function(){
    moveNavigation()
    moveHomeBackground()
  })

  $("nav a").click(function(event){
    var target = "#"+$(this).html().toLowerCase()
    if($(target).length>0) {
      event.preventDefault()
      $("html, body").stop().animate({
        scrollTop: $(target).offset().top
      }, 500)
    }
  })

  $("#booking form input[name=name]").focusout(validateName)
  $("#booking form input[name=email]").focusout(validateEmail)
  $("#booking form input[name=event]").focusout(validateEvent)
  $("#booking form input[name=date]").focusout(validateDate)
  $("#booking form textarea[name=message]").focusout(validateMessage)

  $("#booking form").on('click','input[type=submit]',function(e){
    e.preventDefault()

    validateName()
    validateEmail()
    validateEvent()
    validateDate()
    validateMessage()

    if(v_name&&v_email&&v_event&&v_date&&v_message) {
      $("#booking form").submit();
    }

  })

})

function moveNavigation() {
  $("nav").removeClass("fix")
  if ($(document).scrollTop() > $("nav > ul").position().top)
    $("nav").addClass("fix")
}

function moveHomeBackground() {
  var currentScrollTop = $(window).scrollTop()
  var newValue = ((-1)*currentScrollTop/2)
  $(".header_bg").css('top', newValue)
}

function validateName() {
  let input = $("input[name=name]")
  if(input.val().length>3) {
    input.removeClass("error")
    input.addClass("success")
    v_name = true
  } else {
    input.removeClass("success")
    input.addClass("error")
    v_name = false
  }
}

function validateEmail() {
  let input = $("input[name=email]")
  let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

  if(re.test(input.val())) {
    input.removeClass("error")
    input.addClass("success")
    v_email = true
  } else {
    input.removeClass("success")
    input.addClass("error")
    v_email = false
  }
}

function validateEvent() {
  let input = $("input[name=event]")

  if(input.val().length>3) {
    input.removeClass("error")
    input.addClass("success")
    v_event = true
  } else {
    input.removeClass("success")
    input.addClass("error")
    v_event = false
  }
}

function validateDate() {
  let input = $("input[name=date]")
  let ok = false

  let parts = input.val().split("-")
  if(parts.length==3) {
    if(parts[0]>=1970&&parts[0]<=9999) {
      if(parts[1]>=1&&parts[1]<=12) {
        let daysInMonth = [31,28,31,30,31,30,31,31,30,31,30,31]
        if(parts[2]>=1&&parts[2]<=daysInMonth[parts[1]-1]) {
          ok = true
        } else if (parts[1]==2&&parts[2]==29) {
          if(parts[0]%4==0) {
            if(parts[0]%100) {
              ok = true
            } else if (parts[0]%400==0) {
              ok = true
            }
          }
        }
      }
    }
  }

  if(ok) {
    input.removeClass("error")
    input.addClass("success")
    v_date = true
  } else {
    input.removeClass("success")
    input.addClass("error")
    v_date = false
  }
}

function validateMessage() {
  let input = $("textarea[name=message]")

  if(input.val().length>20) {
    input.removeClass("error")
    input.addClass("success")
    v_message = true
  } else {
    input.removeClass("success")
    input.addClass("error")
    v_message = false
  }
}
