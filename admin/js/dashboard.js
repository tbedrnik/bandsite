$(document).ready(function(){
  var nextTimestamp = $(".counter").html()*1000

  var s = 1000
  var m = 60*s
  var h = 60*m
  var d = 24*h
  var y = 365.242199*d

  function counter(){
    var nowTimestamp = Math.floor(Date.now())
    var timeToNext = nextTimestamp-nowTimestamp

    var unitsToNext = {
        year: Math.floor(timeToNext / y),
        day: Math.floor(timeToNext % y / d),
        hour: Math.floor((timeToNext % d) / h),
        minute: Math.floor((timeToNext % h) / m),
        second: Math.floor((timeToNext % m) / s)
    }

    var output = "";

    for(unit in unitsToNext) {
        let unitToNext = unitsToNext[unit]
        if(unitToNext>0) {
          output += unitToNext + " " + unit
          if(unitToNext>1) output += "s"
          output += " "
        }
    }

    $(".counter").html(output)

  }

  counter()
  setInterval(function(){counter()},1000)
});
