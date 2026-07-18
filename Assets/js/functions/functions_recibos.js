/*==================================================================
[ Variables de Archivo ]*/

let tableRecibos;
let tableRecibosElement = "#tableRecibos";
let tableRecibosElementJS = "tableRecibos";
let configTableRecibos = "";
let saldo_disponible = 0;

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
    if (document.getElementById('formRecibo')) {
        var formRecibo = document.getElementById('formRecibo');
        formRecibo.addEventListener("submit", function (event) { setRecibo(event) });
    }

    /*-------------------------------------------
    [ Form - Agregar evento input al campo Recibe ]*/
    if (document.getElementById('recibo_recibe')) {
        inputRecibe = document.getElementById("recibo_recibe");
        inputRecibe.addEventListener("input", getCambio);
    }

    if (document.getElementById('recibo_descuento')) {
        inputDescuento = document.getElementById("recibo_descuento");
        inputDescuento.addEventListener("input", calcularDescuento);
    }

    if (document.getElementById('recibo_dejaacuenta')) {
        inputDejaCuenta = document.getElementById("recibo_dejaacuenta");
        inputDejaCuenta.addEventListener("input", calcularImporte);
    }


    /*==================================================================
    [ CheckBox ]*/
    let inputcheck_usarsaldo = document.getElementById("mostrar-saldo-disponible");
    inputcheck_usarsaldo.addEventListener('change', function (e) {
        calcularImporte();
    });

    /*==================================================================
    [ Botons de Accion ]*/

    if (document.getElementById('btnNuevoRecibo')) {
        var btnNuevoRecibo = document.getElementById('btnNuevoRecibo');
        btnNuevoRecibo.onclick = function () { nuevoRecibo() };
    }

    if (document.getElementById('btnActivarPortalResidente')) {
        var btnActivarPortalResidente = document.getElementById('btnActivarPortalResidente');
        btnActivarPortalResidente.onclick = function () { setPortalResidente() };
    }

    if (document.getElementById('btnDesactivarPortalResidente')) {
        var btnDesactivarPortalResidente = document.getElementById('btnDesactivarPortalResidente');
        btnDesactivarPortalResidente.onclick = function () { desactivarPortalResidente() };
    }

    if (document.getElementById('btnRegenerarUsuarioPortal')) {
        var btnRegenerarUsuarioPortal = document.getElementById('btnRegenerarUsuarioPortal');
        btnRegenerarUsuarioPortal.onclick = function () { regenerarPortalResidente() };
    }




    /*-------------------------------------------
     [ Agregar evento click para regresar a listado  ]*/

    if (document.querySelector(".btnCancelar_Recibo")) {
        var btnCancelar_Recibo = document.querySelectorAll('.btnCancelar_Recibo');
        for (let index = 0; index < btnCancelar_Recibo.length; index++) {
            const element = btnCancelar_Recibo[index];
            element.onclick = function () { fntCancelarReturnListRecibo() };
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


    // ** [Recibos] **

    /*-------------------------------------------
    [ DataTable - Se ejecuta después de inicializar la tabla ]*/
    $(tableRecibosElement).on('init.dt', function () {

        ReDesignButonExcel();

        $('.dataTables_wrapper select').select2({
            language: "es",
            minimumResultsForSearch: Infinity
        });

        let thead = document.querySelector(tableRecibosElement + ' thead');
        thead.classList.remove("bg-secondary");
        thead.classList.add("bg-thead");

        //Valida si se activa el botón excel .
        validaPermisoExportar(menu);

    });


    /*-------------------------------------------
    [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
    $(tableRecibosElement).on('draw.dt', function () {

    });

    /*-------------------------------------------
    [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
    $(tableRecibosElement).on('click', 'tbody tr>td', function () {

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

    importe = document.getElementById("recibo_importe").value;
    recibe = document.getElementById("recibo_recibe").value;
    cambio = recibe - importe;
    document.getElementById("recibo_cambio").value = cambio;

}

/*==================================================================
[ Nuevo Registro ]*/

function nuevoRecibo() {

    /*-------------------------------------------
    [ Limpiar Form ]*/
    let formElement = document.getElementById('formRecibo');
    document.getElementById("inputIdRecibo").value = '';
    resetFormNoPasley(formElement);

    // Regresa conteo a cero de cantidad.
    cantidad = 0;
    document.getElementById("recibo_cantidad").value = 0;
    concepto = "MANTENIMIENTO ";
    document.getElementById("recibo_concepto").value = "";


    // 
    let list = document.getElementById('list_recibo');
    let editar = document.getElementById('crear_editar_recibo');
    let view = document.getElementById('view_recibo');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";


    //Asignar Titulo de Encabezado
    // document.getElementById("titulo-editar").innerHTML = "Registrar Nuevo Recibo.";
    // document.getElementById("titulo-editar-icon").innerHTML = '<i class="fa-regular fa-file-plus icon-size float-start text-secondary text-secondary-shadow me-2"></i>';

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_recibo');

    //Elemento que recibe la animación
    let eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);


    /*-------------------------------------------
    [ Carga datos default para el mes correspondiente ]*/
    fillDefaultsValues();

}

function fillDefaultsValues() {


    document.getElementById("recibo_cantidad").value = "0";
    document.getElementById("recibo_concepto").value = "0";
    document.getElementById("recibo_subtotal").value = "0";
    document.getElementById("recibo_descuento").value = "0";
    document.getElementById("recibo_dejaacuenta").value = "0";
    document.getElementById("recibo_importe").value = "0";
    document.getElementById("recibo_recibe").value = "0";
    document.getElementById("recibo_cambio").value = "0";
    document.getElementById("recibo_concepto").value = "";

    if (saldo_disponible > 0) {

        document.getElementById('mostrar-saldo-disponible').style.display = 'block';
        document.getElementById('mostrar-saldo-disponible').innerHTML =
            `<div class="row">

                <div class="col-12 col-sm-4">
                    <label for="recibo_saldo_a_cuenta_disponible" class="form-label text-success">Saldo a Cuenta Disponible: </label>
                    <input type="number" class="form-control" value="${saldo_disponible}" name="recibo_saldo_a_cuenta_disponible" id="recibo_saldo_a_cuenta_disponible" placeholder="Ingrese saldo a cuenta disponible" readonly requiered>
                </div>
               
                <div class="col-12 col-sm-8">
                    <label for="recibo_saldo_a_cuenta_disponible" class="form-label text-success">Seleccione: </label>
                    <label class="custom-switch" style="margin-top: 3px; cursor: pointer;">
                        <input type="checkbox" name="saldo_disponible_usar" id="saldo_disponible_usar" class="custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">Usar Saldo Disponible</span>
                    </label>
                </div>

            </div>`;

    } else {
        document.getElementById('mostrar-saldo-disponible').innerHTML =
            `<input type="hidden" value="${saldo_disponible}" name="recibo_saldo_a_cuenta_disponible" id="recibo_saldo_a_cuenta_disponible">`;
        document.getElementById('mostrar-saldo-disponible').style.display = 'none';
    }


}

function fntCancelarReturnListRecibo() {

    let list = document.getElementById('list_recibo');
    let editar = document.getElementById('crear_editar_recibo');
    let view = document.getElementById('view_recibo');
    list.style.display = "block";
    editar.style.display = "none";
    view.style.display = "none";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.list_recibo');

    //Elemento que recibe la animación
    let eLDestino = list;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}

function cargarMesesAdelantados(residente_id) {

    getLastMonthCuenta(residente_id, function (data) {

        let anio_ini = data.anio;
        let mes_ini = data.mes;

        let fecha_ini = anio_ini + "-" + fillLeft(mes_ini, 2) + '-01 00:00:00';

        let meses_adelanto = document.getElementById("meses-adelanto");
        let addMesesAdelanto = "";

        for (let index = 1; index < 13; index++) {

            let dt = new Date(fecha_ini);
            dt.setMonth(dt.getMonth() + index);
            let anio = dt.getFullYear();
            let mes = fillLeft(dt.getMonth() + 1, 2);
            // let mes_descripcion = mesDescripcion(dt.getMonth()).toUpperCase();

            let valor = "";
            valor = anio + '-' + mes;
            let mes_descripcion = mesDescripcion(mes - 1).toUpperCase() + ' ' + anio;

            addMesesAdelanto += `<div class="form-group col-12 col-sm-3">
                                                <div class="selectgroup selectgroup-pills m-0 w-100">
                                                    <label class="selectgroup-item w-100">
                                                        <input type="checkbox" name="mes_pago_adelantados[]" value="${valor}" class="selectgroup-input input-conceptos" concepto="${mes_descripcion}" onclick="fntSelectCargo(this)" >
                                                        <span class="selectgroup-button selectgroup-button-warning">${mes_descripcion}</span>
                                                    </label>
                                                </div>
                                            </div>`;

        }

        meses_adelanto.innerHTML = addMesesAdelanto;

    });

}

function getLastMonthCuenta(residente_id, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
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

function cargarMesesPagados(residente_id) {

    getMesesPagados(residente_id, function (data) {


        var meses_pagados = document.getElementById("meses-pagados");
        var addMesesPagados = "";
        let last_year = "";
        let last_month = "";


        for (let index = 0; index < data.length; index++) {

            var anio = data[index].anio;
            var mes_value = data[index].mes;
            var mes = fillLeft(mes_value, 2);

            last_year = anio;
            last_month = mes;
            var mes_descripcion = "";

            if (data[index].concepto_id == 2) {
                valor = anio + '-' + mes;
                mes_descripcion = mesDescripcion(mes_value - 1).toUpperCase() + ' ' + anio;
            } else {
                valor = '0';
                mes_descripcion = "DEPOSITO INICIAL";
            }

            var recibo_id = data[index].recibo_id;
            var folio_recibo = data[index].folio;

            addMesesPagados += `<div class="form-group col-12 col-sm-3">
                                    <div class="selectgroup selectgroup-pills m-0 w-100">
                                        <label class="selectgroup-item w-100">
                                            <span class="selectgroup-button selectgroup-button-success d-flex flex-column">
                                                <span>${mes_descripcion}</span>
                                                <a class="recibos-link-pagados fs-11" href="${base_url}/recibos/generarComprobante/${recibo_id}" target="_blank">Recibo Folio: ${folio_recibo}</a>
                                            </span>
                                        </label>
                                    </div>
                                </div>`;
        }
        meses_pagados.innerHTML = addMesesPagados;


    });

}

function getMesesPagados(residente_id, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/Cuentas/getMesesPagados/' + residente_id;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}

let cantidad = 0;
let concepto = "MANTENIMIENTO ";
let subtotal = 0;
let renta = 0;

function fntSelectCargo(btnElement) {

    if (btnElement.checked == true) {
        cantidad += 1;
    } else {
        cantidad -= 1;
    }

    var año = 2024;
    concepto = btnElement.getAttribute("concepto");
    if (concepto == "ENERO 2025") {
        renta = 170;
    } else {
        año = getNumbersInString(concepto);
        if (año < 2025) {
            renta = 170;
        } else {
            renta = 200;
        }
    }

    if (btnElement.checked == true) {
        renta = renta * 1;
    } else {
        renta = renta * -1;
    }

    let recibo_subtotal = document.getElementById("recibo_subtotal").value;
    if (recibo_subtotal == '') {
        subtotal = 0;
    } else {
        subtotal = parseFloat(recibo_subtotal);
    }
    calcularSubtotal();

    cargarConcepto();

}

function getNumbersInString(string) {
    var tmp = string.split("");
    var map = tmp.map(function (current) {
        if (!isNaN(parseInt(current))) {
            return current;
        }
    });

    var numbers = map.filter(function (value) {
        return value != undefined;
    });

    return numbers.join("");
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

    let recibo_concepto = document.getElementById("recibo_concepto");
    recibo_concepto.value = concepto;

}

function calcularSubtotal() {

    let recibo_cantidad = document.getElementById("recibo_cantidad");
    let recibo_subtotal = document.getElementById("recibo_subtotal");
    // let recibo_descuento = document.getElementById("recibo_descuento");
    // let recibo_dejaacuenta = document.getElementById("recibo_dejaacuenta");
    // let recibo_importe = document.getElementById("recibo_importe");


    // Asigna el valor de cantidad total de elemntos seleccionados
    recibo_cantidad.value = cantidad;


    // Asigna el valor de subtotal de elemntos seleccionados
    recibo_subtotal.value = subtotal + renta;

    calcularDescuento();

    calcularImporte();

}

let descuento = 0;

function calcularDescuento() {

    let recibo_subtotal = document.getElementById("recibo_subtotal");
    let recibo_descuento = document.getElementById("recibo_descuento");

    let subtotal_valor = recibo_subtotal.value;
    subtotal_valor = parseFloat(subtotal_valor);

    //Asigna valores de entrada variables
    if (recibo_descuento.value == "") {
        descuento = 0;
    } else {
        descuento = parseFloat(recibo_descuento.value);
    }
    if (descuento > 0) {
        descuento = parseFloat(descuento);
        descuento = descuento / 100;
        descuento = subtotal_valor * descuento;
    }

    calcularImporte();

}


function calcularImporte() {

    let recibo_dejaacuenta = document.getElementById("recibo_dejaacuenta");
    let recibo_subtotal = document.getElementById("recibo_subtotal");
    let recibo_importe = document.getElementById("recibo_importe");

    let saldo_a_cuenta = 0;
    if (document.getElementById("saldo_disponible_usar")) {
        let inputcheck_usarsaldo = document.getElementById("saldo_disponible_usar");
        if (inputcheck_usarsaldo.checked) {
            saldo_a_cuenta = document.getElementById("recibo_saldo_a_cuenta_disponible").value;
        } else {
            saldo_a_cuenta = 0
        }
    }

    let subtotal_valor = recibo_subtotal.value;
    subtotal_valor = parseFloat(subtotal_valor);

    let dejaacuenta = 0;
    if (recibo_dejaacuenta.value == "") {
        dejaacuenta = 0;
    } else {
        dejaacuenta = recibo_dejaacuenta.value;
    }
    dejaacuenta = parseFloat(dejaacuenta);

    //Asigna valor del Importe a pagar
    importe_sel = Math.round(subtotal_valor + dejaacuenta - saldo_a_cuenta - descuento);
    console.log(importe_sel);
    recibo_importe.value = importe_sel;

    //Asigana el valor del cambio
    getCambio(importe_sel);

}


/*==================================================================
[ Guardar Registro ]*/

function setRecibo(e) {


    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnGuardarRecibo');
    agregarLoadingButtonGuardar(btnGuardar);


    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();


    /*-------------------------------------------
    [ Envía a Cero los campos varibales que dejen vacíos. ]*/
    if (document.getElementById("recibo_descuento") == "") {
        document.getElementById("recibo_descuento").value = "0";
    }
    if (document.getElementById("recibo_dejaacuenta") == "") {
        document.getElementById("recibo_dejaacuenta").value = "0";
    }


    /*-------------------------------------------
    [ Valida que seleccionen una opción de meses adelantados o adeudos ]*/
    if (document.getElementById("recibo_cantidad").value == "0") {
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
    [ Valida que el total del importe sea mayor a cero. ) ]*/
    if (document.getElementById("recibo_importe").value <= 0) {
        mensajeAlertaModal({
            icon: 'error',
            timer: 3000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'El importe del total debe ser mayor a cero.',
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismiss == true) { removerClasesButtonGuardar(btnGuardar, loading); };
        });
        document.getElementById("recibo_recibe").value = "0";
        return;
    }


    /*-------------------------------------------
    [ Valida que la cantidad recibida sea la correcta (no menor al importe) ]*/
    if (document.getElementById("recibo_cambio").value < 0) {
        mensajeAlertaModal({
            icon: 'error',
            timer: 3000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'La cantidad recibida no puede ser menor al Total del Importe.',
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismiss == true) { removerClasesButtonGuardar(btnGuardar, loading); };
        });
        document.getElementById("recibo_recibe").value = "0";
        return;
    }

    /*-------------------------------------------
    [ Valida que se haya seleccionado un residente ]*/
    if (document.getElementById("residente_id").value == "") {
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

            var responseObj = JSON.parse(xhttp.responseText);
            removerClasesButtonGuardar(btnGuardar, loading);

            if (responseObj.respuesta == "ok") {

                /* Vista Previa recibo en PDF */
                vistaRecibo(responseObj.recibo.id);

                /* Reactiva el botón para guardar registro */
                removerClasesButtonGuardar(btnGuardar, loading);

                // /* Resetea el Form de registro para limpiar los datos */
                // btnNuevoRecibo.click();

                // /* Carga la lista de Adeudos del Residente ]*/
                // cargarMesesAdeudos(responseObj.recibo.residente_id);

                // /* Carga la lista de Pagados del Residente */
                // cargarMesesPagados(responseObj.recibo.residente_id);

                // /* Cargar la lista de meses adelantados */
                // cargarMesesAdelantados(responseObj.recibo.residente_id);

                // /* Retorna y actualiza Listado de Recibos */
                // fntCancelarReturnListRecibo();
                // tableRecibos.ajax.reload(function() {});

                get_residente(responseObj.recibo.residente_id, function (result) {
                    loadResidente(result);
                });


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

    var ajaxUrl = base_url + '/Recibos/setRecibo';
    var formData = new FormData(formRecibo);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}

/*==================================================================
[ Vista Registro ]*/

function fntViewRecibo(btnElement) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-eye')

    let list = document.getElementById('list_recibo');
    let editar = document.getElementById('crear_editar_recibo');
    let view = document.getElementById('view_recibo');
    list.style.display = "none";
    editar.style.display = "none";
    view.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.view_recibo');

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
                    timer: objDataResponse.tiempo,
                    title: iconMensajeSuccess + ' ¡Atención!',
                    text: objDataResponse.mensaje,
                    textButton: 'Cerrar'
                }).then(function (result) {
                    if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-eye'); };
                    if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-eye'); }
                });
            }
        }
    };
    let ajaxUrl = base_url + '/Recibos/getRecibo/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

