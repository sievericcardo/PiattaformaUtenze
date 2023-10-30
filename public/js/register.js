// Verifica client side dell'input
$(document).ready(function () {
  $("form[name='register']").validate({ 
    rules: {
      user_name: {
        required: true,
        minlength: 4
      },
      user_email: {
        required: true,
        minlength: 8
      },
      user_pass: {
        required: true,
        minlength: 4
      },
      user_pass_check: {
        required: true,
        minlength: 8
      }

    },
    /* Messages for previous rules */
    messages: {
      user_name: {
        required: "Campo richiesto",
        minlength: "Minimo 3 caratteri!"
      },
      user_email: {
        required: "Campo richiesto",
        minlength: "La mail deve avere i campi corretti!"
      },
      user_pass: {
        required: "Campo richiesto",
        minlength: "Minimo 8 caratteri!"
      },
      user_pass_check: {
        required: "Campo richiesto",
        minlength: "Minimo 8 caratteri!"
      }
    },
    beforeSend: function (xhr) {
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    },
    submitHandler: function (form) {
      $.ajax({
        type: 'POST',
        url: '<base_url>/piattaforma-utenze/resources/library/registerUser.php',
        data: $('#register-form').serialize(),
        dataType: 'json',
        success: function (response) {
          var res = JSON.stringify(response);
          var obj = JSON.parse(res);
    
          window.location = '<base_url>/piattaforma-utenze';
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
