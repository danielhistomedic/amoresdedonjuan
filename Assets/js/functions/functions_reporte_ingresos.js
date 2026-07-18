/*==================================================================
[ Variables de Archivo ]*/

let tableReporteIngresos;
let tableReporteIngresosElement = "#tableReporteIngresos";
let configTableReporteIngresos = "";


let tableRecibosAnteriores;
let tableRecibosAnterioresElement = "#tableRecibosAnteriores";
let configTableRecibosAnteriores = "";


/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function (event) {


    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Residente] */
    if (document.getElementById('formMostrarReporteIngresos')) {
        var formMostrarReporteIngresos = document.getElementById('formMostrarReporteIngresos');
        formMostrarReporteIngresos.addEventListener("submit", function (event) { getReporteIngresos(event) });
    }


    /*==================================================================
    [ Botons de Accion ]*/
    if (document.getElementById('btnImprimirReporteIngresos')) {
        var btnImprimirReporteIngresos = document.getElementById('btnImprimirReporteIngresos');
        btnImprimirReporteIngresos.onclick = function () { vistaReporteIngresos() };
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

    if (document.querySelector(tableReporteIngresosElement)) {

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableReporteIngresosElement).on('init.dt', function () {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableReporteIngresosElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead-ingresos");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableReporteIngresosElement).on('draw.dt', function () {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableReporteIngresosElement).on('click', 'tbody tr>td', function () {

        });
    }

    if (document.querySelector(tableRecibosAnterioresElement)) {

        $(tableRecibosAnterioresElement).on('init.dt', function () {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableRecibosAnterioresElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableRecibosAnterioresElement).on('draw.dt', function () {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableRecibosAnterioresElement).on('click', 'tbody tr>td', function () {

        });

    }


});

/*==================================================================
[ Window ]*/

window.addEventListener('load', function () {

    /*-------------------------------------------
    [ Activa el menu horizontal (prinicipal) correspondiente del menu ]*/
    let pageUrl = base_url + "/administracion"
    fntActivarHorizontalMenu(pageUrl);


}, false)


/*==================================================================
[ Mostrar Informe ]*/

function getReporteIngresos(e) {

    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();


    /*-------------------------------------------
    [ Limpiar Cuadro Resumen ]*/
    document.getElementById("lblTotalIngresos").innerHTML = '0.00';
    document.getElementById("lblCantidadRecibosExpedidos").innerHTML = '0.00';
    document.getElementById("lblSaldoUtilizado").innerHTML = '0.00';
    document.getElementById("lblImporteDejadoACuenta").innerHTML = '0.00';


    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble recarga de datos ]*/
    var btnGuardar = document.getElementById('btnMostarReporteIngresos');
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
    setConfigTableReporteIngresos('ReporteIngresos', 'getIngresos/' + fecha_ini_send + '/' + fecha_fin_send);
    tableReporteIngresos = $(tableReporteIngresosElement).DataTable(configTableReporteIngresos);

    setConfigTableRecibosAnteriores('ReporteIngresos', 'getRecibosAnteriores/' + fecha_ini_send + '/' + fecha_fin_send);
    tableRecibosAnteriores = $(tableRecibosAnterioresElement).DataTable(configTableRecibosAnteriores);


    let importe_total_ingresos = 0;
    let cantidad_recibos_expedidos = 0;
    let saldo_utilizado = 0;
    let importe_dejado_a_cuenta = 0;

    getResumenReporteIngresos(fecha_ini_send, fecha_fin_send, function (data) {

        importe_total_ingresos = data.importe_total_ingresos;
        cantidad_recibos_expedidos = data.cantidad_recibos_expedidos;
        saldo_utilizado = data.saldo_utilizado;
        importe_dejado_a_cuenta = data.importe_dejado_a_cuenta;

        document.getElementById("lblTotalIngresos").innerHTML = Formato_Moneda(importe_total_ingresos);
        document.getElementById("lblCantidadRecibosExpedidos").innerHTML = cantidad_recibos_expedidos;
        document.getElementById("lblSaldoUtilizado").innerHTML = Formato_Moneda(saldo_utilizado);
        document.getElementById("lblImporteDejadoACuenta").innerHTML = Formato_Moneda(importe_dejado_a_cuenta);


        // Desglose de Ingresos
        let desglose_html = "<hr class='m-1'>";
        let importe = 0;
        let desglose = [];
        desglose = data.importe_total_ingresos_desglose;
        for (let index = 0; index < desglose.length; index++) {
            const element = desglose[index];
            importe = element.importe;
            importe = Formato_Moneda(importe);
            desglose_html += `<li class="list-group-item border-0 fs-12 p-1 fst-italic"><i class="fa fa-check text-info fs-12" aria-hidden="true"></i> <span class="text-primary">${element.concepto}: </span><span class="text-muted"><strong>${importe}</strong></span> </li>`;
        }

        document.getElementById("desglose_ingresos").innerHTML = desglose_html;
        //         importe_total_ingresos_desglose: Array(2)
        // 0:text-muted
        // concepto: "MANTENIMIENTO"
        // importe: "99071"

        console.log(data.importe_total_ingresos_desglose);

        // <!-- <li class="list-group-item border-0 fs-11 p-1 fst-italic"><i class="fa fa-check text-info fs-12" aria-hidden="true"></i> <span class="text-primary">MANTENIMIENTO: </span><span class=""><strong>$ 23,985.00</strong></span> </li>
        // <li class="list-group-item border-0 fs-11 p-1 fst-italic"><i class="fa fa-check text-info fs-12" aria-hidden="true"></i> <span class="text-primary">COMPRA DE TAG: </span><span class=""><strong>$ 1,050.00</strong></span> </li>
        // <li class="list-group-item border-0 fs-11 p-1 fst-italic"><i class="fa fa-check text-info fs-12" aria-hidden="true"></i> <span class="text-primary">REPOSICIÓN DE TAG: </span><span class=""><strong>$ 60.00</strong></span> </li>
        // <li class="list-group-item border-0 fs-11 p-1 fst-italic"><i class="fa fa-check text-info fs-12" aria-hidden="true"></i> <span class="text-primary">OTROS INGRESOS: </span><span class=""><strong>$ 85.00</strong></span> </li> -->


        /* Remueve el Loading del Div */
        divLoading.style.display = "none";

        /* Reactiva el botón para mostrar Informe */
        removerClasesButtonGuardar(btnGuardar, loading);


    });

}


