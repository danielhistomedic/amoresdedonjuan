/*==================================================================
[ Variables de Archivo ]*/

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function (event) {


    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Residente] */
    if (document.getElementById('formCancelarRecibo')) {
        var formCancelarRecibo = document.getElementById('formCancelarRecibo');
        formCancelarRecibo.addEventListener("submit", function (event) { setCancelar(event) });
    }


});

/*==================================================================
[ Window ]*/

window.addEventListener('load', function () {

    /*-------------------------------------------
    [ Activa el menu horizontal (prinicipal) correspondiente del sidemenu ]*/
    let pageUrl = base_url + "/administracion"
    fntActivarHorizontalMenu(pageUrl);


    //focus campo de incio
    setTimeout(() => {
        document.getElementById("motivo_cancela").focus();
    }, 550);

}, false)



/*==================================================================
[ Guardar Registro ]*/

function setCancelar(event) {

    /*-------------------------------------------
     [ Evita la recarga de la pagina. ]*/
    event.preventDefault();


    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    var divLoading = document.getElementById('loading-resumen');
    divLoading.style.display = "flex";


    /*-------------------------------------------
    [ Ajax ]*/

    postFunction(formCancelarRecibo, 'Recibos', 'setCancelarRecibo', function (responseObj) {

        if (responseObj.respuesta == "ok") {

            /** -- Reset Form -- */
            // resetFormNoPasley(formCancelarRecibo);
            // document.getElementById('residente_id').value = "";
            // document.getElementById('residente-nombre').innerHTML = "---";
            // document.getElementById('residente-calle').innerHTML = "---";
            // document.getElementById('residente-numero').innerHTML = "---";
            // document.getElementById('residente-telefono').innerHTML = "---";
            // document.getElementById('residente-email').innerHTML = "---";

            /** -- Mensaje de Alerta -- */
            alerta_success(responseObj, divLoading);

            /** -- Redirecciona a Recibos -- */
            setTimeout(() => {
                window.location.href = base_url + '/recibos';
            }, responseObj.tiempo);

        } else {

            /** -- Mensaje de Alerta -- */
            alerta_error(responseObj, divLoading);
        }

    });

}


// function loadResidente(data) {

//     // *** Limpiar Buscador. ***
//     document.getElementById("search_residente").value = "";


//     // *** Asignar Id de Residente. ***
//     document.getElementById("residente_id").value = data.id;


//     // *** Asigna los datos del Residente en el formulario***
//     document.getElementById("residente-nombre").innerHTML = data.nombre;
//     document.getElementById("residente-calle").innerHTML = data.calle;
//     document.getElementById("residente-numero").innerHTML = data.numero;
//     document.getElementById("residente-email").innerHTML = data.email;
//     document.getElementById("residente-telefono").innerHTML = data.telefono;

// }