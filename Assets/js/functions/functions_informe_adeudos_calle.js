/*==================================================================
[ Variables de Archivo ]*/

let tableAdeduosCalle;
let tableAdeudosCalleElement = "#tableAdeudosCalle";
let configTableAdeudosCalle = "";
var divLoading = document.getElementById('loading-resumen');

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {


    /*==================================================================
    [ Form ]*/


    /*==================================================================
    [ Botons de Accion ]*/
    if (document.getElementById('btnImprimirInformeAdeudosCalle')) {
        var btnImprimirInformeAdeudosCalle = document.getElementById('btnImprimirInformeAdeudosCalle');
        btnImprimirInformeAdeudosCalle.onclick = function() { vistaInformeAdeudosCalle() };
    }

    if (document.getElementById('btnMostarReporteAdeudosCalle')) {
        var btnMostarReporteAdeudosCalle = document.getElementById('btnMostarReporteAdeudosCalle');
        btnMostarReporteAdeudosCalle.onclick = function() { refrescarReporte() };
    }




    /*==================================================================
    [ DataTable ]*/

    /*-------------------------------------------
          [ Agregar evento click a Colvis de Datatable para agregar icono para restaurar las columnas ]*/
    if (document.querySelector(".buttons-colvis")) {
        var btnElement = document.querySelector('.buttons-colvis');
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

    if (document.querySelector(tableAdeudosCalleElement)) {

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableAdeudosCalleElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableAdeudosCalleElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de terminar ajax ]*/
        $(tableAdeudosCalleElement).on('xhr.dt', function(e, settings, json, xhr) {

            divLoading.style.display = "none";

            /*-------------------------------------------
             [ Ajax ]*/
            let calle_id = document.getElementById('calle_id').value;
            getFunctionData('InformeAdeudosCalle', 'getResumenAdeudosCalle', calle_id, function(responseObj) {
                if (responseObj.respuesta == "ok") {
                    document.getElementById('lblImporteAdeudoCalle').innerHTML = Formato_Moneda(responseObj.data.importe_adeudo);
                    document.getElementById('lblTotalInactivos').innerHTML = responseObj.data.total_inactivos;
                    document.getElementById('lblTotalActivos').innerHTML = responseObj.data.total_activos;
                    document.getElementById('lblTotalActivosConvenio').innerHTML = responseObj.data.total_activos_convenio;
                } else {
                    document.getElementById('lblImporteAdeudoCalle').innerHTML = '$ 0.00';
                    document.getElementById('lblTotalInactivos').innerHTML = '0';
                    document.getElementById('lblTotalActivos').innerHTML = '0';
                    document.getElementById('lblTotalActivosConvenio').innerHTML = '0';
                }
            });

        })

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableAdeudosCalleElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableAdeudosCalleElement).on('click', 'tbody tr>td', function() {

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

function loadCalle(data) {

    // *** Limpiar Buscador. ***
    document.getElementById("search_calle").value = "";

    // *** Asignar Id de Residente. ***
    document.getElementById("calle_id").value = data.id;

    // *** Cargar Datos de Calle Seleccionada. ***
    document.getElementById("calle_seleccionada").innerHTML = data.calle;


    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    divLoading.style.display = "flex";

    /*-------------------------------------------
    [ DataTable Inicializa - Lista de Residentes asociados a la Calle Seleccionada ]*/
    setConfigTableAdeudosCalle('InformeAdeudosCalle', 'getAdeudosCalle/' + data.id);
    tableAdeduosCalle = $(tableAdeudosCalleElement).DataTable(configTableAdeudosCalle);

}

function refrescarReporte() {

    // *** Limpiar Buscador. ***

    let calle_id = document.getElementById("calle_id").value;

    if (calle_id == "") {
        alert('debe seleccioanr una calle');
        return;
    }

    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    divLoading.style.display = "flex";

    /*-------------------------------------------
    [ DataTable Inicializa - Lista de Residentes asociados a la Calle Seleccionada ]*/
    setConfigTableAdeudosCalle('InformeAdeudosCalle', 'getAdeudosCalle/' + calle_id);
    tableAdeduosCalle = $(tableAdeudosCalleElement).DataTable(configTableAdeudosCalle);

}



/*==================================================================
[ Imprimir ]*/

function vistaInformeAdeudosCalle() {

    let calle_id = document.getElementById("calle_id").value;

    if (calle_id == "") {
        alert('debe seleccioanr una calle');
        return;
    }
    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble recarga de datos ]*/
    var btnGuardar = document.getElementById('btnImprimirInformeAdeudosCalle');
    agregarLoadingButtonGuardar(btnGuardar);

    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    var divLoading = document.getElementById('loading-resumen');
    divLoading.style.display = "flex";


    url = base_url + '/InformeAdeudosCalle/generarAdeudosCalle/' + calle_id;
    window.open(url, "Adeudos por Calle", "fullscreen=yes");

    /* Reactiva el botón para mostrar Informe */
    removerClasesButtonGuardar(btnGuardar, loading);

    /* Remueve el Loading del Div */
    divLoading.style.display = "none";

}


/*==================================================================
[ DataTable ]*/

function setConfigTableAdeudosCalle(controlador, metodo) {

    configTableAdeudosCalle = {
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
                sheetName: 'Estado de Cuenta Residente',
                extend: 'excel',
                messageTop: "",
                title: 'Amores - Informe de Estado de Cuenta Residente',
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
            { "data": "calle" },
            { "data": "domicilio" },
            { "data": "estatus" },
            { "data": "tags_registradas" },
            { "data": "total_adeudo" }
        ],
        'language': idioma_espanol

    };

}


{
    /* <th class="font-weight-bold text-center">Calle</th>
    <th class="font-weight-bold text-center">Domicilio</th>
    <th class="font-weight-bold text-center">Estatus</th>
    <th class="font-weight-bold text-center">Tags Registradas</th>
    <th class="font-weight-bold text-center">Total Adeudo</th> */
}



/*==================================================================
[ DataTable Filtros ]*/


/*-------------------------------------------
   [ DataTable Inicializa - Recibos asociados al residente ]*/
// setConfigTableAdeudosCalle('Recibos', 'getRecibos/' + data.id);
// tableRecibos = $(tableRecibosElement).DataTable(configTableAdeudosCalle);