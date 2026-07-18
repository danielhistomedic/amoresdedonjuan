/*==================================================================
[ Variables de Archivo ]*/

let tableTagsVigilancia;
let tableTagsVigilanciaElement = "#tableTagsVigilancia";
let tableTagsVigilanciaElementJS = "tableTagsVigilancia";
let configTableTags = "";



/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*==================================================================
    [ DataTable ]*/

    if (document.getElementById(tableTagsVigilanciaElementJS)) {

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableTagsVigilanciaElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableTagsVigilanciaElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableTagsVigilanciaElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableTagsVigilanciaElement).on('click', 'tbody tr>td', function() {

        });

    }


});

/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    /*-------------------------------------------
    [ Activa el menu horizontal (prinicipal) correspondiente del menu ]*/
    let pageUrl = base_url + "/administracion"
    fntActivarHorizontalMenu(pageUrl);


}, false)



/*==================================================================
[ Funciones de Operación ]*/

function loadResidente(data) {

    // *** Limpiar Buscador. ***
    document.getElementById("search_residente").value = "";

    // *** Asignar Id de Residente. ***
    document.getElementById("residente_id").value = data.id;

    let residente_indicaciones = document.getElementById('indicaciones_generales_visitas');
    if (data.indicaciones_generales_visitas == "") {
        residente_indicaciones.innerHTML = 'SIN INDICACIONES';
    } else {
        residente_indicaciones.innerHTML = data.indicaciones_generales_visitas;
    }


    /*-------------------------------------------
    [ Cargar Datos de Resumen de Cuenta ]*/
    getEstatusResidente(data.id, function(data_resumen) {

        let residente_estatus = document.getElementById('residente-estatus');

        if (data_resumen.estatus == 1) {

            residente_estatus.innerHTML = `<div class="alert alert-success alert-dismissible fade show mb-0" style="border-radius: 0;" role="alert">
                                                <div class="d-flex fs-17">
                                                    <p id="residente-nombre" class="text-dark">Nombre: <strong>${data.nombre}</strong></p>
                                                </div>
                                                <hr class="message-inner-separator">
                                                <div class="d-flex fs-17">
                                                    <p id="residente-domicilio" class="text-dark">Domicilio: <strong>${data.calle} ${data.numero}</strong></p>
                                                </div>
                                                <hr class="message-inner-separator">
                                                <div class="d-flex fs-17">
                                                    <p id="residente-telefono" class="text-dark">Teléfono: <strong>${data.telefono}</strong></p>
                                                </div>
                                                <hr class="message-inner-separator">
                                                <p class="fs-17">Estatus: <strong>ACTIVO</strong>.</p>
                                            </div>`;

        } else {

            residente_estatus.innerHTML = `<div class="alert alert-danger alert-dismissible fade show mb-0" style="border-radius: 0;" role="alert">
                                                <div class="d-flex fs-17">
                                                    <p id="residente-nombre" class="text-dark">Nombre: <strong>${data.nombre}</strong></p>
                                                </div>
                                                <hr class="message-inner-separator">
                                                <div class="d-flex fs-17">
                                                    <p id="residente-domicilio" class="text-dark">Domicilio: <strong>${data.calle} ${data.numero}</strong></p>
                                                </div>
                                                <hr class="message-inner-separator">
                                                <div class="d-flex fs-17">
                                                    <p id="residente-telefono" class="text-dark">Teléfono: <strong>${data.telefono}</strong></p>
                                                </div>
                                                <hr class="message-inner-separator">
                                                <p class="fs-17">Estatus: <strong>INACTIVO</strong>.</p>
                                            </div>`;

        }

        /*-------------------------------------------
        [ DataTable Inicializa - Lista de Tags asociados al residente ]*/
        setConfigTableTags('Tags', 'getTags/' + data.id);
        tableTagsVigilancia = $(tableTagsVigilanciaElement).DataTable(configTableTags);

    });

}

function getEstatusResidente(residente_id, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj.data);
        }
    };
    var ajaxUrl = base_url + '/EstatusResidente/getEstatusResidente/' + residente_id;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}



/*==================================================================
[ Imprimir ]*/

function vistaRecibo() {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble recarga de datos ]*/
    var btnGuardar = document.getElementById('btnImprimirInformeEC');
    agregarLoadingButtonGuardar(btnGuardar);

    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    var divLoading = document.getElementById('loading-resumen');
    divLoading.style.display = "flex";

    let fecha_ini = document.getElementById("inputFechaIniPeriodo").value;
    let fecha_fin = document.getElementById("inputFechaFinPeriodo").value;

    let fecha_ini_send = Formato_Fecha_yyyymmdd(fecha_ini);
    let fecha_fin_send = Formato_Fecha_yyyymmdd(fecha_fin);

    url = base_url + '/EstadoResultados/generarEstadoResultados/' + fecha_ini_send + '/' + fecha_fin_send;
    window.open(url, "Estado de Resultados (Ingresos y Egresos)", "fullscreen=yes");

    /* Reactiva el botón para mostrar Informe */
    removerClasesButtonGuardar(btnGuardar, loading);

    /* Remueve el Loading del Div */
    divLoading.style.display = "none";

}


/*==================================================================
[ DataTable ]*/

function setConfigTableTags(controlador, metodo) {

    configTableTags = {
        "aProcessing": true,
        "aServerSide": true,
        'destroy': true,
        "paging": false,
        "searching": false,
        'responsive': false,
        "info": false,
        "autoWidth": false,
        "iDisplayLength": 100,
        "order": [
            // [0, "desc"]
        ],
        "select": true,
        'ajax': {
            "url": " " + base_url + "/" + controlador + "/" + metodo + "",
            'dataSrc': ''
        },
        'columnDefs': [
            // { 'width': '80%', 'targets': 1 },
            // { 'width': '4%', 'targets': 11 },
            // { 'width': '6%', 'targets': '_all' }
        ],
        "columns": [
            { "data": "tag" },
            { "data": "estatus" }
        ],
        'language': idioma_espanol

    };

}


/*==================================================================
[ DataTable Filtros ]*/