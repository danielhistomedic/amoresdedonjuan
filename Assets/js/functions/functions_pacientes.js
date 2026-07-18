/*==================================================================
[ DOMContentLoaded ]*/


document.addEventListener("DOMContentLoaded", function(event) {

    /*-------------------------------------------
    [ Form - Agregar evento submit, keypress al formulario de Nuevo Paciente Express ]*/
    if (document.getElementById('formPacienteInicio')) {
        var formPacienteInicio = document.getElementById('formPacienteInicio');
        formPacienteInicio.addEventListener("submit", function(event) { registroNuevoPacienteInicio(event) });
    }

    /*-------------------------------------------
    [ Form - Agregar plugin de validación de campos ]*/
    if (document.getElementById('formPacienteInicio')) {
        $('#formPacienteInicio').parsley();
    }


    /*-------------------------------------------
    [ Form - Agregar evento click para validar campos del formualrio desde un boton type="button" ]*/
    if (document.getElementById('btnActionForm_PacienteInicioConsulta')) {
        $('#btnActionForm_PacienteInicioConsulta').click(function() {
            $('#formPacienteInicio').parsley().whenValidate({
                // group: 'block-' + curIndex()
            }).done(function() {
                // navigateTo(curIndex() + 1);
                /*-------------------------------------------
                [ Si la validación es correcta procede al registro. ]*/
                registroNuevoPacienteConsultaInicio();
            });
        });
    }


});



/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    /*-------------------------------------------
    [ Funciones Init ]*/



}, false)



/*==================================================================
[ Nuevo Registro de Paciente Inicio ]*/

function registroNuevoPacienteInicio(e) {

    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();

    /*-------------------------------------------
    [ Ajax - Before Send Agregar Loading a Button ]*/
    var btnGuardar = document.getElementById('btnActionForm_PacienteInicio');
    agregarLoadingButtonGuardar(btnGuardar);

    /*-------------------------------------------
    [ Valida Inputs que no estén vacíos. ]*/
    var check = false;
    check = validaInputsForm();

    /*-------------------------------------------
    [ Valida Selects que no estén vacíos. ]*/
    check = validaSelectsForm();



    /*-------------------------------------------
      [ Verifica Resultado de Validaciones ]*/
    if (!check) {
        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + '¡Atención!',
            text: 'Debe llenar los campos marcados en rojo',
            textButton: 'Cerrar'
        }).then(function(result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return;
    }

    /*-------------------------------------------
    [ Ajax ]*/
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {

            var responseObj = JSON.parse(xhttp.responseText);

            if (responseObj.respuesta == "ok") {

                // -- Evalúa respuesta --
                if (responseObj.mostrar_mensaje == true) {

                    // -- Resetea el formulario --
                    resetForm(formPacienteInicio);

                    mensajeAlertaModal({
                        icon: 'success',
                        timer: responseObj.tiempo,
                        title: iconMensajeSuccess + '!OK¡',
                        text: responseObj.mensaje,
                        textButton: 'Cerrar'
                    }).then(function(result) {
                        if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                        if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
                    })
                }

            } else {

                if (responseObj.mostrar_mensaje == true) {

                    mensajeAlertaModal({
                        icon: 'error',
                        timer: responseObj.tiempo,
                        title: iconMensajeError + '¡Atención!',
                        text: responseObj.mensaje,
                        textButton: 'Cerrar'
                    }).then(function(result) {
                        if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                        if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
                    })

                }
            }

        }

    };
    var ajaxUrl = base_url + '/pacientes/setPaciente';
    var formData = new FormData(formPacienteInicio);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}