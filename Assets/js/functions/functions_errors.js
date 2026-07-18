/*==================================================================
[ DOMContentLoaded ]*/


document.addEventListener("DOMContentLoaded", function(event) {

    /*-------------------------------------------
    [ Form - Agregar evento submit, keypress al formulario de Nuevo Paciente Express ]*/
    if (document.getElementById('btnActionForm_RegresarInicio')) {
        var btnRegresarInicio = document.getElementById('btnActionForm_RegresarInicio');
        btnRegresarInicio.addEventListener("click", function() { regresarInicio(this) });
    }

});


/*==================================================================
[ Nuevo Registro de Paciente Inicio ]*/

function regresarInicio(btnEl) {

    /*-------------------------------------------
    [ Agregar Loading a Button ]*/
    // var btnGuardar = document.getElementById('btnActionForm_RegresarInicio');
    // agregarLoadingButtonGuardar(btnEl);
    window.location = base_url + '/inicio';

}