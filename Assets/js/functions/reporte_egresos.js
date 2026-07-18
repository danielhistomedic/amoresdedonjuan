/*==================================================================
[ Variables de Archivo ]*/

let tableReporteEgresos;
let tableReporteEgresosElement = "#tableReporteEgresos";
let configTableReporteEgresos = "";


/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {


    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Residente] */
    if (document.getElementById('formMostrarReporteEgresos')) {
        var formMostrarReporteEgresos = document.getElementById('formMostrarReporteEgresos');
        formMostrarReporteEgresos.addEventListener("submit", function(event) { getReporteEgresos(event) });
    }


    /*==================================================================
    [ Botons de Accion ]*/
    if (document.getElementById('btnImprimirReporteEgresos')) {
        var btnImprimirReporteEgresos = document.getElementById('btnImprimirReporteEgresos');
        btnImprimirReporteEgresos.onclick = function() { vistaReporteIngresos() };
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

    if (document.querySelector(tableReporteEgresosElement)) {

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableReporteEgresosElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableReporteEgresosElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead-ingresos");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableReporteEgresosElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableReporteEgresosElement).on('click', 'tbody tr>td', function() {

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
[ Mostrar Informe ]*/

function getReporteEgresos(e) {

    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();


    /*-------------------------------------------
    [ Limpiar Cuadro Resumen ]*/
    document.getElementById("lblTotalEgresos").innerHTML = '0.00';
    // document.getElementById("lblCantidadRecibosExpedidos").innerHTML = '0.00';
    // document.getElementById("lblSaldoUtilizado").innerHTML = '0.00';
    // document.getElementById("lblImporteDejadoACuenta").innerHTML = '0.00';


    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble recarga de datos ]*/
    var btnGuardar = document.getElementById('btnMostarReporteEgresos');
    agregarLoadingButtonGuardar(btnGuardar);



    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    var divLoading = document.getElementById('loading-resumen');
    divLoading.style.display = "flex";


    let fecha_ini = document.getElementById("inputFechaIniPeriodo").value;
    let fecha_fin = document.getElementById("inputFechaFinPeriodo").value;

    let fecha_ini_send = Formato_Fecha_yyyymmdd(fecha_ini);
    let fecha_fin_send = Formato_Fecha_yyyymmdd(fecha_fin);

    /*-------------------------------------------
    [ DataTable Inicializa ]*/
    setConfigTableReporteEgresos('ReporteEgresos', 'getEgresos/' + fecha_ini_send + '/' + fecha_fin_send);
    tableReporteEgresos = $(tableReporteEgresosElement).DataTable(configtableReporteEgresos);


    let importe_total_egresos = 0;
    // let cantidad_recibos_expedidos = 0;
    // let saldo_utilizado = 0;
    // let importe_dejado_a_cuenta = 0;

    getResumenReporteEgresos(fecha_ini_send, fecha_fin_send, function(data) {

        importe_total_egresos = data.importe_total_egresos;
        // cantidad_recibos_expedidos = data.cantidad_recibos_expedidos;
        // saldo_utilizado = data.saldo_utilizado;
        // importe_dejado_a_cuenta = data.importe_dejado_a_cuenta;

        document.getElementById("lblTotalEgresos").innerHTML = Formato_Moneda(importe_total_egresos);
        // document.getElementById("lblCantidadRecibosExpedidos").innerHTML = cantidad_recibos_expedidos;
        // document.getElementById("lblSaldoUtilizado").innerHTML = Formato_Moneda(saldo_utilizado);
        // document.getElementById("lblImporteDejadoACuenta").innerHTML = Formato_Moneda(importe_dejado_a_cuenta);

        /* Remueve el Loading del Div */
        divLoading.style.display = "none";

        /* Reactiva el botón para mostrar Informe */
        removerClasesButtonGuardar(btnGuardar, loading);


    });

}


function getResumenReporteEgresos(fecha_ini, fecha_fin, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            console.log(dataObj);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/ReporteEgresos/getResumenReporteEgresos/' + fecha_ini + '/' + fecha_fin;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}




/*==================================================================
[ Imprimir ]*/

function vistaReporteIngresos() {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble recarga de datos ]*/
    var btnGuardar = document.getElementById('btnImprimirReporteEgresos');
    agregarLoadingButtonGuardar(btnGuardar);

    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    var divLoading = document.getElementById('loading-resumen');
    divLoading.style.display = "flex";


    let fecha_ini = document.getElementById("inputFechaIniPeriodo").value;
    let fecha_fin = document.getElementById("inputFechaFinPeriodo").value;

    let fecha_ini_send = Formato_Fecha_yyyymmdd(fecha_ini);
    let fecha_fin_send = Formato_Fecha_yyyymmdd(fecha_fin);

    url = base_url + '/ReporteEgresos/generarPDFReporteEgresos/' + fecha_ini_send + '/' + fecha_fin_send;
    window.open(url, "Reporte de Egresos", "fullscreen=yes");

    /* Reactiva el botón para mostrar Informe */
    removerClasesButtonGuardar(btnGuardar, loading);

    /* Remueve el Loading del Div */
    divLoading.style.display = "none";

}


/*==================================================================
[ DataTable ]*/

function setConfigTableReporteEgresos(controlador, metodo) {

    configtableReporteEgresos = {
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
                sheetName: 'Informe de Ingresos',
                extend: 'excel',
                messageTop: "",
                title: 'Amores - Informe de Estado de Resultados (Ingresos)',
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
            { "data": "archivo" }
        ],
        'language': idioma_espanol

    };

}

/*==================================================================
[ DataTable Filtros ]*/


/*-------------------------------------------
   [ DataTable Inicializa - Recibos asociados al residente ]*/
// setConfigtableReporteEgresos('Recibos', 'getRecibos/' + data.id);
// tableRecibos = $(tableRecibosElement).DataTable(configtableReporteEgresos);