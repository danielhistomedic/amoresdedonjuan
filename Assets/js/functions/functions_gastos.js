/*==================================================================
[ Variables de Archivo ]*/

let tableGastos;
let tableGastosElement = "#tableGastos";
let tableGastosElementJS = "tableGastos";
let configTableGastos = "";

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Clasificacion de Gastos]*/
    if (document.getElementById('formGastos')) {
        var formGastos = document.getElementById('formGastos');
        formGastos.addEventListener("submit", function(event) { setGasto(event) });
    }

    /*==================================================================
    [ Botons de Accion ]*/

    /*-------------------------------------------
    [ Button - Agregar evento click para Nuevo Registro ]*/
    if (document.getElementById('btnNuevoGasto')) {
        var btnNuevoGasto = document.getElementById('btnNuevoGasto');
        btnNuevoGasto.onclick = function() { nuevoRegistroGasto() };
    }

    /*-------------------------------------------
     [ Agregar evento click para regresar a listado  ]*/

    if (document.querySelector(".btnRegresarLista_Gasto")) {
        var btnRegresarLista_Gasto = document.querySelectorAll('.btnRegresarLista_Gasto');
        for (let index = 0; index < btnRegresarLista_Gasto.length; index++) {
            const element = btnRegresarLista_Gasto[index];
            element.onclick = function() { fntReturnListfGastos() };
        }
    }

    /*==================================================================
    [ DataTable ]*/

    if (document.getElementById(tableGastosElementJS)) {


        /*-------------------------------------------
        [ DataTable Inicializa - Lista de Gastos ]*/
        setConfigTableGasto('Gastos', 'getGastos');
        tableGastos = $(tableGastosElement).DataTable(configTableGastos);


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableGastosElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableGastosElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableGastosElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableGastosElement).on('click', 'tbody tr>td', function() {

        });

    }

});

/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    /*-------------------------------------------
    [ Funciones Fill Selects ]*/
    fillSelectClasificacionGastos();

}, false)

/*==================================================================
[ Funciones de Manejo de Datos de Registro de Clasificacion de Gastos ]*/



/*==================================================================
[ Funciones Fill Selects e Inicializa Select2]*/

function fillSelectClasificacionGastos() {

    if (document.querySelector('#comboClasificacionGastos')) {


        //Ajax LLenar Select de catalogo de roles
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboClasificacionGastos').innerHTML = xhttp.responseText;

                /*-------------------------------------------
                [ Inicializa Select2 ]*/
                $('#comboClasificacionGastos').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion',
                    minimumResultsForSearch: Infinity
                });

                //Asignar Valor Default después de Inicializar Seleclt2
                $('#comboClasificacionGastos').val('');
                $('#comboClasificacionGastos').trigger('change');

            }

        };
        var ajaxUrl = base_url + '/Catalogos/getSelectClasificacionesGastos';
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();
    }

}


/*==================================================================
[ Nuevo Registro ]*/

function nuevoRegistroGasto() {

    /*-------------------------------------------
    [ Limpiar Form ]*/
    let formElement = document.getElementById('formGastos');
    resetFormNoPasley(formElement);

    document.getElementById('inputGastoId').value = "";


    /*-------------------------------------------
    [ Activa el input para caragr archivo ]*/
    document.getElementById("gastos_adjunto").innerHTML = `
                    <label class="form-label" for="inputGastosArchivo">Seleccione Archivo:</label>
                    <div class="d-flex">
                        <input type="file" class="form-control" name="inputGastosArchivo" id="inputGastosArchivo" placeholder="Seleccione Archivo" autocomplete="off">
                    </div>`;


    // 
    let list = document.getElementById('list_gastos');
    let editar = document.getElementById('crear_editar_gastos');
    let view = document.getElementById('view_gastos');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_gastos');


    //Elemento que recibe la animación
    let eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}