function cargarDatosRecibo(modelDataObj) {

    document.getElementById("inputIdRecibo").value = modelDataObj.data.id;

    /*-------------------------------------------
    [ Llena lso datos del form view  ]*/
    document.getElementById("inputFolio_read").innerHTML = modelDataObj.data.folio;

    document.getElementById("inputConcepto_read").innerHTML = modelDataObj.data.concepto;

    document.getElementById("inputImporte_read").innerHTML = modelDataObj.data.importe;

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

    //      fechaCancelacion_read
    // usuarioCancela_read
    // motivoCancelacion_read



}

function fntReimprimirRecibo(btnElement) {

    /*-------------------------------------------
    [ Obtiene id de registro ]*/
    var recibo_id = btnElement.getAttribute("data-id");

    url = base_url + '/recibos/generarComprobante/' + recibo_id;
    window.open(url, "Recibo de Cobro", "fullscreen=yes");


}

function vistaRecibo(recibo_id) {
    console.log("si netra aquei?");


    url = base_url + '/recibos/generarComprobante/' + recibo_id;
    console.log(url);

    window.open(url, "Recibo de Cobro", "fullscreen=yes");
    // window.location = base_url + '/recibos/generarComprobante/' + recibo_id;
}

function loadResidente(data) {

    // *** Mostrar Form de Inicio. ***
    fntCancelarReturnListRecibo();


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
    document.getElementById("res_indicaciones_generales_visitas").value = data.indicaciones_generales_visitas;

    saldo_disponible = data.saldo_disponible;

    // *** Asignar Id de Residente. ***
    // document.getElementById("recibo_residente_id").value = data.id;

    // *** Asigna los datos del Residente en el formulario***
    // document.getElementById("recibo_nombre").value = data.nombre;
    // document.getElementById("recibo_calle").value = data.calle;
    // document.getElementById("recibo_numero").value = data.numero;
    // document.getElementById("recibo_monto").value = "200";
    // document.getElementById("recibo_concepto").value = "MANTENIMIENTO DICIEMBRE 2021";

    // *** Envia foco al dato que menos se tiene datos. ***
    // document.getElementById("recibo_nombre").focus();

    /*-------------------------------------------
    [ DataTable Inicializa - Recibos asociados al residente ]*/
    setConfigTableRecibos('Recibos', 'getRecibos/' + data.id);
    tableRecibos = $(tableRecibosElement).DataTable(configTableRecibos);

    /*-------------------------------------------
    [ Carga la lista de Adeudos del Residente ]*/
    cargarMesesAdeudos(data.id);

    /*-------------------------------------------
    [ Carga la lista de Pagados del Residente ]*/
    cargarMesesPagados(data.id);

    /*-------------------------------------------
    [ Carga la lista de Meses Adelantados ]*/
    cargarMesesAdelantados(data.id);

    /*-------------------------------------------
    [ Carga lel estatus del Portal del Residente ]*/
    estatusPortal(data.id);


}

