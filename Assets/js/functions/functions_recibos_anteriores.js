/*==================================================================
[ Variables de Archivo ]*/

let tableRecibosAnteriores;
let tableRecibosAnterioresElement = "#tableRecibosAnteriores";
let configTableRecibosAnteriores = "";

let cantidad = 0;
let concepto = "MANTENIMIENTO ";

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function (event) {


    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Residente] */
    if (document.getElementById('formResidente')) {
        var formResidente = document.getElementById('formResidente');
        formResidente.addEventListener("submit", function (event) { setResidente(event) });
    }

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Recibo de Cobro]*/
    if (document.getElementById('formReciboAnterior')) {
        var formReciboAnterior = document.getElementById('formReciboAnterior');
        formReciboAnterior.addEventListener("submit", function (event) { setReciboAnterior(event) });
    }

    /*-------------------------------------------
    [ Form - Agregar evento input al campo Recibe ]*/
    if (document.getElementById('recibo_anterior_recibe')) {
        inputRecibe = document.getElementById("recibo_anterior_recibe");
        inputRecibe.addEventListener("input", getCambio);
    }

    if (document.getElementById('recibo_anterior_descuento')) {
        inputDescuento = document.getElementById("recibo_anterior_descuento");
        inputDescuento.addEventListener("input", calcularImporteReciboAnterior);
    }

    if (document.getElementById('recibo_anterior_dejaacuenta')) {
        inputDejaCuenta = document.getElementById("recibo_anterior_dejaacuenta");
        inputDejaCuenta.addEventListener("input", calcularImporteReciboAnterior);
    }


    /*==================================================================
    [ Botons de Accion ]*/

    if (document.getElementById('btnNuevoReciboAnterior')) {
        var btnNuevoReciboAnterior = document.getElementById('btnNuevoReciboAnterior');
        btnNuevoReciboAnterior.onclick = function () { nuevoReciboAnterior() };
    }

    if (document.getElementById('btnSeleccionarTodos_ReciboAnterior')) {
        var btnSeleccionarTodos_ReciboAnterior = document.getElementById('btnSeleccionarTodos_ReciboAnterior');
        btnSeleccionarTodos_ReciboAnterior.onclick = function () { seleccionarTodosReciboAnterior() };
    }

    /*-------------------------------------------
     [ Agregar evento click para regresar a listado  ]*/

    if (document.querySelector(".btnCancelar_ReciboAnterior")) {
        var btnCancelar_ReciboAnterior = document.querySelectorAll('.btnCancelar_ReciboAnterior');
        for (let index = 0; index < btnCancelar_ReciboAnterior.length; index++) {
            const element = btnCancelar_ReciboAnterior[index];
            element.onclick = function () { fntCancelarReturnListReciboAnterior() };
        }
    }


    /*==================================================================
    [ DataTable ]*/

    /*-------------------------------------------
    [ Agregar evento click a Colvis de Datatable para agregar icono para restaurar las columnas ]*/
    if (document.querySelector(".buttons-colvis")) {
        var btnElement = document.querySelector('.buttons-colvis');
        btnElement.onclick = function () {

            setTimeout(() => {
                if (document.querySelector("div.dt-button-collection .buttons-colvisRestore span i")) {
                    let colvis_icon = document.querySelector('div.dt-button-collection .buttons-colvisRestore span i');
                    colvis_icon.classList.remove("far");
                    colvis_icon.classList.add("fa-regular");
                }
            }, 500);

        };
    }


    // ** [Recibos Anteriores] **

    /*-------------------------------------------
    [ DataTable - Se ejecuta después de inicializar la tabla ]*/
    $(tableRecibosAnterioresElement).on('init.dt', function () {

        ReDesignButonExcel();

        $('.dataTables_wrapper select').select2({
            language: "es",
            minimumResultsForSearch: Infinity
        });

        let thead = document.querySelector(tableRecibosAnterioresElement + ' thead');
        thead.classList.remove("bg-secondary");
        thead.classList.add("bg-thead");

        //Valida si se activa el botón excel .
        validaPermisoExportar(menu);

    });


    /*-------------------------------------------
    [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
    $(tableRecibosAnterioresElement).on('draw.dt', function () {

    });

    /*-------------------------------------------
    [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
    $(tableRecibosAnterioresElement).on('click', 'tbody tr>td', function () {

    });

});

/*==================================================================
[ Window ]*/