function fntReturnListfGastos() {

    let list = document.getElementById('list_gastos');
    let editar = document.getElementById('crear_editar_gastos');
    let view = document.getElementById('view_gastos');
    list.style.display = "block";
    editar.style.display = "none";
    view.style.display = "none";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.list_gastos');

    //Elemento que recibe la animación
    let eLDestino = list;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}

/*==================================================================
[ Guardar Registro ]*/

function setGasto(e) {

    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnGuardarGasto');
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
            text: "Debe llenar los campos requeridos.",
            textButton: 'Cerrar'
        }).then(function(result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return false;
    }

    /*-------------------------------------------
    [ Valida Selects que no estpen vacíos. ]*/
    if (!check) {
        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe llenar los campos requeridos.',
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
            console.log(responseObj);

            if (responseObj.respuesta == "ok") {

                /* Resetea el Form de registro para limpiar los datos */
                btnNuevoGasto.click();

                /* Reactiva el botón para guardar registro */
                removerClasesButtonGuardar(btnGuardar, loading);

                /* Retorna y actualiza Listado de Tags */
                fntReturnListfGastos();
                tableGastos.ajax.reload(function() {});

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
    var ajaxUrl = base_url + '/Gastos/setGasto';
    var formData = new FormData(formGastos);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}

/*==================================================================
[ Editar Registro ]*/

function fntEditGasto(btnElement) {

    /*-------------------------------------------
    [ Limpiar Form ]*/
    let formElement = document.getElementById('formGastos');
    resetFormNoPasley(formElement);

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-pencil-alt')


    /*-------------------------------------------
    [ Animación de Paneles ]*/
    let list = document.getElementById('list_gastos');
    let editar = document.getElementById('crear_editar_gastos');
    let view = document.getElementById('view_gastos');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_gastos');

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
            console.log(responseObj);

            if (responseObj.respuesta == "ok") {

                /*-------------------------------------------
                [ Asigna los valores de inputs ]*/
                cargarDatosEditGasto(responseObj);

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
    let ajaxUrl = base_url + '/Gastos/getGasto/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

function cargarDatosEditGasto(modelDataObj) {

    //     Id: "3"
    // archivo: "ad19cbbb.pdf"
    // clasificacion: "SERVICIO DE SEGURIDAD"
    // clasificacion_gasto_id: "14"
    // created_at: "2022-01-22 18:53:37"
    // descripcion: "PAGO PARCIAL DE SERVICIO DE VIGILANCIA RESTAN $12,095"
    // fecha_docto: "2021-11-30"
    // fecha_pago: "2021-12-02"
    // folio_nota: "158"
    // importe: "16000"
    // proveedor: "GENESIS"
    // updated_at: "2022-01-22 18:53:37"
    // usuario: "VICTOR DANIEL PEREZ VARGAS"
    // usuario_id_created: "3"
    // usuario_id_updated: "3"

    document.getElementById("inputGastoId").value = modelDataObj.data.Id;


    /*-------------------------------------------
    [ Asigna los valores de inputs  ]*/
    // let fecha_pago = Formato_Fecha_ddmmyyyy(modelDataObj.data.fecha_pago);
    // document.getElementById("inputGastoFechaPago").value = fecha_pago;

    document.getElementById("inputGastoProveedor").value = modelDataObj.data.proveedor;

    let fecha_nota = Formato_Fecha_ddmmyyyy(modelDataObj.data.fecha_docto);
    document.getElementById("inputGastoFechaNota").value = fecha_nota;

    document.getElementById("inputGastoFolioNota").value = modelDataObj.data.folio_nota;

    document.getElementById("inputGastoDescripcion").value = modelDataObj.data.descripcion;

    document.getElementById("inputGastoImporte").value = modelDataObj.data.importe;


    if (modelDataObj.data.archivo == '') {
        document.getElementById("gastos_adjunto").innerHTML = `
                            <label class="form-label" for="inputGastosArchivo">Seleccione Archivo:</label>
                            <div class="d-flex">
                                <input type="file" class="form-control" name="inputGastosArchivo" id="inputGastosArchivo" placeholder="Seleccione Archivo" autocomplete="off">
                            </div>

                `;
    } else {
        document.getElementById("gastos_adjunto").innerHTML = `
                            <label class="form-label" for="inputGastosArchivo">Archivo Adjunto:</label>
                            <div class="d-flex justify-content-start align-items-center">
                                <a target="_blank" href="${assets}/files/${modelDataObj.data.archivo}"><i class="fa-regular fa-paperclip-vertical fs-16"></i> Ver Archivo</a>
                                <button type="button" id="btnDeleteFile" class="ms-2 btn btn-outline-danger btn-sm rounded-11" title="Eliminar Archivo Adjunto">
                                    <i class="fa-regular fa-trash-can" id="iconDeleteFile"></i>
                                </button> 
                            </div>
                    `;
    }


    /*-------------------------------------------
    [ Asigna los valores de selects2  ]*/
    $('#comboClasificacionGastos').val(modelDataObj.data.clasificacion_gasto_id);
    $('#comboClasificacionGastos').trigger('change');


    /*-------------------------------------------
    [ Button - Agregar evento click para Button Eliminar Archivo ]*/
    if (document.getElementById('btnDeleteFile')) {
        var btnDeleteFile = document.getElementById('btnDeleteFile');
        btnDeleteFile.onclick = function() { fntDeleteFile() };
    }


}

/*==================================================================
[ Vista Registro ]*/

function fntViewGasto(btnElement) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-eye')

    let list = document.getElementById('list_gastos');
    let editar = document.getElementById('crear_editar_gastos');
    let view = document.getElementById('view_gastos');
    list.style.display = "none";
    editar.style.display = "none";
    view.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.view_gastos');

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

                /*-------------------------------------------
                [ Cargar lso datso obtendios en detalle de registro ]*/
                cargarDatosGasto(objDataResponse);

                /*-------------------------------------------
                [ Restablece el botón de origen donde se cargaron lso datos. ]*/
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
    let ajaxUrl = base_url + '/Gastos/getGasto/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

function cargarDatosGasto(modelDataObj) {

    /*-------------------------------------------
     [ Llena los datos del form view  ]*/
    let fecha_pago = Formato_Fecha_ddmmyyyy(modelDataObj.data.fecha_pago);
    document.getElementById("lblGasto_FechaPagado").innerHTML = fecha_pago;

    document.getElementById("lblGasto_Clasificacion").innerHTML = modelDataObj.data.clasificacion;
    document.getElementById("lblGasto_Proveedor").innerHTML = modelDataObj.data.proveedor;

    let fecha_nota = Formato_Fecha_ddmmyyyy(modelDataObj.data.fecha_docto);
    document.getElementById("lblGasto_FechaNota").innerHTML = fecha_nota;

    document.getElementById("lblGasto_FolioNota").innerHTML = modelDataObj.data.folio_nota;

    document.getElementById("lblGasto_Descripcion").innerHTML = modelDataObj.data.descripcion;

    let importe = Formato_Moneda(modelDataObj.data.importe)
    document.getElementById("lblGasto_Importe").innerHTML = importe;

    if (modelDataObj.data.archivo != '') {
        let file = `<a target="_blank" href="${assets}/files/${modelDataObj.data.archivo}"><i class="fa-regular fa-paperclip-vertical fs-16"></i> Ver Archivo</a>`;
        document.getElementById("lblGasto_Adjunto").innerHTML = file;
    }

    /*-------------------------------------------
    [ Mostrar Mas ]*/
    document.getElementById("fechaRegistro_read").innerHTML = modelDataObj.data.updated_at;
    document.getElementById("usuarioRegistro_read").innerHTML = modelDataObj.data.usuario;

}

/*==================================================================
[ Eliminar Registro ]*/

function fntDeleteGasto(btnElement) {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-trash-can')

    mensajeAlertaModal({
        icon: 'warning',
        title: iconMensajeWarning + ' ¡Advertencia!',
        text: "¿Está seguro de eliminar el Gasto seleccionada?",
        textButton: 'Sí, Eliminar.',
        textCancelButton: 'No, Cerrar.'
    }).then(function(result) {
        if (result.dismiss == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-trash-can'); };
        if (result.si == true) { deleteGasto(idRegistro) };
    });

}

function deleteGasto(gasto_id) {


    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            let responseObj = JSON.parse(xhttp.responseText);

            if (responseObj.respuesta == "ok") {

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
                mensajeAlertaModal({
                    icon: 'error',
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                });
            }
            tableGastos.ajax.reload(function() {});
        }
    };

    let ajaxUrl = base_url + '/Gastos/eliminarGasto/';

    var formData = new FormData();
    formData.append('id', gasto_id)
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}

/*==================================================================
[ Eliminar Archivo ]*/

function fntDeleteFile() {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = document.getElementById("inputGastoId").value

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    let btnIconElement = document.getElementById("iconDeleteFile");
    addLoadingButton(btnIconElement, 'fa-trash-can');

    /*-------------------------------------------
    [ Activa Mensaje Modal ]*/
    mensajeAlertaModal({
        icon: 'warning',
        title: iconMensajeWarning + ' ¡Advertencia!',
        text: "¿Está seguro de eliminar el Archivo?",
        textButton: 'Sí, Eliminar.',
        textCancelButton: 'No, Cerrar.'
    }).then(function(result) {
        if (result.dismiss == true) { removeLoadingButton(btnIconElement, 'fa-trash-can'); };
        if (result.si == true) { deleteFile(idRegistro) };
    });

}

function deleteFile(gastos_id) {


    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            let responseObj = JSON.parse(xhttp.responseText);
            let btnIconElement = document.getElementById("iconDeleteFile");
            removeLoadingButton(btnIconElement, 'fa-trash-can');

            if (responseObj.respuesta == "ok") {

                /*-------------------------------------------
                [ Recargar lista de registros ]*/
                tableGastos.ajax.reload(function() {});

                /*-------------------------------------------
                [ Activa el input para caragr archivo ]*/
                document.getElementById("gastos_adjunto").innerHTML = `
                    <label class="form-label" for="inputGastosArchivo">Seleccione Archivo:</label>
                    <div class="d-flex">
                        <input type="file" class="form-control" name="inputGastosArchivo" id="inputGastosArchivo" placeholder="Seleccione Archivo" autocomplete="off">
                    </div>`;

                /*-------------------------------------------
                [ Valida Mostrar Mensaje ]*/
                if (responseObj.mostrar_mensaje = true) {
                    mensajeAlertaModal({
                        icon: 'success',
                        timer: responseObj.tiempo,
                        title: iconMensajeSuccess + ' ¡Atención!',
                        text: responseObj.mensaje,
                        textButton: 'Cerrar'
                    });
                }

            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                });
            }
        }
    };

    let ajaxUrl = base_url + '/Gastos/eliminarArchivo/';

    var formData = new FormData();
    formData.append('id', gastos_id)
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}



/*==================================================================
[ DataTable ]*/

function setConfigTableGasto(controlador, metodo) {

    configTableGastos = {
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
                sheetName: 'Lista de Gastos Registrados',
                extend: 'excel',
                messageTop: "",
                title: 'Amores - Lista de Gastos Registrados',
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
            { "data": "fecha_pago" },
            { "data": "clasificacion" },
            { "data": "proveedor" },
            { "data": "fecha_docto" },
            { "data": "folio_nota" },
            { "data": "descripcion" },
            { "data": "importe" },
            { "data": "archivo" },
            { "data": "options" }
        ],
        'language': idioma_espanol

    };

}