function fntEnviarReciboEmail(btnElement) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-paper-plane')

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

                mensajeAlertaModal({
                    icon: 'success',
                    timer: objDataResponse.tiempo,
                    title: iconMensajeSuccess + ' ¡Atención!',
                    text: objDataResponse.mensaje,
                    textButton: 'Cerrar'
                }).then(function (result) {
                    if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-eye'); };
                    if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-eye'); }
                });


                restablecerButtonOpcionesDataTable(btnElement, 'fa-paper-plane');
            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: objDataResponse.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: objDataResponse.mensaje,
                    textButton: 'Cerrar'
                }).then(function (result) {
                    if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-paper-plane'); };
                    if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-paper-plane'); }
                });
            }
        }
    };
    let ajaxUrl = base_url + '/recibos/enviarComprobante/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

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
//                 btnNuevoRecibo.click();

//                 let residente_id = document.getElementById("residente_id").value;
//                 /* Carga la lista de Adeudos del Residente ]*/
//                 cargarMesesAdeudos(residente_id);

//                 /* Carga la lista de Pagados del Residente */
//                 cargarMesesPagados(residente_id);

//                 /* Retorna y actualiza Listado de Recibos */
//                 fntCancelarReturnListRecibo();
//                 tableRecibos.ajax.reload(function () { });

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

