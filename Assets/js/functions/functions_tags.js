/*==================================================================
[ Variables de Archivo ]*/

let tableTags;
let tableTagsElement = "#tableTags";
let tableTagsElementJS = "tableTags";
let configTableTags = "";

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {


    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Tags]*/
    if (document.getElementById('formVigilanciaTag')) {
        var formVigilanciaTag = document.getElementById('formVigilanciaTag');
        formVigilanciaTag.addEventListener("submit", function(event) { setTag(event) });
    }

    /*-------------------------------------------
    [ Form - Autocomplete ]*/

    if (document.getElementById('res_tag')) {
        $('#res_tag').autocomplete({
            source: function(request, response) {
                get_tag_search(request.term, function(result) {
                    response(result);
                });
            },
            minLength: 3,
            select: function(event, ui) {
                loadTag(ui.item.id);
            }
        }).data('ui-autocomplete')._renderItem = function(ul, item) {
            return $('<li class="ui-automplete-row"></li>')
                .data('item.autocomplete', item)
                .append(item.label)
                .appendTo(ul);
        }
    }

    /*==================================================================
    [ Botons de Accion ]*/

    /*-------------------------------------------
    [ Button - Agregar evento click para Nuevo Registro ]*/
    if (document.getElementById('btnNuevaTag')) {
        var btnNuevaTag = document.getElementById('btnNuevaTag');
        btnNuevaTag.onclick = function() { nuevoRegistroTag() };
    }

    /*-------------------------------------------
     [ Agregar evento click para regresar a listado  ]*/

    if (document.querySelector(".btnCancelar_Tag")) {
        var btnCancelar_Tag = document.querySelectorAll('.btnCancelar_Tag');
        for (let index = 0; index < btnCancelar_Tag.length; index++) {
            const element = btnCancelar_Tag[index];
            element.onclick = function() { fntCancelarReturnListTag() };
        }
    }

    /*==================================================================
    [ DataTable ]*/

    if (document.getElementById(tableTagsElementJS)) {

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableTagsElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableTagsElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableTagsElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableTagsElement).on('click', 'tbody tr>td', function() {

        });

    }


});




/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {


}, false)


/*==================================================================
[ Autocomplete ]*/

