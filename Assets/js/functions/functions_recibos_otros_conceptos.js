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


    /*==================================================================
    [ Botons de Accion ]*/

    if (document.getElementById('btnNuevoRecibo')) {
        var btnNuevoRecibo = document.getElementById('btnNuevoRecibo');
        btnNuevoRecibo.onclick = function () { nuevoRecibo() };
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
    [ Llenar Selects ]*/
    fillSelectClasificacionIngresos();

    /*-------------------------------------------
    [ Activa el menu horizontal (prinicipal) correspondiente del sidemenu ]*/
    let pageUrl = base_url + "/administracion"
    fntActivarHorizontalMenu(pageUrl);

}, false)


/*==================================================================
[ Funciones Fill Selects e Inicializa Select2]*/

function fillSelectClasificacionIngresos() {

    if (document.querySelector('#comboClasificacionIngresos')) {


        //Ajax LLenar Select de catalogo de roles
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboClasificacionIngresos').innerHTML = xhttp.responseText;

                /*-------------------------------------------
                [ Inicializa Select2 ]*/
                $('#comboClasificacionIngresos').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion',
                    minimumResultsForSearch: Infinity
                });

                //Asignar Valor Default después de Inicializar Seleclt2
                $('#comboClasificacionIngresos').val('');
                $('#comboClasificacionIngresos').trigger('change');

            }

        };
        var ajaxUrl = base_url + '/Catalogos/getSelectClasificacionesIngresos';
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();
    }

}


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

    // Regresa conteo a uno de cantidad.
    cantidad = 1;
    concepto = "OTROS CONCEPTOS ";

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

    document.getElementById("recibo_concepto").value = "";
    document.getElementById("recibo_importe").value = "0";
    document.getElementById("recibo_recibe").value = "0";
    document.getElementById("recibo_cambio").value = "0";

    document.getElementById("recibo_concepto").focus();

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

function calcularImporte() {

    let recibo_importe = document.getElementById("recibo_importe");

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

    var ajaxUrl = base_url + '/Recibos/setReciboOtrosConceptos';
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



}

function fntReimprimirRecibo(btnElement) {

    /*-------------------------------------------
    [ Obtiene id de registro ]*/
    var recibo_id = btnElement.getAttribute("data-id");

    url = base_url + '/recibos/generarComprobante/' + recibo_id;
    window.open(url, "Recibo de Cobro", "fullscreen=yes");


}

function vistaRecibo(recibo_id) {
    url = base_url + '/recibos/generarComprobante/' + recibo_id;
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


    saldo_disponible = data.saldo_disponible;

    // *** Asignar Id de Residente. ***
    // document.getElementById("recibo_residente_id").value = data.id;

    // *** Asigna los datos del Residente en el formulario***
    // document.getElementById("recibo_nombre").value = data.nombre;
    // document.getElementById("recibo_calle").value = data.calle;
    // document.getElementById("recibo_numero").value = data.numero;
    // document.getElementById("recibo_monto").value = "170";
    // document.getElementById("recibo_concepto").value = "MANTENIMIENTO DICIEMBRE 2021";

    // *** Envia foco al dato que menos se tiene datos. ***
    // document.getElementById("recibo_nombre").focus();

    /*-------------------------------------------
    [ DataTable Inicializa - Recibos asociados al residente ]*/
    setConfigTableRecibos('Recibos', 'getRecibosOtrosConceptos/' + data.id);
    tableRecibos = $(tableRecibosElement).DataTable(configTableRecibos);


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

//     let ajaxUrl = base_url + '/Recibos/setCancelarReciboOtrosConceptos/';
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