//     let ajaxUrl = base_url + '/Recibos/setCancelarRecibo/';
//     let strData = "";
//     strData += "id=" + recibo_id;
//     strData += "&estatus=1";
//     strData += "&residente_id=" + document.getElementById("residente_id").value;
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
[ Activar Portal Residente ]*/

function setPortalResidente() {


    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnActivarPortalResidente');
    agregarLoadingButtonGuardar(btnGuardar);


    /*-------------------------------------------
    [ Valida Inputs necesarios que no estpen vacíos. ]*/
    var residente_id = document.getElementById('residente_id').value;
    if (residente_id == '') {
        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe seleccionar un residente.',
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return;
    }


    var res_email = document.getElementById('res_email').value;
    if (res_email == '') {
        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe indicar correo electrónico.',
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return;
    }


    /*-------------------------------------------
    [ Ajax ]*/
    var formData = new FormData();
    formData.append('residente_id', residente_id);
    formData.append('res_email', res_email);

    postFunctionData(formData, 'Recibos', 'setUsuarioPortal', function (responseObj) {


        if (responseObj.respuesta == "ok") {

            /** -- Actualza estatus del portal -- */
            estatusPortal(residente_id);

            /** -- Mensaje de Alerta -- */
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

        } else {

            /** -- Mensaje de Alerta -- */
            mensajeAlertaModal({
                icon: 'error',
                timer: 4000,
                title: iconMensajeError + ' ¡Atención!',
                text: responseObj.mensaje,
                textButton: 'Cerrar'
            }).then(function (result) {
                if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
            })

        }

    });

}

