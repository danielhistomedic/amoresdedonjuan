/*==================================================================
[ Variables de Archivo ]*/

let tableEstadoResultadosIngresos;
let tableEstadoResultadosIngresosElement = "#tableEstadoResultadosIngresos";
let configTableEstadoResultadosIngresos = "";

let tableEstadoResultadosEgresos;
let tableEstadoResultadosEgresosElement = "#tableEstadoResultadosEgresos";
let configTableEstadoResultadosEgresos = "";

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {


    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Residente] */
    if (document.getElementById('formMostrarInformeEstadoResultados')) {
        var formMostrarInformeEstadoResultados = document.getElementById('formMostrarInformeEstadoResultados');
        formMostrarInformeEstadoResultados.addEventListener("submit", function(event) { getReporteEstadoResultados(event) });
    }


    /*==================================================================
    [ Botons de Accion ]*/
    if (document.getElementById('btnImprimirInforme')) {
        var btnImprimirInforme = document.getElementById('btnImprimirInforme');
        btnImprimirInforme.onclick = function() { vistaInforme() };
    }

    if (document.getElementById('btnGenerarInforme')) {
        var btnGenerarInforme = document.getElementById('btnGenerarInforme');
        btnGenerarInforme.onclick = function() { generarIngresosEgresosCierre() };
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

    if (document.querySelector(tableEstadoResultadosIngresosElement)) {

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableEstadoResultadosIngresosElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableEstadoResultadosIngresosElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead-ingresos");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableEstadoResultadosIngresosElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableEstadoResultadosIngresosElement).on('click', 'tbody tr>td', function() {

        });
    }

    if (document.querySelector(tableEstadoResultadosEgresosElement)) {

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableEstadoResultadosEgresosElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableEstadoResultadosEgresosElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead-egresos");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableEstadoResultadosEgresosElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableEstadoResultadosEgresosElement).on('click', 'tbody tr>td', function() {

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

function getReporteEstadoResultados(e) {

    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();


    /*-------------------------------------------
    [ Limpiar Cuadro Resumen ]*/
    document.getElementById("lblSaldoAnterior").innerHTML = '0.00';
    document.getElementById("lblIngresosPeriodo").innerHTML = '0.00';
    document.getElementById("lblGastosPeriodo").innerHTML = '0.00';
    document.getElementById("lblSaldoPeriodo").innerHTML = '0.00';
    document.getElementById("lblPeriodo").innerHTML = '';
    document.getElementById("lblFechaSaldoAnterior").innerHTML = '';


    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble recarga de datos ]*/
    var btnGuardar = document.getElementById('btnMostarInforme');
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
    setConfigTableEstadoResultadosIngresos('EstadoResultados', 'getIngresos/' + fecha_ini_send + '/' + fecha_fin_send);
    tableEstadoResultadosIngresos = $(tableEstadoResultadosIngresosElement).DataTable(configTableEstadoResultadosIngresos);

    setConfigTableEstadoResultadosEgresos('EstadoResultados', 'getEgresos/' + fecha_ini_send + '/' + fecha_fin_send);
    tableEstadoResultadosEgresos = $(tableEstadoResultadosEgresosElement).DataTable(configTableEstadoResultadosEgresos);

    let saldo_anterior = 0;
    let lblSaldoAnterior = '';

    let ingresos_periodo = 0;
    let lblIngresosPeriodo = '';

    let gastos_periodo = 0;
    let lblGastosPeriodo = '';

    let saldo_periodo = 0;
    let lblSaldoPeriodo = '';

    getSaldoActual(fecha_ini_send, function(data) {

        saldo_anterior = data.saldo;
        lblSaldoAnterior = Formato_Moneda(data.saldo);

        getImporteTotalIngresos(fecha_ini_send, fecha_fin_send, function(data) {

            ingresos_periodo = data.total_ingresos;
            lblIngresosPeriodo = Formato_Moneda(ingresos_periodo);


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



            getImporteTotalEgresos(fecha_ini_send, fecha_fin_send, function(data) {

                gastos_periodo = data.total_egresos;
                lblGastosPeriodo = Formato_Moneda(gastos_periodo);

                saldo_periodo = saldo_anterior + ingresos_periodo - gastos_periodo;
                lblSaldoPeriodo = Formato_Moneda(saldo_periodo);

                /* Carga datos en el DOM */
                document.getElementById("lblSaldoAnterior").innerHTML = lblSaldoAnterior;
                document.getElementById("lblIngresosPeriodo").innerHTML = lblIngresosPeriodo;
                document.getElementById("lblGastosPeriodo").innerHTML = lblGastosPeriodo;
                document.getElementById("lblSaldoPeriodo").innerHTML = lblSaldoPeriodo;

                /* Remueve el Loading del Div */
                divLoading.style.display = "none";

                /* Reactiva el botón para mostrar Informe */
                removerClasesButtonGuardar(btnGuardar, loading);

                document.getElementById("lblPeriodo").innerHTML = '(Del ' + fecha_ini + ' al ' + fecha_fin + ')';

                let fecha_saldo_anterior = addDays(fecha_ini, -1);
                document.getElementById("lblFechaSaldoAnterior").innerHTML = '(' + fecha_saldo_anterior + ')';

            });

        });

    });

}

