/*==================================================================
[ DOMContentLoaded ]*/
document.addEventListener("DOMContentLoaded", function(event) {

    /*-------------------------------------------
    [ Form - Agregar evento onsubmit ala todos los formularios de la pagina ]*/
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');

    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.add('was-validated');
            } else {
                setConfiguracion(event);
            }
        }, false);
    });

});

/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    getFunctionData('Configuracion', 'getConfiguracion', '', (responseObj) => {

        console.log(responseObj);

        if (responseObj.respuesta == "ok") {

            /** -- Cargar Datos recibidos -- */
            cargarDatosConfig(responseObj.data);

            /** -- Mensaje de Alerta -- */
            alerta_success(responseObj, "");

        } else {
            /** -- Mensaje de Alerta -- */
            alerta_error(responseObj, "");
        }

    });

}, false)


function cargarDatosConfig(data) {

    document.getElementById('inputEmailRemitente').value = data.email_remitente;
    document.getElementById('inputEmailContabilidad').value = data.email_destino_contabilidad;
    document.getElementById('inputSMTPHost').value = data.smtp_host;
    document.getElementById('inputSMTPUsuario').value = data.smtp_usuario;
    document.getElementById('inputSMTPPassword').value = data.smtp_password;
    document.getElementById('inputSMTPPuerto').value = data.smtp_puerto;

}


function setConfiguracion(event) {

    /*-------------------------------------------
         [ Evita la recarga de la pagina. ]*/
    event.preventDefault();

    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    var divLoading = document.getElementById('loading');
    divLoading.style.display = "flex";


    /*-------------------------------------------
    [ Ajax ]*/
    let form = document.getElementById("formConfig");
    postFunction(form, 'Configuracion', 'setConfiguracion', function(responseObj) {

        if (responseObj.respuesta == "ok") {

            /** -- Mensaje de Alerta -- */
            alerta_success(responseObj, divLoading);

        } else {

            /** -- Mensaje de Alerta -- */
            alerta_error(responseObj, divLoading);
        }

    });


}