function estatusPortal(residente_id) {

    var portal_info = document.querySelector('.portal_info');
    var portal_info_activar = document.querySelector('.portal_info_activar');
    var portal_info_desactivar = document.querySelector('.portal_info_desactivar');

    getFunctionData('Recibos', 'getEstatusResidentePortal', residente_id, function (responseObj) {

        if (responseObj.respuesta == "ok") {

            if (responseObj.data.portal_estatus == 1) {

                portal_info_activar.style.display = 'none';
                portal_info.style.display = 'block';
                if (responseObj.data.estatus == 1) {
                    portal_info.innerHTML = `<div style="padding: 6px 20px;" class="portal_info ms-3 alert alert-success alert-dismissible fade show" role="alert">
                                                    <span class="alert-inner--icon"><i class="fe fe-thumbs-up"></i></span>
                                                    <span class="alert-inner--text"><strong>¡Portal Activado!</strong> Usuario Activo</span>
                                                </div>`
                } else {
                    portal_info.innerHTML = `<div style="padding: 6px 20px;" class="portal_info ms-3 alert alert-danger alert-dismissible fade show" role="alert">
                                                    <span class="alert-inner--icon"><i class="fe fe-slash"></i></span>
                                                    <span class="alert-inner--text"><strong>¡Portal Activado!</strong> Usuario Inactivo</span>
                                                </div>`
                }
                portal_info_desactivar.style.display = 'block';

            } else {

                portal_info_activar.style.display = 'block';
                portal_info_desactivar.style.display = 'none';
                portal_info.style.display = 'none';
                portal_info.innerHTML = "";
            }

        } else {

            portal_info_activar.style.display = 'block';
            portal_info_desactivar.style.display = 'none';
            portal_info.style.display = 'none';
            portal_info.innerHTML = "";

        }

    });

}

