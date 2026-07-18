/*==================================================================
[ Variables de Archivo ]*/

let tableClasifGastos;
let tableClasifGastosElement = "#tableClasificacionGastos";
let tableClasifGastosElementJS = "tableClasificacionGastos";
let configTableClasifGastos = "";

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Clasificacion de Gastos]*/
    if (document.getElementById('formClasificacionGastos')) {
        var formClasificacionGastos = document.getElementById('formClasificacionGastos');
        formClasificacionGastos.addEventListener("submit", function(event) { setClasifGastos(event) });
    }

    /*==================================================================
    [ Botons de Accion ]*/

    /*-------------------------------------------
    [ Button - Agregar evento click para Nuevo Registro ]*/
    if (document.getElementById('btnNuevaClasifGastos')) {
        var btnNuevaClasifGastos = document.getElementById('btnNuevaClasifGastos');
        btnNuevaClasifGastos.onclick = function() { nuevoRegistroClasifGastos() };
    }

    /*-------------------------------------------
     [ Agregar evento click para regresar a listado  ]*/

    if (document.querySelector(".btnCancelar_ClasifGastos")) {
        var btnCancelar_ClasifGastos = document.querySelectorAll('.btnCancelar_ClasifGastos');
        for (let index = 0; index < btnCancelar_ClasifGastos.length; index++) {
            const element = btnCancelar_ClasifGastos[index];
            element.onclick = function() { fntCancelarReturnListClasifGastos() };
        }
    }

    /*==================================================================
    [ DataTable ]*/

    if (document.getElementById(tableClasifGastosElementJS)) {


        /*-------------------------------------------
        [ DataTable Inicializa - Lista de Calsificaciones ]*/
        setConfigTableClasifGastos('Catalogos', 'getClasificacionesGastos');
        tableClasifGastos = $(tableClasifGastosElement).DataTable(configTableClasifGastos);


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableClasifGastosElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableClasifGastosElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableClasifGastosElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableClasifGastosElement).on('click', 'tbody tr>td', function() {

        });

    }

});

/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {


}, false)

/*==================================================================
[ Funciones de Manejo de Datos de Registro de Clasificacion de Gastos ]*/

/*==================================================================
[ Nuevo Registro ]*/

function nuevoRegistroClasifGastos() {

    /*-------------------------------------------
    [ Limpiar Form ]*/
    let formElement = document.getElementById('formClasificacionGastos');
    resetFormNoPasley(formElement);


    // 
    let list = document.getElementById('list_clasificacion_gastos');
    let editar = document.getElementById('crear_editar_clasif_gastos');
    let view = document.getElementById('view_clasificacion_gastos');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_clasif_gastos');


    //Elemento que recibe la animación
    let eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}

function fntCancelarReturnListClasifGastos() {

    let list = document.getElementById('list_clasificacion_gastos');
    let editar = document.getElementById('crear_editar_clasif_gastos');
    let view = document.getElementById('view_clasificacion_gastos');
    list.style.display = "block";
    editar.style.display = "none";
    view.style.display = "none";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.list_clasificacion_gastos');

    //Elemento que recibe la animación
    let eLDestino = list;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}

/*==================================================================
[ Guardar Registro ]*/

