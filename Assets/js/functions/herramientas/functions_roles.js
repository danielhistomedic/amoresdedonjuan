/*==================================================================
[ Variables ]*/

let tableRoles;
let tableRolesElement = "#tableRoles";
let tableRolesElementJS = "tableRoles";
let configTableRoles = "";

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento onsubmit al formulario de registro de Roles ]*/
    if (document.getElementById('formRol')) {
        let formRol = document.getElementById('formRol');
        formRol.addEventListener("submit", function(event) { setRol(event) });
    }

    /*-------------------------------------------
    [ Form - Agregar plugin de validación de campos ]*/
    // if (document.getElementById('formRol')) {
    //     $('#formRol').parsley();
    // }

    /*==================================================================
    [ Botons de Accion ]*/

    /*-------------------------------------------
    [ Agregar evento click a Nuevo Rol  ]*/
    if (document.getElementById("btnCrear_Rol")) {
        let btnElement = document.getElementById('btnCrear_Rol');
        btnElement.onclick = function() { fntNuevoRol() };
    }

    /*-------------------------------------------
    [ Agregar evento click a Editar Roles  ]*/

    if (document.getElementById("btnEditar_Rol")) {
        let btnElement = document.getElementById('btnEditar_Rol');
        btnElement.onclick = function() { fntEditRol_Form() };
    }

    /*-------------------------------------------
     [ Agregar evento click a Cancelar Edicion/Creación de Rol  ]*/

    if (document.querySelector(".btnCancelar_Rol")) {
        let btnElement = document.querySelectorAll('.btnCancelar_Rol');
        for (let index = 0; index < btnElement.length; index++) {
            const element = btnElement[index];
            element.onclick = function() { fntCancelarReturnListRol() };
        }
    }



    /*==================================================================
    [ DataTable ]*/


    /*-------------------------------------------
    [ DataTable Inicializa ]*/
    setConfigTableRoles('Roles', 'getRoles');
    tableRoles = $(tableRolesElement).DataTable(configTableRoles);


    /*-------------------------------------------
    [ DataTable - Se ejecuta después de inicializar la tabla ]*/
    $(tableRolesElement).on('init.dt', function() {

        ReDesignButonExcel();

        $('.dataTables_wrapper select').select2({
            language: "es",
            minimumResultsForSearch: Infinity
        });

        let thead = document.querySelector(tableRolesElement + ' thead');
        thead.classList.remove("bg-secondary");
        thead.classList.add("bg-thead");

        //Valida si se activa el botón excel .
        validaPermisoExportar(menu);

    });


    /*-------------------------------------------
    [ Agregar evento click a Colvis de Datatable para agregar icono para restaurar las columnas ]*/
    if (document.querySelector(".buttons-colvis")) {
        let btnElement = document.querySelector('.buttons-colvis');
        btnElement.onclick = function() {

            setTimeout(() => {
                if (document.querySelector("div.dt-button-collection .buttons-colvisRestore span i")) {
                    let colvis_icon = document.querySelector('div.dt-button-collection .buttons-colvisRestore span i');
                    colvis_icon.classList.remove("far");
                    colvis_icon.classList.add("fa-regular");
                }
            }, 500);

        };
    }

    /*-------------------------------------------
    [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
    $(tableRolesElement).on('draw.dt', function() {

    });

    /*-------------------------------------------
    [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
    $(tableRolesElement).on('click', 'tbody tr>td', function() {

    });

    /*==================================================================
    [ Otros Eventos ]*/

    /*-------------------------------------------
    [ Agregar evento click para Mostrar Mas/Mostrar Menos ]*/
    if (document.querySelector(".mostrar_mas_menos_rol")) {
        let more_less = document.querySelector(".mostrar_mas_menos_rol");
        let mostrar_mas = document.getElementById("mostrar_mas_rol");
        let mostrar_menos = document.getElementById("mostrar_menos_rol");
        more_less.addEventListener("click", function() {
            mostrar_mas.classList.toggle("d-none");
            mostrar_menos.classList.toggle("d-none");
        });
    }


});

/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    /*-------------------------------------------
    [ Funciones Init ]*/

    /*-------------------------------------------
    [ Funciones Fill Selects ]*/


    /*-------------------------------------------
    [ Activa el menu horizontal (prinicipal) correspondiente del sidemenu ]*/
    let pageUrl = base_url + "/herramientas"
    fntActivarHorizontalMenu(pageUrl);


}, false)


/*==================================================================
[ Funciones de Eventos ]*/


/*==================================================================
[ Funciones de Carga Inicial ]*/


/*==================================================================
[ Nuevo Registro ]*/