function getResumenReporteIngresos(fecha_ini, fecha_fin, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            console.log(dataObj);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/ReporteIngresos/getResumenReporteIngresos/' + fecha_ini + '/' + fecha_fin;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}




/*==================================================================
[ Imprimir ]*/

function vistaReporteIngresos() {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble recarga de datos ]*/
    var btnGuardar = document.getElementById('btnImprimirReporteIngresos');
    agregarLoadingButtonGuardar(btnGuardar);

    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    var divLoading = document.getElementById('loading-resumen');
    divLoading.style.display = "flex";


    let fecha_ini = document.getElementById("inputFechaIniPeriodo").value;
    let fecha_fin = document.getElementById("inputFechaFinPeriodo").value;

    let fecha_ini_send = Formato_Fecha_yyyymmdd(fecha_ini);
    let fecha_fin_send = Formato_Fecha_yyyymmdd(fecha_fin);

    url = base_url + '/ReporteIngresos/generarPDFReporteIngresos/' + fecha_ini_send + '/' + fecha_fin_send;
    window.open(url, "Reporte de Ingresos", "fullscreen=yes");

    /* Reactiva el botón para mostrar Informe */
    removerClasesButtonGuardar(btnGuardar, loading);

    /* Remueve el Loading del Div */
    divLoading.style.display = "none";

}


function fntReimprimirRecibo(btnElement) {

    /*-------------------------------------------
    [ Obtiene id de registro ]*/
    var recibo_id = btnElement.getAttribute("data-id");

    url = base_url + '/recibos/generarComprobante/' + recibo_id;
    window.open(url, "Recibo de Cobro", "fullscreen=yes");


}


/*==================================================================
[ DataTable ]*/

function setConfigTableReporteIngresos(controlador, metodo) {

    configTableReporteIngresos = {
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
            { "data": "domicilio" },
            { "data": "folio" },
            { "data": "created_at" },
            { "data": "clasificacion_ingreso" },
            { "data": "concepto" },
            { "data": "importe" },
            { "data": "estatus" },
            { "data": "fecha_cancela" },
            { "data": "usuario_cancela" },
            { "data": "motivo_cancela" },
            { "data": "options" }
        ],
        'language': idioma_espanol

    };

}

function setConfigTableRecibosAnteriores(controlador, metodo) {

    configTableRecibosAnteriores = {
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
            sheetName: 'Lista de Recibos de Cobro Anteriores',
            extend: 'excel',
            messageTop: "",
            title: 'Amores - Lista de Recibos de Cobro Anteriores',
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
            { "data": "domicilio" },
            { "data": "folio" },
            { "data": "created_at" },
            { "data": "concepto" },
            { "data": "folio_anterior" },
            { "data": "archivo" },
            { "data": "estatus" },
            { "data": "fecha_cancela" },
            { "data": "usuario_cancela" },
            { "data": "motivo_cancela" },
            { "data": "options" }
        ],
        'language': idioma_espanol

    };

}

//  <th class="font-weight-bold text-center">Fecha Cancelacion</th>
//                                                         <th class="font-weight-bold text-center">Usuario Cancela</th>
//                                                         <th class="font-weight-bold text-center">Motivo Cancela</th>

/*==================================================================
[ DataTable Filtros ]*/


/*-------------------------------------------
   [ DataTable Inicializa - Recibos asociados al residente ]*/
// setConfigTableReporteIngresos('Recibos', 'getRecibos/' + data.id);
// tableRecibos = $(tableRecibosElement).DataTable(configTableReporteIngresos);