function getSaldoActual(fecha_ini, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            console.log(dataObj);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/EstadoResultados/getSaldoAnterior/' + fecha_ini;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}

function getImporteTotalIngresos(fecha_ini, fecha_fin, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            console.log(dataObj);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/EstadoResultados/getImporteTotalIngresosPeriodo/' + fecha_ini + '/' + fecha_fin;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}

function getImporteTotalEgresos(fecha_ini, fecha_fin, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            console.log(dataObj);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/EstadoResultados/getImporteTotalEgresosPeriodo/' + fecha_ini + '/' + fecha_fin;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}


/*==================================================================
[ Imprimir ]*/

function vistaInforme() {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble recarga de datos ]*/
    var btnGuardar = document.getElementById('btnImprimirInforme');
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
    window.open(url, "Ingresos y Egresos", "fullscreen=yes");

    /* Reactiva el botón para mostrar Informe */
    removerClasesButtonGuardar(btnGuardar, loading);

    /* Remueve el Loading del Div */
    divLoading.style.display = "none";

}



/*==================================================================
[ Generar PDF Cierre de Mes para Portal de Residentes. ]*/

function generarIngresosEgresosCierre() {


    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    var divLoading = document.getElementById('loading-resumen');
    divLoading.style.display = "flex";

    mensajeAlertaModal({
        icon: 'warning',
        title: iconMensajeWarning + ' ¡Advertencia!',
        text: "¿Está seguro de generar el informe?",
        textButton: 'Sí, Generar',
        textCancelButton: 'No, Cerrar'
    }).then(function(result) {
        if (result.dismiss == true) { divLoading.style.display = "none"; };
        if (result.si == true) { generar(); };
    });

}

function generar() {

    var divLoading = document.getElementById('loading-resumen');
    divLoading.style.display = "flex";

    let fecha_ini = document.getElementById("inputFechaIniPeriodo").value;
    let fecha_fin = document.getElementById("inputFechaFinPeriodo").value;

    let fecha_ini_send = Formato_Fecha_yyyymmdd(fecha_ini);
    let fecha_fin_send = Formato_Fecha_yyyymmdd(fecha_fin);


    /*-------------------------------------------
    [ Ajax ]*/
    var formData = new FormData();
    formData.append('fecha_ini', fecha_ini_send);
    formData.append('fecha_fin', fecha_fin_send);

    postFunctionData(formData, 'EstadoResultados', 'generarEstadoResultadosCierre/' + fecha_ini_send + '/' + fecha_fin_send, function(responseObj) {

        console.log(responseObj);

        if (responseObj.respuesta == "ok") {

            /** -- Mensaje de Alerta -- */
            alerta_success(responseObj, divLoading);

        } else {

            /** -- Mensaje de Alerta -- */
            alerta_error(responseObj, divLoading);

        }

    });
}

/*==================================================================
[ DataTable ]*/

function setConfigTableEstadoResultadosIngresos(controlador, metodo) {

    configTableEstadoResultadosIngresos = {
        "aProcessing": true,
        "aServerSide": true,
        'destroy': true,
        'responsive': false,
        "autoWidth": false,
        "iDisplayLength": 5,
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
            { "data": "calle" },
            { "data": "importe" }
        ],
        'language': idioma_espanol

    };

}

function setConfigTableEstadoResultadosEgresos(controlador, metodo) {

    configTableEstadoResultadosEgresos = {
        "aProcessing": true,
        "aServerSide": true,
        'destroy': true,
        'responsive': false,
        "autoWidth": false,
        "iDisplayLength": 5,
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
                sheetName: 'Informe de Egresos',
                extend: 'excel',
                messageTop: "",
                title: 'Amores - Informe de Estado de Resultados (Egresos)',
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
// setConfigTableEstadoResultadosIngresos('Recibos', 'getRecibos/' + data.id);
// tableRecibos = $(tableRecibosElement).DataTable(configTableEstadoResultadosIngresos);