/*==================================================================
[ Variables de Archivo ]*/

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function (event) {


    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Residente] */
    if (document.getElementById('formTransferirRecibo')) {
        var formTransferirRecibo = document.getElementById('formTransferirRecibo');
        formTransferirRecibo.addEventListener("submit", function (event) { setTransferir(event) });
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
        document.getElementById("recibo_origen").focus();
    }, 550);

}, false)



/*==================================================================
[ Guardar Registro ]*/

function setTransferir(event) {

    /*-------------------------------------------
     [ Evita la recarga de la pagina. ]*/
    event.preventDefault();


    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    var divLoading = document.getElementById('loading-resumen');
    divLoading.style.display = "flex";


    /*-------------------------------------------
    [ Ajax ]*/

    postFunction(formTransferirRecibo, 'Recibos', 'setTransferirRecibo', function (responseObj) {

        if (responseObj.respuesta == "ok") {

            /** -- Reset Form -- */
            resetFormNoPasley(formTransferirRecibo);
            document.getElementById('residente_id').value = "";
            document.getElementById('residente-nombre').innerHTML = "---";
            document.getElementById('residente-calle').innerHTML = "---";
            document.getElementById('residente-numero').innerHTML = "---";
            document.getElementById('residente-telefono').innerHTML = "---";
            document.getElementById('residente-email').innerHTML = "---";

            /** -- Mensaje de Alerta -- */
            alerta_success(responseObj, divLoading);

            /** -- Reimprime Recibo Transferido -- */
            vistaRecibo(responseObj.data.recibo_id);

        } else {

            /** -- Mensaje de Alerta -- */
            alerta_error(responseObj, divLoading);
        }

    });



}


function vistaRecibo(recibo_id) {
    url = base_url + '/recibos/generarComprobante/' + recibo_id;
    window.open(url, "Recibo de Cobro", "fullscreen=yes");
}

function loadResidente(data) {

    // *** Limpiar Buscador. ***
    document.getElementById("search_residente").value = "";


    // *** Asignar Id de Residente. ***
    document.getElementById("residente_id").value = data.id;


    // *** Asigna los datos del Residente en el formulario***
    document.getElementById("residente-nombre").innerHTML = data.nombre;
    document.getElementById("residente-calle").innerHTML = data.calle;
    document.getElementById("residente-numero").innerHTML = data.numero;
    document.getElementById("residente-email").innerHTML = data.email;
    document.getElementById("residente-telefono").innerHTML = data.telefono;

}