function get_tag_search(filtro, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/Tags/getTagsSearch/' + filtro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}


/*==================================================================
[ Funciones de Manejo de Datos de Registro de Tags ]*/


function getResidentesTag(id, result) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/Tags/getTagsResidente/' + id;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}

function loadResidente(data) {

    // *** Limpiar Buscador. ***
    document.getElementById("search_residente").value = "";

    // *** Asigna los datos del Residente en el formulario***
    document.getElementById("residente-nombre").innerHTML = data.nombre;
    document.getElementById("residente-domicilio").innerHTML = data.calle + ' ' + data.numero;

    if (data.email == null) {
        document.getElementById("residente-email").innerHTML = 'No Registrado';
    } else {
        document.getElementById("residente-email").innerHTML = data.email;
    }

    if (data.telefono == null) {
        document.getElementById("residente-telefono").innerHTML = 'No Registrado';
    } else {
        document.getElementById("residente-telefono").innerHTML = data.telefono;
    }

    // *** Asignar Id de Residente. ***
    document.getElementById("residente_id").value = data.id;


    /*-------------------------------------------
    [ DataTable Inicializa - Lista de Tags asociados al residente ]*/
    setConfigTableTags('Tags', 'getTags/' + data.id);
    tableTags = $(tableTagsElement).DataTable(configTableTags);

}

function loadTag(id) {

    // *** Asignar Id de Residente. ***
    document.getElementById("tag_id").value = id;

}


/*==================================================================
[ Nuevo Registro ]*/

function nuevoRegistroTag() {

    /*-------------------------------------------
    [ Limpiar Form ]*/
    let formElement = document.getElementById('formVigilanciaTag');
    resetFormNoPasley(formElement);


    // 
    let list = document.getElementById('list_tag');
    let editar = document.getElementById('crear_editar_tag');
    let view = document.getElementById('view_tag');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_tag');


    //Elemento que recibe la animación
    let eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}

function fntCancelarReturnListTag() {

    let list = document.getElementById('list_tag');
    let editar = document.getElementById('crear_editar_tag');
    let view = document.getElementById('view_tag');
    list.style.display = "block";
    editar.style.display = "none";
    view.style.display = "none";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.list_tag');

    //Elemento que recibe la animación
    let eLDestino = list;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}


/*==================================================================
[ Guardar Registro ]*/

function setTag(e) {



    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnGuardarTag');
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

            if (responseObj.respuesta == "ok" || responseObj.respuesta == "warning") {

                /* Resetea el Form de registro para limpiar los datos */
                btnNuevaTag.click();

                /* Retorna y actualiza Listado de Tags */
                fntCancelarReturnListTag();
                tableTags.ajax.reload(function() {});

                /* Muestra Mensaje Modal y Reactiva el botón para guardar registro */
                let icon = "success";
                if (responseObj.respuesta == "warning") {
                    icon = "warning";
                }
                mensajeAlertaModal({
                    icon: icon,
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                    if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
                });

            } else {
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
    };
    var ajaxUrl = base_url + '/Tags/setTag';
    var formData = new FormData(formVigilanciaTag);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}


/*==================================================================
[ Vista Registro ]*/

function fntViewTag(btnElement) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-eye')

    let list = document.getElementById('list_tag');
    let editar = document.getElementById('crear_editar_tag');
    let view = document.getElementById('view_tag');
    list.style.display = "none";
    editar.style.display = "none";
    view.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.view_tag');

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
                cargarDatosTag(objDataResponse);
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
    let ajaxUrl = base_url + '/Tags/getTag/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

function cargarDatosTag(modelDataObj) {

    document.getElementById("tag_id").value = modelDataObj.data.id;

    /*-------------------------------------------
    [ Llena los datos del form view  ]*/
    document.getElementById("inputTag_read").innerHTML = modelDataObj.data.tag;
    document.getElementById("inputNombre_read").innerHTML = modelDataObj.data.residente;
    document.getElementById("inputDomicilio_read").innerHTML = modelDataObj.data.domicilio;
    // fecha_vigencia
    let estatus =
        modelDataObj.data.estatus == 1 ?
        '<span class = "badge badge-success">Activada</span>' :
        '<span class = "badge badge-danger">Desactivada</span>';
    document.getElementById("estatus_read").innerHTML = estatus;

    document.getElementById("inputFechaVigencia_read").innerHTML = modelDataObj.data.fecha_vigencia;

    document.getElementById("fechaRegistro_read").innerHTML = modelDataObj.data.updated_at;
    document.getElementById("usuarioRegistro_read").innerHTML = modelDataObj.data.usuario;

}


/*==================================================================
[ Eliminar Registro ]*/

function fntDeleteTag(btnElement) {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-trash-can')


    mensajeAlertaModal({
        icon: 'warning',
        title: iconMensajeWarning + ' ¡Advertencia!',
        text: "¿Está seguro de eliminar la tag seleccionada?",
        textButton: 'Sí'
    }).then(function(result) {
        if (result.dismiss == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-trash-can'); };
        if (result.si == true) { deleteTag(idRegistro) };
    });

}

function deleteTag(tag_id) {


    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            let responseObj = JSON.parse(xhttp.responseText);

            if (responseObj.respuesta == "ok") {
                tableTags.ajax.reload(function() {});
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

    let ajaxUrl = base_url + '/Tags/eliminarTag/';

    var formData = new FormData();
    formData.append('id', tag_id)
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}


/*==================================================================
[ DataTable ]*/

function setConfigTableTags(controlador, metodo) {

    configTableTags = {
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
                sheetName: 'Lista de Tags Registradas',
                extend: 'excel',
                messageTop: "",
                title: 'Amores - Lista de Tags Registradas',
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
            { "data": "usuario_reg" },
            { "data": "tag" },
            { "data": "nombre" },
            { "data": "domicilio" },
            { "data": "estatus" },
            { "data": "options" }
        ],
        'language': idioma_espanol

    };

}