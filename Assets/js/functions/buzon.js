/*==================================================================
[ Variables ]*/

var tablePerfilSeguimientoBuzon;
var tablePerfilSeguimientoBuzonElement = "#tablePerfilSeguimientoBuzon";
let tablePerfilSeguimientoBuzonElementJS = "tablePerfilSeguimientoBuzon";
var configTablePerfilSeguimientoBuzon = "";

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Clasificacion de Gastos]*/

    if (document.getElementById('formEnviarBuzon')) {
        let formEnviarBuzon = document.getElementById('formEnviarBuzon');
        formEnviarBuzon.addEventListener("submit", function(event) { setBuzon(event) });
    }


    /*==================================================================
    [ Botons de Accion ]*/

    /*-------------------------------------------
    [ Button - Agregar evento click para Nuevo Registro ]*/
    if (document.getElementById('btnMostrarTodos')) {
        var btnMostrarTodos = document.getElementById('btnMostrarTodos');
        btnMostrarTodos.onclick = function() { cargarListaTodos() };
    }



    /*==================================================================
    [ DataTable ]*/

    if (document.getElementById(tablePerfilSeguimientoBuzonElementJS)) {

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tablePerfilSeguimientoBuzonElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tablePerfilSeguimientoBuzonElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tablePerfilSeguimientoBuzonElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tablePerfilSeguimientoBuzonElement).on('click', 'tbody tr>td', function() {

        });

    }


});

/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    cargarSeguimiento();

    cargarListaTodos();

    /*-------------------------------------------
             [ Inicializa Select2 ]*/
    $('#comboEstatusBuzon').select2({
        language: "es",
        placeholder: 'Seleccione una opcion',
        minimumResultsForSearch: Infinity
    });

    //Asignar Valor Default después de Inicializar Seleclt2
    $('#comboEstatusBuzon').val('');
    $('#comboEstatusBuzon').trigger('change');


}, false)

/*==================================================================
[ Funciones de Carga Inicial ]*/

function cargarSeguimiento() {

    if (document.getElementById('buzon_seguimiento')) {

        let buzon_id = document.getElementById('buzon_id').value;
        let residente_id = document.getElementById('residente_id').value;

        getFunctionHTML('Buzon', 'getSeguimientoBuzon', buzon_id + '/' + residente_id, function(html) {
            console.log(html);
            document.getElementById('buzon_seguimiento').innerHTML = html;
        });
    }

}

/*==================================================================
[ Funciones Fill Selects e Inicializa Select2]*/


/*==================================================================
[ Guardar Registro ]*/

function setBuzon(event) {


    /*-------------------------------------------
      [ Evita la recarga de la pagina. ]*/
    event.preventDefault();

    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    var divLoading = document.getElementById('loading-resumen');
    divLoading.style.display = "flex";


    let residente_id = document.getElementById('residente_id').value;
    let buzon_id = document.getElementById('buzon_id').value;


    /*-------------------------------------------
    [ Ajax ]*/

    postFunction(formEnviarBuzon, 'Buzon', 'setBuzonAdmin', function(responseObj) {

        if (responseObj.respuesta == "ok") {

            /** -- ResetForm -- */
            resetFormNoPasley(formEnviarBuzon, '#formEnviarBuzon');

            /** -- Restablece lso parametros base -- */
            document.getElementById('residente_id').value = residente_id;
            document.getElementById('buzon_id').value = buzon_id;

            /** -- Mensaje de Alerta -- */
            alerta_success(responseObj, divLoading);

        } else {

            /** -- Mensaje de Alerta -- */
            alerta_error(responseObj, divLoading);
        }

    });

}


/*==================================================================
[ Cargar Datos de Residente ]*/

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
    setConfigTablePerfilSeguimientoBuzon('Buzon', 'getListBuzon/' + data.id);
    tablePerfilSeguimientoBuzon = $(tablePerfilSeguimientoBuzonElement).DataTable(configTablePerfilSeguimientoBuzon);

}


/*==================================================================
[ Cargar Lista completa de buzón ]*/

function cargarListaTodos() {

    /*-------------------------------------------
    [ DataTable Inicializa - Lista de Tags asociados al residente ]*/
    setConfigTablePerfilSeguimientoBuzon('Buzon', 'getListBuzonAdmin');
    tablePerfilSeguimientoBuzon = $(tablePerfilSeguimientoBuzonElement).DataTable(configTablePerfilSeguimientoBuzon);

}



/*==================================================================
[ DataTable ]*/

function setConfigTablePerfilSeguimientoBuzon(controlador, metodo) {

    configTablePerfilSeguimientoBuzon = {
        "aProcessing": true,
        "aServerSide": true,
        'destroy': true,
        'responsive': false,
        "autoWidth": false,
        "iDisplayLength": 5,
        "lengthMenu": [
            [5, 10, 25, -1],
            [5, 10, 25, "Todos"]
        ],
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
                sheetName: 'Buzon',
                extend: 'excel',
                messageTop: "",
                title: 'Amores - Buzon Lista',
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
        columnDefs: [
            { className: "text-muted fs-15 fw-semibold text-center px-4 py-2", targets: [0, 3, 4, 5] },
            { className: "text-muted fs-15 fw-semibold px-5 py-2", targets: [6] },
            { className: "fs-15 fw-semibold text-center px-5 py-2", targets: [7] },
            { className: "fs-15 fw-semibold px-5 py-2", targets: [1] },
            { className: "fs-15 fw-semibold text-center px-5 py-2", targets: [2] }
        ],
        "columns": [
            { "data": "No" },
            { "data": "nombre" },
            { "data": "domicilio" },
            { "data": "Folio" },
            { "data": "created_at" },
            { "data": "tipo" },
            { "data": "asunto" },
            { "data": "estatus" }
        ],
        'language': idioma_espanol

    };


}