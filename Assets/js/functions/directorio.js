/*==================================================================
[ Variables de Archivo ]*/

let tableDirectorio;
let tableDirectorioElement = "#tableDirectorio";
let tableDirectorioElementJS = "tableDirectorio";
let configTableDirectorio = "";

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*==================================================================
    [ DataTable ]*/

    if (document.getElementById(tableDirectorioElementJS)) {


        /*-------------------------------------------
        [ DataTable Inicializa ]*/
        setConfigTableDirectorio('Residentes', 'getDirectorio');
        tableDirectorio = $(tableDirectorioElement).DataTable(configTableDirectorio);

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableDirectorioElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tableDirectorioElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableDirectorioElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableDirectorioElement).on('click', 'tbody tr>td', function() {

        });

    }


});


/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {


}, false)


/*==================================================================
[ DataTable ]*/

function setConfigTableDirectorio(controlador, metodo) {

    configTableDirectorio = {
        "aProcessing": true,
        "aServerSide": true,
        'destroy': true,
        'responsive': false,
        "autoWidth": false,
        "iDisplayLength": 10,
        "order": [
            [0, "asc"],
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
                sheetName: 'Directorio',
                extend: 'excel',
                messageTop: "",
                title: 'Amores - Directorio de Residentes',
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
            { className: "text-center p-2", targets: [0, 1, 3, 4] },
            { className: "text-start p-2", targets: [2, 5] }
        ],
        "columns": [
            { "data": "calle" },
            { "data": "numero" },
            { "data": "nombre" },
            { "data": "telefono" },
            { "data": "email" },
            // { "data": "estatus" },
            { "data": "indicaciones_generales_visitas" }
        ],
        'language': idioma_espanol

    };

}