window.addEventListener('load', function () {

    /*-------------------------------------------
    [ Activa el menu horizontal (prinicipal) correspondiente del sidemenu ]*/
    let pageUrl = base_url + "/administracion"
    fntActivarHorizontalMenu(pageUrl);

}, false)


/*==================================================================
[ Funciones de Manejo de Datos de Registro de Tags ]*/

function getCambio(importe) {

    importe = document.getElementById("recibo_anterior_importe").value;
    recibe = document.getElementById("recibo_anterior_recibe").value;
    cambio = recibe - importe;
    document.getElementById("recibo_anterior_cambio").value = cambio;

}

function seleccionarTodosReciboAnterior() {

    let selCheck = document.querySelectorAll(".selectgroup-input");

    cantidad = 0;

    for (let index = 0; index < selCheck.length; index++) {
        const element = selCheck[index];
        element.setAttribute('checked', 'checked');
        cantidad += 1;
    }

    calcularImporteReciboAnterior();

    cargarConcepto();

}


/*==================================================================
[ Nuevo Registro ]*/

function nuevoReciboAnterior() {

    /*-------------------------------------------
    [ Limpiar Form ]*/
    let formElement = document.getElementById('formReciboAnterior');
    document.getElementById("inputIdReciboAnterior").value = '';
    resetFormNoPasley(formElement);


    // Regresa conteo a cero de cantidad.
    cantidad = 0;
    document.getElementById("recibo_anterior_cantidad").value = 0;
    concepto = "MANTENIMIENTO ";
    document.getElementById("recibo_anterior_concepto").value = "";


    // 
    let list = document.getElementById('list_recibo_anterior');
    let editar = document.getElementById('crear_editar_recibo_anterior');
    let view = document.getElementById('view_recibo_anterior');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";


    //Asignar Titulo de Encabezado
    // document.getElementById("titulo-editar").innerHTML = "Registrar Nuevo Recibo.";
    // document.getElementById("titulo-editar-icon").innerHTML = '<i class="fa-regular fa-file-plus icon-size float-start text-secondary text-secondary-shadow me-2"></i>';

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_recibo_anterior');

    //Elemento que recibe la animación
    let eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);


    /*-------------------------------------------
    [ Carga datos default para el mes correspondiente ]*/
    fillDefaultsValues();

}

function fillDefaultsValues() {


    document.getElementById("recibo_anterior_cantidad").value = "0";
    document.getElementById("recibo_anterior_concepto").value = "0";
    document.getElementById("recibo_anterior_subtotal").value = "0";
    document.getElementById("recibo_anterior_descuento").value = "0";
    document.getElementById("recibo_anterior_dejaacuenta").value = "0";
    document.getElementById("recibo_anterior_importe").value = "0";
    document.getElementById("recibo_anterior_recibe").value = "0";
    document.getElementById("recibo_anterior_cambio").value = "0";
    document.getElementById("recibo_anterior_concepto").value = "";

}

function fntCancelarReturnListReciboAnterior() {

    let list = document.getElementById('list_recibo_anterior');
    let editar = document.getElementById('crear_editar_recibo_anterior');
    let view = document.getElementById('view_recibo_anterior');
    list.style.display = "block";
    editar.style.display = "none";
    view.style.display = "none";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.list_recibo_anterior');

    //Elemento que recibe la animación
    let eLDestino = list;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}


