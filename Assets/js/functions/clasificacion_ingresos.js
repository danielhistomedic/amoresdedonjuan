/*==================================================================
[ Variables de Archivo ]*/

let tableClasifIngresos;
let tableClasifIngresosElement = "#tableClasificacionIngresos";
let tableClasifIngresosElementJS = "tableClasificacionIngresos";
let configTableClasifIngresos = "";

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Clasificacion de Ingresos]*/
    if (document.getElementById('formClasificacionIngresos')) {
        var formClasificacionIngresos = document.getElementById('formClasificacionIngresos');
        formClasificacionIngresos.addEventListener("submit", function(event) { setClasifIngresos(event) });
    }

    /*==================================================================
    [ Botons de Accion ]*/

    /*-------------------------------------------
    [ Button - Agregar evento click para Nuevo Registro ]*/
    if (document.getElementById('btnNuevaClasifIngresos')) {
        var btnNuevaClasifIngresos = document.getElementById('btnNuevaClasifIngresos');
        btnNuevaClasifIngresos.onclick = function() { nuevoRegistroClasifIngresos() };
    }

    /*-------------------------------------------
     [ Agregar evento click para regresar a listado  ]*/

    if (document.querySelector(".btnCancelar_ClasifIngresos")) {
        var btnCancelar_ClasifIngresos = document.querySelectorAll('.btnCancelar_ClasifIngresos');
        for (let index = 0; index < btnCancelar_ClasifIngresos.length; index++) {
            const element = btnCancelar_ClasifIngresos[index];
            element.onclick = function() { fntCancelarReturnListClasifIngresos() };
        }
    }

    /*==================================================================
    [ DataTable ]*/

    if (document.getElementById(tableClasifIngresosElementJS)) {


        /*-------------------------------------------
        [ DataTable Inicializa - Lista de Calsificaciones ]*/
        setConfigTableClasifIngresos('Catalogos', 'getClasificacionesIngresos');
        tableClasifIngresos = $(tableClasifIngresosElement).DataTable(configTableClasifIngresos);


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableClasifIngresosElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableClasifIngresosElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableClasifIngresosElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableClasifIngresosElement).on('click', 'tbody tr>td', function() {

        });

    }

});

/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {


}, false)

/*==================================================================
[ Funciones de Manejo de Datos de Registro de Clasificacion de Ingresos ]*/

/*==================================================================
[ Nuevo Registro ]*/

function nuevoRegistroClasifIngresos() {

    /*-------------------------------------------
    [ Limpiar Form ]*/
    let formElement = document.getElementById('formClasificacionIngresos');
    resetFormNoPasley(formElement);


    // 
    let list = document.getElementById('list_clasificacion_ingresos');
    let editar = document.getElementById('crear_editar_clasif_ingresos');
    let view = document.getElementById('view_clasificacion_ingresos');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_clasif_ingresos');


    //Elemento que recibe la animación
    let eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}

function fntCancelarReturnListClasifIngresos() {

    let list = document.getElementById('list_clasificacion_ingresos');
    let editar = document.getElementById('crear_editar_clasif_ingresos');
    let view = document.getElementById('view_clasificacion_ingresos');
    list.style.display = "block";
    editar.style.display = "none";
    view.style.display = "none";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.list_clasificacion_ingresos');

    //Elemento que recibe la animación
    let eLDestino = list;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}

/*==================================================================
[ Guardar Registro ]*/

function setClasifIngresos(e) {

    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnGuardarClasifIngresos');
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
                btnNuevaClasifIngresos.click();

                /* Reactiva el botón para guardar registro */
                removerClasesButtonGuardar(btnGuardar, loading);

                /* Retorna y actualiza Listado de Tags */
                fntCancelarReturnListClasifIngresos();
                tableClasifIngresos.ajax.reload(function() {});

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
    var ajaxUrl = base_url + '/Catalogos/setClasifIngresos';
    var formData = new FormData(formClasificacionIngresos);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}

/*==================================================================
[ Editar Registro ]*/

function fntEditClasifIngresos(btnElement) {

    /*-------------------------------------------
    [ Limpiar Form ]*/
    let formElement = document.getElementById('formClasificacionIngresos');
    resetFormNoPasley(formElement);

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-pencil-alt')


    /*-------------------------------------------
    [ Animación de Paneles ]*/
    let list = document.getElementById('list_clasificacion_ingresos');
    let editar = document.getElementById('crear_editar_clasif_ingresos');
    let view = document.getElementById('view_clasificacion_ingresos');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_clasif_ingresos');

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
                cargarDatosEditClasifIngresos(responseObj);

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
    let ajaxUrl = base_url + '/Catalogos/getClasificacionIngresos/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

function cargarDatosEditClasifIngresos(modelDataObj) {

    /*-------------------------------------------
    [ Obtiene datos de Form  ]*/
    document.getElementById("inputClasifIngresosId").value = modelDataObj.data.id;
    document.getElementById("inputClasifIngresos").value = modelDataObj.data.concepto;


}

/*==================================================================
[ Vista Registro ]*/

function fntViewClasifIngresos(btnElement) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-eye')

    let list = document.getElementById('list_clasificacion_ingresos');
    let editar = document.getElementById('crear_editar_clasif_ingresos');
    let view = document.getElementById('view_clasificacion_ingresos');
    list.style.display = "none";
    editar.style.display = "none";
    view.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.view_clasificacion_ingresos');

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
                cargarDatosClasificacionIngresos(objDataResponse);
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
    let ajaxUrl = base_url + '/Catalogos/getClasificacionIngresos/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

function cargarDatosClasificacionIngresos(modelDataObj) {

    document.getElementById("inputClasifIngresosId").value = modelDataObj.data.id;

    /*-------------------------------------------
    [ Llena los datos del form view  ]*/
    document.getElementById("inputClasificacion_read").innerHTML = modelDataObj.data.concepto;

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

function fntDeleteClasifIngresos(btnElement) {

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

function deleteClasificacion(clasif_ingresos_id) {


    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            let responseObj = JSON.parse(xhttp.responseText);

            if (responseObj.respuesta == "ok") {
                tableClasifIngresos.ajax.reload(function() {});
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

    let ajaxUrl = base_url + '/Catalogos/eliminarClasificacionIngresos/';

    var formData = new FormData();
    formData.append('id', clasif_ingresos_id)
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}

/*==================================================================
[ Reactivar Registro ]*/

function fntActiveClasifIngresos(btnElement) {

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

function activeClasificacion(clasif_ingresos_id) {

    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            let responseObj = JSON.parse(xhttp.responseText);
            if (responseObj.respuesta == "ok") {
                tableClasifIngresos.ajax.reload(function() {});
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

    let ajaxUrl = base_url + '/Catalogos/activarClasificacionIngresos/';

    var formData = new FormData();
    formData.append('id', clasif_ingresos_id)
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}

/*==================================================================
[ DataTable ]*/

function setConfigTableClasifIngresos(controlador, metodo) {

    configTableClasifIngresos = {
        "aProcessing": true,
        "aServerSide": true,
        'destroy': true,
        'responsive': false,
        "autoWidth": false,
        "iDisplayLength": 10,
        "order": [
            [1, "asc"]
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
                sheetName: 'Cat Ingresos',
                extend: 'excel',
                messageTop: "",
                title: 'Amores - Lista de Clasificación de Ingresos Registrados',
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
            { "data": "concepto" },
            { "data": "activo" },
            { "data": "options" }
        ],
        'language': idioma_espanol

    };

}