$(document).ready(function () {
  $("form[name='server-cyb']").validate({

    /* Rules for username and password */
    rules: {
      nome_safe: "required",
      nome_server: "required",
      nome_utenza: "required",
      ticket: "required"
    },

    // Messaggi relativi alle precondizioni
    messages: {
      nome_safe: "Nome safe richiesto",
      nome_server: "IP Server richiesto",
      nome_utenza: "Utenza richiesta",
      ticket: "Ticket richiesto"
    },
    // Check tipo di action
    beforeSend: function (xhr) {
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
    },
    submitHandler: function (form) {
      $.ajax({
        type: "POST",
        url: '<base_url>/piattaforma-utenze/resources/library/newCybAs.php',
        data: $('#server-cyb-form').serialize(),
        dataType: 'json',
        success: function (response) {
          var res = JSON.stringify(response);
          var obj = JSON.parse(res);

          if (obj.code == 200) {
            // window.location = '<base_url>/piattaforma-utenze/public/utenza/as.php';
          } else {
            toast (obj.message);
          }
        },
        error: function (jqXHR, exception) {
          var errorMessage = jqXHR.status + ': ' + jqXHR.statusText;
          alert('Error - ' + errorMessage);
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