function getLastMonthCuenta(residente_id, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            console.log(dataObj);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/Cuentas/getLastMonthCuenta/' + residente_id;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}


function cargarMesesAdeudos(residente_id) {

    getMesesAdeudos(residente_id, function (data) {

        var dt = new Date();
        dt.setMonth(dt.getMonth() + 1);
        var meses_adeudo = document.getElementById("meses-adeudo");
        var addMesesAdeudo = "";

        for (let index = 0; index < data.length; index++) {

            var cuenta_id = data[index].id;
            var anio = data[index].anio;
            var mes_value = data[index].mes;
            var mes_descripcion = "";
            if (data[index].concepto_id == 2) {
                mes_descripcion = mesDescripcion(mes_value - 1).toUpperCase() + ' ' + anio;
            } else {
                mes_descripcion = "DEPOSITO INICIAL";
            }

            addMesesAdeudo += `<div class="form-group col-12 col-sm-3">
                                    <div class="selectgroup selectgroup-pills m-0 w-100">
                                        <label class="selectgroup-item w-100">
                                            <input type="checkbox" name="mes_pago_adeudos[]" value="${cuenta_id}" class="selectgroup-input input-conceptos" concepto="${mes_descripcion}" onclick="fntSelectCargo(this)">
                                                <span class="selectgroup-button selectgroup-button-danger">${mes_descripcion}</span>
                                        </label>
                                    </div>
                                </div>`;
        }
        meses_adeudo.innerHTML = addMesesAdeudo;

    });

}

function getMesesAdeudos(residente_id, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/Cuentas/getMesesAdeudos/' + residente_id;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}




function fntSelectCargo(btnElement) {

    if (btnElement.checked == true) {
        cantidad += 1;
    } else {
        cantidad -= 1;
    }

    calcularImporteReciboAnterior();

    cargarConcepto();

}

function cargarConcepto() {

    var conceptos = document.querySelectorAll(".input-conceptos");
    concepto = "MANTENIMIENTO ";

    for (let index = 0; index < conceptos.length; index++) {
        if (conceptos[index].checked == true) {
            if (concepto == "MANTENIMIENTO ") {
                concepto += conceptos[index].getAttribute('concepto');
            } else {
                concepto += ',  ' + conceptos[index].getAttribute('concepto');
            }

        }
    }

    let recibo_anterior_concepto = document.getElementById("recibo_anterior_concepto");
    recibo_anterior_concepto.value = concepto;



}

function calcularImporteReciboAnterior() {

    let recibo_anterior_cantidad = document.getElementById("recibo_anterior_cantidad");
    let recibo_anterior_subtotal = document.getElementById("recibo_anterior_subtotal");
    let recibo_anterior_descuento = document.getElementById("recibo_anterior_descuento");
    let recibo_anterior_dejaacuenta = document.getElementById("recibo_anterior_dejaacuenta");
    let recibo_anterior_importe = document.getElementById("recibo_anterior_importe");


    // Asigna el valor de cantidad total de elemntos seleccionados
    recibo_anterior_cantidad.value = cantidad;


    // Asigna el valor de subtotal de elemntos seleccionados
    let subtotal = cantidad * 200;
    recibo_anterior_subtotal.value = subtotal;


    //Asigna valores de entrada variables
    let descuento = 0;
    if (recibo_anterior_descuento.value == "") {
        descuento = 0;
    } else {
        descuento = parseFloat(recibo_anterior_descuento.value);
    }
    if (descuento > 0) {
        descuento = parseFloat(descuento);
        descuento = descuento / 100;
        descuento = subtotal * descuento;
    }
    subtotal = subtotal - descuento;

    let dejaacuenta = 0;
    if (recibo_anterior_dejaacuenta.value == "") {
        dejaacuenta = 0;
    } else {
        dejaacuenta = recibo_anterior_dejaacuenta.value;
    }
    dejaacuenta = parseFloat(dejaacuenta);


    //Asigna valor del Importe a pagar
    importe_sel = Math.round(subtotal + dejaacuenta);
    recibo_anterior_importe.value = importe_sel;


    //Asigana el valor del cambio
    getCambio(importe_sel);


}


/*==================================================================
[ Guardar Registro ]*/

function setReciboAnterior(e) {

    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnGuardarRecibo');
    agregarLoadingButtonGuardar(btnGuardar);


    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();



    /*-------------------------------------------
    [ Envía a Cero los campos varibales que dejen vacíos. ]*/
    if (document.getElementById("recibo_anterior_descuento") == "") {
        document.getElementById("recibo_anterior_descuento").value = "0";
    }
    if (document.getElementById("recibo_anterior_dejaacuenta") == "") {
        document.getElementById("recibo_anterior_dejaacuenta").value = "0";
    }


    /*-------------------------------------------
    [ Asigna valores default ]*/
    document.getElementById("recibo_anterior_cambio").value = "0";

    let importe_insert = document.getElementById("recibo_anterior_importe").value;
    let dejacuenta_insert = document.getElementById("recibo_anterior_dejaacuenta").value;
    let recibe_insert = importe_insert + dejacuenta_insert;
    document.getElementById("recibo_anterior_recibe").value = recibe_insert;



    /*-------------------------------------------
    [ Valida que seleccionen una opción de meses adelantados o adeudos ]*/
    if (document.getElementById("recibo_anterior_cantidad").value == "0") {
        mensajeAlertaModal({
            icon: 'error',
            timer: 3000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe seleccionar por lo menos un mes a pagar, ya sea adeudo o adelantado.',
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismiss == true) { removerClasesButtonGuardar(btnGuardar, loading); };
        });
        return;
    }

    /*-------------------------------------------
    [ Valida que la cantidad recibida sea la correcta (no menor al importe) ]*/
    if (document.getElementById("recibo_anterior_cambio").value < 0) {
        mensajeAlertaModal({
            icon: 'error',
            timer: 3000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'La cantidad recibida no puede ser menor al Total del Importe.',
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismiss == true) { removerClasesButtonGuardar(btnGuardar, loading); };
        });
        document.getElementById("recibo_anterior_recibe").value = "0";
        return;
    }

    /*-------------------------------------------
    [ Valida que se haya seleccionado un residente ]*/
    if (document.getElementById("recibo_residente_id").value == "") {
        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe seleccionar un residente.',
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismiss == true) { removerClasesButtonGuardar(btnGuardar, loading); };
        })
        return;
    }


    /*-------------------------------------------
    [ Valida Inputs que no estpen vacíos. ]*/
    var check = true;
    var input = document.querySelectorAll("input.inputForm100_recibo");

    for (let index = 0; index < input.length; index++) {
        const element = input[index];
        if (element.value == '') {
            validaInputs(element);
            check = false;
        }
    }

    if (!check) {

        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Todos los campos son obligatorios.',
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return false;
    }

    /*-------------------------------------------
    [ Verifica Resultado de Validaciones ]*/
    if (!check) {
        return false;
    }


    /*-------------------------------------------
    [ Ajax ]*/
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var responseText = xhttp.responseText;
            var jsonStartIndex = responseText.indexOf('{');
            if (jsonStartIndex !== -1) {
                responseText = responseText.substring(jsonStartIndex);
            }
            var responseObj = JSON.parse(responseText);

            if (responseObj.respuesta == "ok") {

                /* Vista Previa recibo en PDF */
                vistaRecibo(responseObj.recibo.id);

                /* Resetea el Form de registro para limpiar los datos */
                btnNuevoReciboAnterior.click();

                /* Reactiva el botón para guardar registro */
                removerClasesButtonGuardar(btnGuardar, loading);

                /* Carga la lista de Adeudos del Residente ]*/
                cargarMesesAdeudos(responseObj.recibo.residente_id);

                /* Retorna y actualiza Listado de Recibos */
                fntCancelarReturnListReciboAnterior();
                tableRecibosAnteriores.ajax.reload(function () { });

            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: 4000,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function (result) {
                    if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                    if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
                });
            }
        }
    };

    var ajaxUrl = base_url + '/RecibosAnteriores/setReciboAnterior';
    var formData = new FormData(formReciboAnterior);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}