function fntNuevoRol() {


    /*-------------------------------------------
    [ Limpiar Form ]*/
    let formElement = document.getElementById('formRol');
    document.getElementById("inputIdRol").value = '';
    resetFormNoPasley(formElement);

    let list = document.getElementById('list_htas_roles');
    let editar = document.getElementById('crear_editar_htas_roles');
    let view = document.getElementById('view_htas_roles');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";


    //Asignar Titulo de Encabezado
    // document.getElementById("titulo-editar").innerHTML = "Registrar Nuevo Rol.";
    // document.getElementById("titulo-editar-icon").innerHTML = '<i class="fa-regular fa-file-plus icon-size float-start text-secondary text-secondary-shadow me-2"></i>';

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_htas_roles');

    //Elemento que recibe la animación
    let eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);


}

/*==================================================================
[ Guardar Registro ]*/

function setRol(e) {


    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    let btnGuardar = document.getElementById('btnGuardar_Rol');
    agregarLoadingButtonGuardar(btnGuardar);


    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();


    /*-------------------------------------------
    [ Valida Inputs que no estpen vacíos. ]*/
    let check = true;
    let input = document.querySelectorAll("input.inputForm100");

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
            text: 'Debe llenar los campos requeridos.',
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
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let responseObj = JSON.parse(xhttp.responseText);
            if (responseObj.respuesta == "ok") {
                tableRoles.ajax.reload(function() {});
                fntCancelarReturnListRol();
                mensajeAlertaModal({
                    icon: 'success',
                    timer: responseObj.tiempo,
                    title: iconMensajeSuccess + ' ¡Atención!',
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
    let ajaxUrl = base_url + '/Roles/setRol';
    let formData = new FormData(formRol);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}


/*==================================================================
[ Editar Registro ]*/

function fntCancelarReturnListRol() {

    let list = document.getElementById('list_htas_roles');
    let editar = document.getElementById('crear_editar_htas_roles');
    let view = document.getElementById('view_htas_roles');
    list.style.display = "block";
    editar.style.display = "none";
    view.style.display = "none";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.list_htas_roles');

    //Elemento que recibe la animación
    let eLDestino = list;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}

function fntEditRol(btnElement) {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-pencil-alt')


    //Asignar Titulo de Encabezado
    // document.getElementById("titulo-editar").innerHTML = "Editar Rol Seleccionado.";
    // document.getElementById("titulo-editar-icon").innerHTML = '<i class="fa-regular fa-file-pen icon-size float-start text-secondary text-secondary-shadow me-2"></i>';

    //-----------------------------------
    //[ Animación de Paneles ]

    let list = document.getElementById('list_htas_roles');
    let editar = document.getElementById('crear_editar_htas_roles');
    let view = document.getElementById('view_htas_roles');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_htas_roles');

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
                cargarDatosEditRol(responseObj);


                restablecerButtonOpcionesDataTable(btnElement, 'fa-pencil-alt');

            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-pencil-alt'); };
                    if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-pencil-alt'); }
                });
            }

        }

    };
    let ajaxUrl = base_url + '/Roles/getRol/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

function cargarDatosEditRol(modelDataObj) {

    /*-------------------------------------------
    [ Obtiene datos de Form  ]*/
    document.getElementById("inputIdRol").value = modelDataObj.data.id;
    document.getElementById("inputNombreRol").value = modelDataObj.data.name;
    document.getElementById("inputDescripcionRol").value = modelDataObj.data.descripcion;

}

function fntEditRol_Form() {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = document.getElementById("inputIdRol").value;
    console.log('idRegistro = ' + idRegistro);
    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    let btnGuardar = document.getElementById('btnEditar_Rol');
    agregarLoadingButtonGuardar(btnGuardar);


    //Asignar Titulo de Encabezado
    // document.getElementById("titulo-editar").innerHTML = "Editar Rol Seleccionado.";
    // document.getElementById("titulo-editar-icon").innerHTML = '<i class="fa-regular fa-file-pen icon-size float-start text-secondary text-secondary-shadow me-2"></i>';

    //-----------------------------------
    //[ Animación de Paneles ]

    let list = document.getElementById('list_htas_roles');
    let editar = document.getElementById('crear_editar_htas_roles');
    let view = document.getElementById('view_htas_roles');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_htas_roles');

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
                cargarDatosEditRol(responseObj);

                removerClasesButtonGuardar(btnGuardar, loading);

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
    let ajaxUrl = base_url + '/Roles/getRol/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}


/*==================================================================
[ Reactivar Registro ]*/

function fntActiveRol(btnElement) {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-check-double')

    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            let responseObj = JSON.parse(xhttp.responseText);

            if (responseObj.respuesta == "ok") {
                tableRoles.ajax.reload(function() {});
                mensajeAlertaModal({
                    icon: 'success',
                    timer: responseObj.tiempo,
                    title: iconMensajeSuccess + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-check-double'); };
                    if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-check-double'); }
                });
            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-check-double'); };
                    if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-check-double'); }
                });
            }
        }
    };

    let ajaxUrl = base_url + '/Roles/setEstatusRol/';
    let strData = "";
    strData += "id=" + idRegistro;
    strData += "&estatus=1";
    xhttp.open("POST", ajaxUrl, true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(strData);

}



