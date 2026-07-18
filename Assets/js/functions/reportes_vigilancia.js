/*==================================================================
[ Variables ]*/

let tableReportesVigilancia;
let tableReportesVigilanciaElement = "#tableReportesVigilancia";
let tableReportesVigilanciaElementJS = "tableReportesVigilancia";
let configtableReportesVigilancia = "";

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento onsubmit ala todos los formularios de la pagina ]*/
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');

    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.add('was-validated');
            } else {
                setReporteVigilancia(event);
            }
        }, false);
    });

    /*-------------------------------------------
    [ Form - Agregar evento onsubmit al formulario de registro de Reporte ]*/
    // if (document.getElementById('formReporteVigilancia')) {
    //     let formReporteVigilancia = document.getElementById('formReporteVigilancia');
    //     formReporteVigilancia.addEventListener("submit", function(event) { setReporteVigilancia(event) });
    // }

    /*==================================================================
    [ Botons de Accion ]*/

    /*-------------------------------------------
    [ Agregar evento click a Nuevo Reporte  ]*/
    if (document.getElementById("btnCrear_ReporteVigilancia")) {
        let btnElement = document.getElementById('btnCrear_ReporteVigilancia');
        btnElement.onclick = function() { fntNuevoReporteVigilancia() };
    }

    /*-------------------------------------------
     [ Agregar evento click a Cancelar Edicion/Creación de Rol  ]*/

    if (document.querySelector(".btnCancelar_Reporte")) {
        let btnElement = document.querySelectorAll('.btnCancelar_Reporte');
        for (let index = 0; index < btnElement.length; index++) {
            const element = btnElement[index];
            element.onclick = function() { fntCancelarReturnListReporteVigilancia() };
        }
    }

    /*==================================================================
    [ DataTable ]*/

    /*-------------------------------------------
    [ DataTable Inicializa ]*/
    setConfigtableReportesVigilancia('ReportesVigilancia', 'getReportes');
    tableReportesVigilancia = $(tableReportesVigilanciaElement).DataTable(configtableReportesVigilancia);


    /*-------------------------------------------
    [ DataTable - Se ejecuta después de inicializar la tabla ]*/
    $(tableReportesVigilanciaElement).on('init.dt', function() {

        ReDesignButonExcel();

        $('.dataTables_wrapper select').select2({
            language: "es",
            minimumResultsForSearch: Infinity
        });

        let thead = document.querySelector(tableReportesVigilanciaElement + ' thead');
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
    $(tableReportesVigilanciaElement).on('draw.dt', function() {

    });

    /*-------------------------------------------
    [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
    $(tableReportesVigilanciaElement).on('click', 'tbody tr>td', function() {

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
    [ Activa el menu horizontal (prinicipal) correspondiente del sidemenu ]*/
    let pageUrl = base_url
    fntActivarHorizontalMenu(pageUrl);


}, false)


/*==================================================================
[ Nuevo Registro ]*/

function fntNuevoReporteVigilancia() {


    /*-------------------------------------------
    [ Limpiar Form ]*/
    let formElement = document.getElementById('formReporteVigilancia');
    document.getElementById("reportes_vigilancia_id").value = '';
    resetFormNew(formElement, 'formReporteVigilancia');

    let list = document.getElementById('list_reportes_vigilancia');
    let editar = document.getElementById('crear_editar_reportes_vigilancia');
    let view = document.getElementById('view_reportes_vigilancia');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_reportes_vigilancia');

    //Elemento que recibe la animación
    let eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);


}

/*==================================================================
[ Guardar Registro ]*/

function setReporteVigilancia(e) {


    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    let btnGuardar = document.getElementById('btnGuardar_ReporteVigilancia');
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
            if (responseObj.respuesta == "ok") {
                tableReportesVigilancia.ajax.reload(function() {});
                fntCancelarReturnListReporteVigilancia();
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
    let ajaxUrl = base_url + '/ReportesVigilancia/setReporteVigilancia';
    let formData = new FormData(formReporteVigilancia);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}


/*==================================================================
[ Editar Registro ]*/

function fntCancelarReturnListReporteVigilancia() {

    let list = document.getElementById('list_reportes_vigilancia');
    let editar = document.getElementById('crear_editar_reportes_vigilancia');
    let view = document.getElementById('view_reportes_vigilancia');
    list.style.display = "block";
    editar.style.display = "none";
    view.style.display = "none";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.list_reportes_vigilancia');

    //Elemento que recibe la animación
    let eLDestino = list;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}


/*==================================================================
[ Vista Registro ]*/

function fntViewReporteVigilancia(btnElement) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-eye')

    let list = document.getElementById('list_reportes_vigilancia');
    let editar = document.getElementById('crear_editar_reportes_vigilancia');
    let view = document.getElementById('view_reportes_vigilancia');
    list.style.display = "none";
    editar.style.display = "none";
    view.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.view_reportes_vigilancia');

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
                cargarDatosReporteVigilancia(objDataResponse);
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

function cargarDatosReporteVigilancia(modelDataObj) {

    document.getElementById("reportes_vigilancia_id").value = modelDataObj.data.id;

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
[ DataTable ]*/

function setConfigtableReportesVigilancia(controlador, metodo) {

    configtableReportesVigilancia = {
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
                sheetName: 'Reportes',
                extend: 'excel',
                messageTop: "",
                title: 'Histoclin - Reportes de Vigilancia',
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
            { className: "text-center p-2", targets: [0, 1, 2, 3, 4] }
        ],
        "columns": [
            { "data": "asunto" },
            { "data": "reporte" },
            { "data": "estatus" },
            { "data": "archivo" },
            { "data": "options" }
        ],
        'language': idioma_espanol

    };

}

/*==================================================================
[ DataTable Filtros ]*/