/*==================================================================
[ Vista Registro ]*/

function fntViewRecibo(btnElement) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-eye')

    let list = document.getElementById('list_recibo_anterior');
    let editar = document.getElementById('crear_editar_recibo_anterior');
    let view = document.getElementById('view_recibo_anterior');
    list.style.display = "none";
    editar.style.display = "none";
    view.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.view_recibo_anterior');

    //Elemento que recibe la animación
    let eLDestino = view;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);


    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");

    /*-------------------------------------------
     [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            let objDataResponse = JSON.parse(xhttp.responseText);
            if (objDataResponse.respuesta == "ok") {
                cargarDatosRecibo(objDataResponse);
                restablecerButtonOpcionesDataTable(btnElement, 'fa-eye');
            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function (result) {
                    if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-eye'); };
                    if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-eye'); }
                });
            }
        }
    };
    let ajaxUrl = base_url + '/RecibosAnteriores/getReciboAnterior/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

function cargarDatosRecibo(modelDataObj) {

    document.getElementById("inputIdReciboAnterior").value = modelDataObj.data.id;

    /*-------------------------------------------
    [ Llena lso datos del form view  ]*/
    document.getElementById("inputFolio_RA_read").innerHTML = modelDataObj.data.folio;
    document.getElementById("inputConcepto_RA_read").innerHTML = modelDataObj.data.concepto;
    // document.getElementById("inputImporte_RA_read").innerHTML = modelDataObj.data.importe;

    let estatus =
        modelDataObj.data.estatus == 0 ?
            '<span class = "badge badge-success">Vigente</span>' :
            '<span class = "badge badge-danger">Cancelado</span>';
    document.getElementById("estatus_read").innerHTML = estatus;

    document.getElementById("fechaRegistro_read").innerHTML = modelDataObj.data.created_at;
    document.getElementById("usuarioRegistro_read").innerHTML = modelDataObj.data.usuario;

    document.getElementById("fechaCancelacion_read").innerHTML = modelDataObj.data.fecha_cancela;
    document.getElementById("usuarioCancela_read").innerHTML = modelDataObj.data.usuario_cancela;
    document.getElementById("motivoCancelacion_read").innerHTML = modelDataObj.data.motivo_cancela;

}