/*==================================================================
[ Eliminar Registro ]*/

function fntDeleteRol(btnElement) {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-trash-can')

    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            let responseObj = JSON.parse(xhttp.responseText);

            if (responseObj.respuesta == "ok") {
                tableRoles.ajax.reload(function() {});
                mensajeAlertaModal({
                    icon: 'success',
                    timer: responseObj.tiempo,
                    title: iconMensajeSuccess + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) {};
                    if (result.dismissUser == true) {}
                });
            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-trash-can'); };
                    if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-trash-can'); }
                });
            }
        }
    };

    let ajaxUrl = base_url + '/Roles/setEstatusRol/';
    let strData = "";
    strData += "id=" + idRegistro;
    strData += "&estatus=0";
    xhttp.open("POST", ajaxUrl, true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send(strData);

}


/*==================================================================
[ Vista Registro ]*/

function fntViewRol(btnElement) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-eye')

    let list = document.getElementById('list_htas_roles');
    let editar = document.getElementById('crear_editar_htas_roles');
    let view = document.getElementById('view_htas_roles');
    list.style.display = "none";
    editar.style.display = "none";
    view.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.view_htas_roles');

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
                cargarDatosRol(objDataResponse);
                restablecerButtonOpcionesDataTable(btnElement, 'fa-eye');
            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-eye'); };
                    if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-eye'); }
                });

            }

        }

    };
    let ajaxUrl = base_url + '/Roles/getRol/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

function cargarDatosRol(modelDataObj) {

    document.getElementById("inputIdRol").value = modelDataObj.data.id;

    /*-------------------------------------------
    [ Llena lso datos del form view  ]*/
    document.getElementById("inputNombreRol_read").innerHTML = modelDataObj.data.name;
    document.getElementById("inputDescripcionRol_read").innerHTML = modelDataObj.data.descripcion;

    let estatus =
        modelDataObj.data.activo == 1 ?
        '<span class = "badge badge-success">Activo</span>' :
        '<span class = "badge badge-danger">Inactivo</span>';
    document.getElementById("estatus_read").innerHTML = estatus;

    document.getElementById("fechaRegistro_read").innerHTML = modelDataObj.data.updated_at;
    document.getElementById("usuarioRegistro_read").innerHTML = modelDataObj.data.usuario;

}

/*==================================================================
[ Permisos de Rol ]*/

function fntPermisosRol(btnElement) {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    let idRol = btnElement.getAttribute("data-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-key-skeleton')


    /*-------------------------------------------
     [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {

            // --- Requiere once de php para cargar el Modal de Permisos ---
            document.getElementById('loadModalPermisos').innerHTML = xhttp.responseText;

            // --- Instanciar el modal ---
            let modalEl = document.querySelector('.modalPermisos');
            let myModalPermisos = new bootstrap.Modal(modalEl, {
                keyboard: false
            })

            // --- Ejecuta el toggle para mostrar el Modal ---
            myModalPermisos.toggle();

            // --- Crear evento para Guardar los cambios en los permisos ---
            document.getElementById('formPermisos').addEventListener('submit', setPermisos, true);

            // --- Restablecer Icon ---
            restablecerButtonOpcionesDataTable(btnElement, 'fa-key-skeleton');
        }

    };
    let ajaxUrl = base_url + '/Permisos/getPermisosRol/' + idRol;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

function setPermisos(e) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnActionFormPermisos');
    agregarLoadingButtonGuardar(btnGuardar);


    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();

    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let responseObj = JSON.parse(xhttp.responseText);
            if (responseObj.respuesta == 'ok') {
                mensajeAlertaModal({
                    icon: 'success',
                    timer: 4000,
                    title: iconMensajeSuccess + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                    if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
                });
            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: 4000,
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
    let formElement = document.getElementById("formPermisos");
    let formData = new FormData(formElement);
    let ajaxUrl = base_url + '/Permisos/setPermisos';
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}


/*==================================================================
[ DataTable ]*/

function setConfigTableRoles(controlador, metodo) {

    configTableRoles = {
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
                sheetName: 'Roles de Accesso',
                extend: 'excel',
                messageTop: "",
                title: 'Histoclin - Lista de Roles',
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
            { "data": "name" },
            { "data": "descripcion" },
            { "data": "activo" },
            { "data": "options" }
        ],
        'language': idioma_espanol

    };

}

/*==================================================================
[ DataTable Filtros ]*/