function setClasifGastos(e) {

    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnGuardarClasifGastos');
    agregarLoadingButtonGuardar(btnGuardar);


    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();


    /*-------------------------------------------
    [ Valida Inputs que no estpen vacíos. ]*/
    var check = true;
    var input = document.querySelectorAll("input.inputForm100");

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
            text: "Todos los campos son obligatorios.",
            textButton: 'Cerrar'
        }).then(function(result) {
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
    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {

            var responseObj = JSON.parse(xhttp.responseText);

            if (responseObj.respuesta == "ok") {

                /* Resetea el Form de registro para limpiar los datos */
                btnNuevaClasifGastos.click();

                /* Reactiva el botón para guardar registro */
                removerClasesButtonGuardar(btnGuardar, loading);

                /* Retorna y actualiza Listado de Tags */
                fntCancelarReturnListClasifGastos();
                tableClasifGastos.ajax.reload(function() {});

                /* Muestra Mensaje Modal y Reactiva el botón para guardar registro */
                if (responseObj.mostrar_mensaje) {
                    mensajeAlertaModal({
                        icon: "success",
                        timer: responseObj.tiempo,
                        title: iconMensajeSuccess + ' ¡Atención!',
                        text: responseObj.mensaje,
                        textButton: 'Cerrar'
                    }).then(function(result) {
                        if (result.dismissTimer == true) {};
                        if (result.dismissUser == true) {}
                    });
                }

            } else {
                if (responseObj.mostrar_mensaje) {
                    mensajeAlertaModal({
                        icon: 'error',
                        timer: responseObj.tiempo,
                        title: iconMensajeError + ' ¡Atención!',
                        text: responseObj.mensaje,
                        textButton: 'Cerrar'
                    }).then(function(result) {
                        if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                        if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
                    });
                }

            }
        }
    };
    var ajaxUrl = base_url + '/Catalogos/setClasifGastos';
    var formData = new FormData(formClasificacionGastos);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}

/*==================================================================
[ Editar Registro ]*/

function fntEditClasifGastos(btnElement) {

    /*-------------------------------------------
    [ Limpiar Form ]*/
    let formElement = document.getElementById('formClasificacionGastos');
    resetFormNoPasley(formElement);

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-pencil-alt')


    /*-------------------------------------------
    [ Animación de Paneles ]*/
    let list = document.getElementById('list_clasificacion_gastos');
    let editar = document.getElementById('crear_editar_clasif_gastos');
    let view = document.getElementById('view_clasificacion_gastos');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_clasif_gastos');

    //Elemento que recibe la animación
    let eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);


    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {
            let responseObj = JSON.parse(xhttp.responseText);
            if (responseObj.respuesta == "ok") {

                /*-------------------------------------------
                [ Asigna los valores de inputs ]*/
                cargarDatosEditClasifGastos(responseObj);

                /*-------------------------------------------
                [ Reactiva el botón de origen ]*/
                restablecerButtonOpcionesDataTable(btnElement, 'fa-pencil-alt');

                if (responseObj.mostrar_mensaje) {
                    mensajeAlertaModal({
                        icon: 'success',
                        timer: responseObj.tiempo,
                        title: iconMensajeSuccess + ' ¡Atención!',
                        text: responseObj.mensaje,
                        textButton: 'Cerrar'
                    });
                }

            } else {

                /*-------------------------------------------
                [ Reactiva el botón de origen ]*/
                restablecerButtonOpcionesDataTable(btnElement, 'fa-pencil-alt');

                if (responseObj.mostrar_mensaje) {
                    mensajeAlertaModal({
                        icon: 'error',
                        timer: responseObj.tiempo,
                        title: iconMensajeError + ' ¡Atención!',
                        text: responseObj.mensaje,
                        textButton: 'Cerrar'
                    });
                }

            }

        }

    };
    let ajaxUrl = base_url + '/Catalogos/getClasificacionGastos/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

function cargarDatosEditClasifGastos(modelDataObj) {

    /*-------------------------------------------
    [ Obtiene datos de Form  ]*/
    document.getElementById("inputClasifGastosId").value = modelDataObj.data.id;
    document.getElementById("inputClasifGastos").value = modelDataObj.data.clasificacion;


}

/*==================================================================
[ Vista Registro ]*/

function fntViewClasifGastos(btnElement) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-eye')

    let list = document.getElementById('list_clasificacion_gastos');
    let editar = document.getElementById('crear_editar_clasif_gastos');
    let view = document.getElementById('view_clasificacion_gastos');
    list.style.display = "none";
    editar.style.display = "none";
    view.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.view_clasificacion_gastos');

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
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let objDataResponse = JSON.parse(xhttp.responseText);
            if (objDataResponse.respuesta == "ok") {
                cargarDatosClasificacionGastos(objDataResponse);
                restablecerButtonOpcionesDataTable(btnElement, 'fa-eye');
            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: objDataResponse.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: objDataResponse.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-eye'); };
                    if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-eye'); }
                });
            }
        }
    };
    let ajaxUrl = base_url + '/Catalogos/getClasificacionGastos/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

