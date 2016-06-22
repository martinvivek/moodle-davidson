(function () {
  var form = document.getElementById("unicko-form"),
      privateMessage = document.getElementById("unicko-private-message");
  if(privateMessage) {
    // delay for private room message
    setTimeout(function() {
      form.submit();
    }, 5000);
  } else {
        form.submit();
  }
})();