function fntReimprimirRecibo(btnElement) {

    /*-------------------------------------------
    [ Obtiene id de registro ]*/
    var recibo_id = btnElement.getAttribute("data-id");

    url = base_url + '/RecibosAnteriores/generarComprobanteAnterior/' + recibo_id;
    window.open(url, "Recibo de Cobro", "fullscreen=yes");

}

function vistaRecibo(recibo_id) {

    url = base_url + '/RecibosAnteriores/generarComprobanteAnterior/' + recibo_id;
    window.open(url, "Recibo de Cobro", "fullscreen=yes");

}

function loadResidente(data) {


    // *** Mostrar Form de Inicio. ***
    fntCancelarReturnListReciboAnterior();


    // *** Limpiar Buscador. ***
    document.getElementById("search_residente").value = "";


    // *** Asignar Id de Residente. ***
    document.getElementById("residente_id").value = data.id;
    document.getElementById("recibo_residente_id").value = data.id;


    // *** Asigna los datos del Residente en el formulario***
    document.getElementById("res_nombre").value = data.nombre;
    document.getElementById("res_calle").value = data.calle;
    document.getElementById("res_numero").value = data.numero;
    document.getElementById("res_email").value = data.email;
    document.getElementById("res_telefono").value = data.telefono;

    /*-------------------------------------------
    [ DataTable Inicializa - Recibos asociados al residente ]*/
    setConfigTableRecibosAnteriores('RecibosAnteriores', 'getRecibosAnteriores/' + data.id);
    tableRecibosAnteriores = $(tableRecibosAnterioresElement).DataTable(configTableRecibosAnteriores);

    /*-------------------------------------------
    [ Carga la lista de Adeudos del Residente ]*/
    cargarMesesAdeudos(data.id);


    //=======

    // // *** Mostrar Form de Inicio. ***
    // fntCancelarReturnListReciboAnterior();


    // // *** Limpiar Buscador. ***
    // document.getElementById("search_residente").value = "";

    // // *** Asigna los datos del Residente en el formulario***
    // document.getElementById("residente-nombre").innerHTML = data.nombre;
    // document.getElementById("residente-domicilio").innerHTML = data.calle + ' ' + data.numero;

    // if (data.email == null) {
    //     document.getElementById("residente-email").innerHTML = 'No Registrado';
    // } else {
    //     document.getElementById("residente-email").innerHTML = data.email;
    // }

    // if (data.telefono == null) {
    //     document.getElementById("residente-telefono").innerHTML = 'No Registrado';
    // } else {
    //     document.getElementById("residente-telefono").innerHTML = data.telefono;
    // }

    // // *** Asignar Id de Residente. ***
    // document.getElementById("recibo_residente_id").value = data.id;

    // /*-------------------------------------------
    // [ DataTable Inicializa - Recibos asociados al residente ]*/
    // setConfigTableRecibosAnteriores('RecibosAnteriores', 'getRecibosAnteriores/' + data.id);
    // tableRecibosAnteriores = $(tableRecibosAnterioresElement).DataTable(configTableRecibosAnteriores);

    // /*-------------------------------------------
    // [ Carga la lista de Adeudos del Residente ]*/
    // cargarMesesAdeudos(data.id);

}


