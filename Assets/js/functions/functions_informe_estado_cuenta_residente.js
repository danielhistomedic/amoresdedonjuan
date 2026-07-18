/*==================================================================
[ Variables de Archivo ]*/

let tableEstadoCuentaResidente;
let tableEstadoCuentaResidenteElement = "#tableEstadoCuentaResidente";
let configTableEstadoCuentaResidente = "";

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*==================================================================
    [ Form ]*/


    /*==================================================================
    [ Botons de Accion ]*/
    if (document.getElementById('btnImprimirInformeEC')) {
        var btnImprimirInformeEC = document.getElementById('btnImprimirInformeEC');
        btnImprimirInformeEC.onclick = function() { vistaInformeEC() };
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

    if (document.querySelector(tableEstadoCuentaResidenteElement)) {

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableEstadoCuentaResidenteElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableEstadoCuentaResidenteElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableEstadoCuentaResidenteElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableEstadoCuentaResidenteElement).on('click', 'tbody tr>td', function() {

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
    setConfigTableEstadoCuentaResidente('InformeEstadoCuentaResdiente', 'getCuentas/' + data.id);
    tableEstadoCuentaResidente = $(tableEstadoCuentaResidenteElement).DataTable(configTableEstadoCuentaResidente);


    /*-------------------------------------------
    [ Cargar Datos de Resumen de Cuenta ]*/
    getResumenEstadoCuenta(data.id, function(data_resumen) {

        //   $data['importe_adeudo'] =  $importe_adeudo;
        //         $data['importe_saldo_disponible'] =  $importe_saldo_disponible;

        //         importe_adeudo: 6800
        // importe_saldo_disponible: 0

        let lblTotalAdeudo = document.getElementById('lblTotalAdeudo');
        lblTotalAdeudo.innerHTML = Formato_Moneda(data_resumen.importe_adeudo);

        let lblSaldoACuentaDisponible = document.getElementById('lblSaldoACuentaDisponible');
        lblSaldoACuentaDisponible.innerHTML = Formato_Moneda(data_resumen.importe_saldo_disponible);

    });

}

function getResumenEstadoCuenta(residente_id, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj.data);
        }
    };
    var ajaxUrl = base_url + '/InformeEstadoCuentaResdiente/getResumenEstadoCuenta/' + residente_id;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}

/*==================================================================
[ Imprimir ]*/

function vistaInformeEC() {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble recarga de datos ]*/
    var btnGuardar = document.getElementById('btnImprimirInformeEC');
    agregarLoadingButtonGuardar(btnGuardar);

    let residente_id = document.getElementById("residente_id").value

    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    var divLoading = document.getElementById('loading-resumen');
    divLoading.style.display = "flex";

    url = base_url + '/InformeEstadoCuentaResdiente/generarEstadoCuentaResidente/' + residente_id;
    window.open(url, "Estado de Cuenta Residente", "fullscreen=yes");

    /* Reactiva el botón para mostrar Informe */
    removerClasesButtonGuardar(btnGuardar, loading);

    /* Remueve el Loading del Div */
    divLoading.style.display = "none";

}

/*==================================================================
[ DataTable ]*/

function setConfigTableEstadoCuentaResidente(controlador, metodo) {

    configTableEstadoCuentaResidente = {
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
            { "data": "anio" },
            { "data": "mes" },
            { "data": "estatus" },
            { "data": "descripcion" },
            { "data": "folio" }
        ],
        'language': idioma_espanol

    };

}

/*==================================================================
[ DataTable Filtros ]*/

/*-------------------------------------------
   [ DataTable Inicializa - Recibos asociados al residente ]*/
// setConfigTableEstadoCuentaResidente('Recibos', 'getRecibos/' + data.id);
// tableRecibos = $(tableRecibosElement).DataTable(configTableEstadoCuentaResidente);