function desactivarPortalResidente() {


    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnDesactivarPortalResidente');
    agregarLoadingButtonGuardar(btnGuardar);


    /*-------------------------------------------
    [ Valida Inputs necesarios que no estpen vacíos. ]*/
    var residente_id = document.getElementById('residente_id').value;
    if (residente_id == '') {
        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe seleccionar un residente.',
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return;
    }


    /*-------------------------------------------
    [ Ajax ]*/
    var formData = new FormData();
    formData.append('residente_id', residente_id);
    formData.append('estatus', 0);

    postFunctionData(formData, 'Recibos', 'estatusPortalResidente', function (responseObj) {

        if (responseObj.respuesta == "ok") {

            /** -- Actualza estatus del portal -- */
            estatusPortal(residente_id);

            /** -- Mensaje de Alerta -- */
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

        } else {

            /** -- Mensaje de Alerta -- */
            mensajeAlertaModal({
                icon: 'error',
                timer: 4000,
                title: iconMensajeError + ' ¡Atención!',
                text: responseObj.mensaje,
                textButton: 'Cerrar'
            }).then(function (result) {
                if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
            })

        }

    });


}

function regenerarPortalResidente() {


    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnRegenerarUsuarioPortal');
    agregarLoadingButtonGuardar(btnGuardar);


    /*-------------------------------------------
    [ Valida Inputs necesarios que no estpen vacíos. ]*/
    var residente_id = document.getElementById('residente_id').value;
    if (residente_id == '') {
        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe seleccionar un residente.',
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return;
    }


    var res_email = document.getElementById('res_email').value;
    if (res_email == '') {
        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe indicar correo electrónico.',
            textButton: 'Cerrar'
        }).then(function (result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return;
    }


    /*-------------------------------------------
    [ Ajax ]*/
    var formData = new FormData();
    formData.append('residente_id', residente_id);
    formData.append('res_email', res_email);

    postFunctionData(formData, 'Recibos', 'resetUsuarioPortal', function (responseObj) {


        if (responseObj.respuesta == "ok") {

            /** -- Actualza estatus del portal -- */
            estatusPortal(residente_id);

            /** -- Mensaje de Alerta -- */
            mensajeAlertaModal({
                icon: 'success',
                timer: responseObj.tiempo,
                title: iconMensajeSuccess + ' ¡Atención!',
                text: responseObj.mensaje,
                textButton: 'Cerrar'
            }).then(function (result) {
                if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
            });

        } else {

            /** -- Mensaje de Alerta -- */
            mensajeAlertaModal({
                icon: 'error',
                timer: 4000,
                title: iconMensajeError + ' ¡Atención!',
                text: responseObj.mensaje,
                textButton: 'Cerrar'
            }).then(function (result) {
                if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
            })

        }

    });

}


/*==================================================================
[ DataTable ]*/

function setConfigTableRecibos(controlador, metodo) {

    configTableRecibos = {
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
            sheetName: 'Lista de Recibos de Cobro',
            extend: 'excel',
            messageTop: "",
            title: 'Amores - Lista de Recibos de Cobro',
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
            { "data": "importe" },
            { "data": "estatus" },
            { "data": "options" }
        ],
        'language': idioma_espanol

    };

}


/*==================================================================
[ DataTable Filtros ]*/