function cargarDatosClasificacionGastos(modelDataObj) {

    document.getElementById("inputClasifGastosId").value = modelDataObj.data.id;

    /*-------------------------------------------
    [ Llena los datos del form view  ]*/
    document.getElementById("inputClasificacion_read").innerHTML = modelDataObj.data.clasificacion;

    let estatus =
        modelDataObj.data.activo == 1 ?
        '<span class = "badge badge-success">Activo</span>' :
        '<span class = "badge badge-danger">Inactivo</span>';
    document.getElementById("estatus_read").innerHTML = estatus;

    document.getElementById("fechaRegistro_read").innerHTML = modelDataObj.data.updated_at;
    document.getElementById("usuarioRegistro_read").innerHTML = modelDataObj.data.usuario;

}

/*==================================================================
[ Eliminar Registro ]*/

function fntDeleteClasifGastos(btnElement) {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-trash-can')


    mensajeAlertaModal({
        icon: 'warning',
        title: iconMensajeWarning + ' ¡Advertencia!',
        text: "¿Está seguro de eliminar la Clasificación seleccionada?",
        textButton: 'Sí, Eliminar.',
        textCancelButton: 'No, Cerrar.'
    }).then(function(result) {
        if (result.dismiss == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-trash-can'); };
        if (result.si == true) { deleteClasificacion(idRegistro) };
    });

}

function deleteClasificacion(clasif_gastos_id) {


    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            let responseObj = JSON.parse(xhttp.responseText);

            if (responseObj.respuesta == "ok") {
                tableClasifGastos.ajax.reload(function() {});
                mensajeAlertaModal({
                    icon: 'success',
                    timer: responseObj.tiempo,
                    title: iconMensajeSuccess + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                });
            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismiss == true) {
                        restablecerButtonOpcionesDataTable(btnElement, 'fa-trash-can');
                    };
                });
            }
        }
    };

    let ajaxUrl = base_url + '/Catalogos/eliminarClasificacionGastos/';

    var formData = new FormData();
    formData.append('id', clasif_gastos_id)
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}

/*==================================================================
[ Reactivar Registro ]*/

function fntActiveClasifGastos(btnElement) {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-arrow-rotate-left')


    mensajeAlertaModal({
        icon: 'warning',
        title: iconMensajeWarning + ' ¡Advertencia!',
        text: "¿Está seguro de Reactivar la Clasificación seleccionada?",
        textButton: 'Sí, Reactivar.',
        textCancelButton: 'No, Cerrar.'
    }).then(function(result) {
        if (result.dismiss == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-arrow-rotate-left'); };
        if (result.si == true) { activeClasificacion(idRegistro) };
    });

}

function activeClasificacion(clasif_gastos_id) {

    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            let responseObj = JSON.parse(xhttp.responseText);
            if (responseObj.respuesta == "ok") {
                tableClasifGastos.ajax.reload(function() {});
                mensajeAlertaModal({
                    icon: 'success',
                    timer: responseObj.tiempo,
                    title: iconMensajeSuccess + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                });
            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismiss == true) {
                        restablecerButtonOpcionesDataTable(btnElement, 'fa-arrow-rotate-left');
                    };
                });
            }
        }
    };

    let ajaxUrl = base_url + '/Catalogos/activarClasificacionGastos/';

    var formData = new FormData();
    formData.append('id', clasif_gastos_id)
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}

/*==================================================================
[ DataTable ]*/

function setConfigTableClasifGastos(controlador, metodo) {

    configTableClasifGastos = {
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
                sheetName: 'Lista de Clasificación de Gastos Registrados',
                extend: 'excel',
                messageTop: "",
                title: 'Amores - Lista de Clasificación de Gastos Registrados',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'colvis',
                columnText: function(dt, idx, title) {
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
            { "data": "created_at" },
            { "data": "clasificacion" },
            { "data": "activo" },
            { "data": "options" }
        ],
        'language': idioma_espanol

    };

}