/*==================================================================
[ Eliminar Registro ]*/

// function fntDeleteRecibo(btnElement) {

//     /*-------------------------------------------
//      [ Obtiene id de registro ]*/
//     var idRegistro = btnElement.getAttribute("data-id");

//     /*-------------------------------------------
//     [ Deshabilita elemento para prevenir doble registro ]*/
//     agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-trash-can')


//     mensajeAlertaModal({
//         icon: 'warning',
//         title: iconMensajeWarning + ' ¡Advertencia!',
//         text: "¿Está seguro de cancelar el recibo seleccionado?",
//         textButton: 'Sí, Cancelar Recibo',
//         textCancelButton: 'No, Cerrar'
//     }).then(function (result) {
//         if (result.dismiss == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-trash-can'); };
//         if (result.si == true) { deleteRecibo(idRegistro) };
//     });

// }

// function deleteRecibo(recibo_id) {

//     /*-------------------------------------------
//     [ Ajax ]*/
//     let xhttp = new XMLHttpRequest();
//     xhttp.onreadystatechange = function () {
//         if (this.readyState == 4 && this.status == 200) {

//             let responseObj = JSON.parse(xhttp.responseText);

//             if (responseObj.respuesta == "ok") {

//                 /* Resetea el Form de registro para limpiar los datos */
//                 btnNuevoReciboAnterior.click();

//                 let residente_id = document.getElementById("recibo_residente_id").value;
//                 /* Carga la lista de Adeudos del Residente ]*/
//                 cargarMesesAdeudos(residente_id);

//                 /* Retorna y actualiza Listado de Recibos */
//                 fntCancelarReturnListReciboAnterior();
//                 tableRecibosAnteriores.ajax.reload(function () { });

//                 mensajeAlertaModal({
//                     icon: 'success',
//                     timer: responseObj.tiempo,
//                     title: iconMensajeSuccess + ' ¡Atención!',
//                     text: responseObj.mensaje,
//                     textButton: 'Cerrar'
//                 });


//             } else {
//                 mensajeAlertaModal({
//                     icon: 'error',
//                     timer: responseObj.tiempo,
//                     title: iconMensajeError + ' ¡Atención!',
//                     text: responseObj.mensaje,
//                     textButton: 'Cerrar'
//                 }).then(function (result) {
//                     if (result.dismiss == true) {
//                         restablecerButtonOpcionesDataTable(btnElement, 'fa-trash-can');
//                     };
//                 });
//             }
//         }
//     };

