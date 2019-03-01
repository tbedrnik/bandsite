$(document).ready(function(){
  $("input[name=to]").focusout(validateTo)
  $("input[name=subject]").focusout(validateSubject)
  $("textarea[name=message]").focusout(validateMessage)

  $("input[type=submit]").click(function(e){
    e.preventDefault()
    validateTo()
    validateSubject()
    validateMessage()
    if(v_to&&v_subject&&v_message) {
      $("form").submit()
    }
  })
})



function validateTo() {
  let input = $("input[name=to]")
  let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

  if(re.test(input.val())) {
    input.removeClass("error")
    input.addClass("success")
    v_to = true
  } else {
    input.removeClass("success")
    input.addClass("error")
    v_to = false
  }
}

function validateSubject() {
  let input = $("input[name=subject]")
  if(input.val().length>0) {
    input.removeClass("error")
    input.addClass("success")
    v_subject = true
  } else {
    input.removeClass("success")
    input.addClass("error")
    v_subject = false
  }
}

function validateMessage() {
  let input = $("textarea[name=message]")
  if(input.val().length>0) {
    input.removeClass("error")
    input.addClass("success")
    v_message = true
  } else {
    input.removeClass("success")
    input.addClass("error")
    v_message = false
  }
}
