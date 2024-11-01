// Disable right click script
var message = "";
///////////////////////////////////
function clickIE() {
  if (document.all) {
    (message);
  }
}

function clickNS(e) {
  if (document.layers || (document.getElementById && !document.all)) {
    if (e.which == 2 || e.which == 3) {
      (message);
      //alert("ACCESS FORBIDDEN!\nRight Click is absolutely forbidden!");
      return false;
    }
  }
}
if (document.layers) {
  document.captureEvents(Event.MOUSEDOWN);
  document.onmousedown = clickNS;
} else {
  document.onmouseup = clickNS;
  document.oncontextmenu = clickIE;
}

document.oncontextmenu = new Function("return false")

//Disabling ctrl + v on the keyboard
/*document.onkeydown = function(e) {
  if (e.ctrlKey && 
      (e.keyCode === 67 || 
       e.keyCode === 86 || 
       e.keyCode === 85 || 
       e.keyCode === 117)) {
      //alert("ACCESS FORBIDDEN!\nThe Action you're trying to perform is absolutely"+
      //" Forbidden!");
      //swal("ACCESS FORBIDDEN!","The Task you're trying to perform is absolutely Forbidden!","error")
      return false;
  } else {
      return true;
  }
}*/

// ctrl c and ctrl u
$(document).keydown(function(event) {
  var pressedKey = String.fromCharCode(event.keyCode).toLowerCase();
  //event.ctrlKey && (pressedKey == "c" || pressedKey == "u")
  if (event.ctrlKey && pressedKey == "u") {
  //alert('Sorry, This Functionality Has Been Disabled!');
  //disable key press porcessing
  return false;
  }
});
// f12
document.onkeypress = function (event) {
  event = (event || window.event);
  if (event.keyCode == 123) {
    return false;
  }
}

document.onmousedown = function (event) {
  event = (event || window.event);
  if (event.keyCode == 123) {
    return false;
  }
}
document.onkeydown = function (event) {
  event = (event || window.event);
  if (event.keyCode == 123) {
    return false;
  }
}

$(document).keypress("u",function(e) {
  if(e.ctrlKey){
    return false;
  }
  else{
    return true;
  }
});