//     let ajaxUrl = base_url + '/RecibosAnteriores/setCancelarReciboAnterior/';
//     let strData = "";
//     strData += "id=" + recibo_id;
//     strData += "&estatus=1";
//     strData += "&residente_id=" + document.getElementById("recibo_residente_id").value;
//     xhttp.open("POST", ajaxUrl, true);
//     xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
//     xhttp.send(strData);

// }


/*==================================================================
[ Actualizar Datos Residente ]*/

function setResidente(e) {

    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnActualizarResidente');
    agregarLoadingButtonGuardar(btnGuardar);


    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();


    /*-------------------------------------------
    [ Valida Inputs que no estpen vacíos. ]*/
    var check = true;
    var input = document.querySelectorAll("input.inputForm100_residente");

    for (let index = 0; index < input.length; index++) {
        const element = input[index];
        if (element.value == '') {
            validaInputs(element);
            check = false;
        }
    }
    if (!check) {

        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Todos los campos son obligatorios.',
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return false;
    }


    /*-------------------------------------------
    [ Valida Selects que no estpen vacíos. ]*/
    // if (!check) {
    //     mensajeAlertaModal({
    //         icon: 'error',
    //         timer: 4000,
    //         title: iconMensajeError + ' ¡Atención!',
    //         text: 'Debe llenar los campos requeridos.',
    //         textButton: 'Cerrar'
    //     }).then(function(result) {
    //         if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
    //         if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
    //     })

    //     return false;
    // }


    /*-------------------------------------------
    [ Verifica Resultado de Validaciones ]*/
    if (!check) {
        return false;
    }


    /*-------------------------------------------
    [ Ajax ]*/
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var responseObj = JSON.parse(xhttp.responseText);
            if (responseObj.respuesta == "ok") {

                mensajeAlertaModal({
                    icon: 'success',
                    timer: 4000,
                    title: iconMensajeSuccess + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function (result) {
                    if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                    if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
                });

                /* Reactiva el botón para actualziar datos de residente */
                removerClasesButtonGuardar(btnGuardar, loading);

            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: 4000,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function (result) {
                    if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                    if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
                });
            }
        }
    };
    var ajaxUrl = base_url + '/Residentes/setResidente';
    var formData = new FormData(formResidente);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}


/*==================================================================
[ DataTable ]*/

function setConfigTableRecibosAnteriores(controlador, metodo) {

    configTableRecibosAnteriores = {
        "aProcessing": true,
        "aServerSide": true,
        'destroy': true,
        'responsive': false,
        "autoWidth": false,
        "iDisplayLength": 10,
        "order": [
            // [0, "desc"]
        ],
        "select": true,
        'ajax': {
            "url": " " + base_url + "/" + controlador + "/" + metodo + "",
            'dataSrc': ''
        },
        'dom': 'Blfrtip',
        'buttons': [{
            extend: 'excelHtml5',
            autoFilter: true,
            sheetName: 'Lista de Recibos de Cobro Anteriores',
            extend: 'excel',
            messageTop: "",
            title: 'Amores - Lista de Recibos de Cobro Anteriores',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'colvis',
            columnText: function (dt, idx, title) {
                return '<i class="fa-regular fa-angle-right"></i>&nbsp;&nbsp;' + title;
            },
            postfixButtons: ['colvisRestore']
        }
        ],
        'columnDefs': [
            // { 'width': '80%', 'targets': 1 },
            // { 'width': '4%', 'targets': 11 },
            // { 'width': '6%', 'targets': '_all' }
        ],
        "columns": [
            { "data": "folio" },
            { "data": "created_at" },
            { "data": "concepto" },
            { "data": "folio_anterior" },
            { "data": "archivo" },
            { "data": "estatus" },
            { "data": "options" }
        ],
        'language': idioma_espanol

    };

}

/*==================================================================
[ DataTable Filtros ]*/