/* Function to change navbar from responsive into non-responsive */
function toggleNavbar () {
  var nav = document.getElementById('navbar');

  /* We check if our nav has the class navabar. If so, we'll add the responsive,
   * we'll give navbar otherwise.
   * not that in the if statement we used three = since we want to make sure
   * that not only it has the correct type but they are equals too. */
  if(nav.className === 'navbar') {
    nav.className += ' responsive';
  } else {
    nav.className = 'navbar';
  }
}

function createIngredient () {
  var newPlaceholder = document.createElement('div');
}

/* Toast message that pop ups */
function toast (message) {
  var toastElement = document.getElementById('toast');

  $('#toast_message').remove();
  $('#toast').append('<span id="toast_message">' + message +'</span>');

  toastElement.className = "show";

  setTimeout(function() {
    toastElement.className = toastElement.className.replace(
      "show", ""
    );
  }, 3500);
}

function loadContent(idName, fileName) {
  document.getElementById(idName).innerHTML = "";
  $('#'+idName).load("<base_url>/piattaforma-utenze/resources/templates/" + fileName);
}

