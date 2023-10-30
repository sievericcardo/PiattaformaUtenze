$(document).ready(function () {
  $("form[name='login']").validate({

    /* Rules for username and password */
    rules: {
      user_name: "required",

      user_pass: {
        required: true,
        minlength: 8
      }
    },

    // Messaggi relativi alle precondizioni
    messages: {
      user_name: "Username richiesto",
      user_pass: {
        required: "Password richiesta",
        minlength: "Password non corretta"
      },
    },
    // Check tipo di action
    beforeSend: function (xhr) {
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
    },
    submitHandler: function (form) {
      $.ajax({
        type: "POST",
        url: '<base_url>/piattaforma-utenze/resources/library/loginUser.php',
        data: $('#login-form').serialize(),
        dataType: 'json',
        success: function (response) {
          var res = JSON.stringify(response);
          var obj = JSON.parse(res);

          if (obj.code == 200) {
            window.location = '<base_url>/piattaforma-utenze/public/';
          } else {
            toast (obj.message);
          }
        },
        error: function (jqXHR, exception) {
          var msg = '';
          if (jqXHR.status === 0) {
              msg = 'Errore di rete.';
          } else if (jqXHR.status == 404) {
              msg = 'Risorsa non trovata';
          } else if (jqXHR.status == 500) {
              msg = 'Errore Interno del server';
          } else if (exception === 'parsererror') {
              msg = "Errore nell'elaborazione del risultato";
          } else if (exception === 'timeout') {
              msg = 'Errore Time out';
          } else if (exception === 'abort') {
              msg = 'Richiesta Ajax annullata.';
          } else {
              msg = 'Errore di Sistema';
          }
          toast(msg);
        },
     });

    return false;
    }
  });
});
