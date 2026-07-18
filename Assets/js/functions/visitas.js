/*==================================================================
[ Variables de Archivo ]*/

var tablePerfilVisitas;
var tablePerfilVisitasElement = "#tablePerfilVisitas";
var tablePerfilVisitasElementJS = "tablePerfilVisitas";
var configTablePerfilVisitas = "";

var btnGuardar;

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*-------------------------------------------
    [ Form - Agregar evento submit al formulario de registro de Residente] */
    if (document.getElementById('formReporteVisitas')) {
        var formReporteVisitas = document.getElementById('formReporteVisitas');
        formReporteVisitas.addEventListener("submit", function(event) { getReporteVisitas(event) });
    }


    /*==================================================================
    [ Botons de Accion ]*/

    if (document.getElementById('btnNuevaVisita')) {
        var btnNuevaVisita = document.getElementById('btnNuevaVisita');
        btnNuevaVisita.onclick = function() { nuevoVisita() };
    }


    /*==================================================================
    [ DataTable ]*/

    if (document.getElementById(tablePerfilVisitasElementJS)) {


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de terminar ajax ]*/
        $(tablePerfilVisitasElement).on('xhr.dt', function(e, settings, json, xhr) {

            /* Reactiva el botón para mostrar Informe */
            removerClasesButtonGuardar(btnGuardar, loading);

        })


        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tablePerfilVisitasElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            let thead = document.querySelector(tablePerfilVisitasElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tablePerfilVisitasElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tablePerfilVisitasElement).on('click', 'tbody tr>td', function() {

        });

    }


});

/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {


}, false)


/*==================================================================
[ Botones de Acccion ]*/

function setVisita(event) {


    /*-------------------------------------------
      [ Evita la recarga de la pagina. ]*/
    event.preventDefault();


    /*-------------------------------------------
    [ Mostrar Loading en div. ]*/
    var divLoading = document.getElementById('loading');
    divLoading.style.display = "flex";


    /*-------------------------------------------
    [ Ajax ]*/

    postFunction(formAutorizarVisita, 'Visitas', 'setVisita', function(responseObj) {

        if (responseObj.respuesta == "ok") {


            /** -- Validar tipo de link de whatsup -- */
            let residente_id = document.getElementById('residente_id').value;
            let calle = document.getElementById('calle').value;
            let numero = document.getElementById('numero').value;
            let whatsup = document.querySelectorAll('.whatsup');
            let telefono = document.getElementById('telefono').value;
            if (telefono == '') {
                for (let index = 0; index < whatsup.length; index++) {
                    const element = whatsup[index];
                    element.setAttribute("href", "https://wa.me/?text=Código de Accesso, " + calle + " " + numero + ". Vigencia: " + vigencia_qr + " minutos. " + assets + "/files/visitas/" + residente_id + ".png");
                }
            } else {
                for (let index = 0; index < whatsup.length; index++) {
                    const element = whatsup[index];
                    element.setAttribute("href", "https://wa.me/52" + telefono + "?text=Código de Accesso, " + calle + " " + numero + ". Vigencia: " + vigencia_qr + " minutos. " + assets + "/files/visitas/" + residente_id + ".png");
                }
            }
            // https://wa.me/527712297574?text=Tu Codigo de Accesso <?= media(); ?>/files/visitas/<?= $data['residente']['id']; ?>.png
            // https://wa.me/?text=Tu Codigo de Accesso <?= media(); ?>/files/visitas/<?= $data['residente']['id']; ?>.png



            /** -- ResetForm -- */
            resetForm(formAutorizarVisita, '#formAutorizarVisita');

            /** -- Ocultar Inputs -- */
            let form_valida = document.querySelectorAll('.form-valida');
            for (let i = 0; i < form_valida.length; i++) {
                const element = form_valida[i];
                element.style.display = 'none';
            }
            document.getElementById('btnNuevaVisita').classList.toggle('d-none');


            /** -- Recargar Imagen -- */
            var aleatorio = Math.ceil(Math.random() * 100);
            // <img class="d-block img-fluid br-5" src="<?= media(); ?>/files/visitas/<?= $data['residente']['id']; ?>.png" alt="">
            document.getElementById('qr_img').innerHTML = '<img  class="d-block img-fluid br-5" src=' + assets + '/files/visitas/' + residente_id + '.png?' + aleatorio + '" alt="">';


            /** -- Mensaje de Alerta -- */
            //-----------------------------------
            //[ Animación de Paneles ]
            divLoading.style.display = "none";
            document.querySelector('.qr_result').style.display = 'block';

            //Elemento que dispara la animación
            let eLOrigen = document.querySelector('#btnGuardarAutorizacion');

            //Elemento que recibe la animación
            let qr_result = document.querySelector('.qr_result');
            let eLDestino = qr_result;

            // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
            animationButton(eLOrigen, eLDestino);


        } else {

            /** -- Mensaje de Alerta -- */
            alerta_error(responseObj, divLoading);
        }

    });

}

function nuevoVisita() {

    document.querySelector('.qr_result').style.display = 'none';

    let form_valida = document.querySelectorAll('.form-valida');
    for (let i = 0; i < form_valida.length; i++) {
        const element = form_valida[i];
        element.style.display = 'block';
    }
    document.getElementById('btnNuevaVisita').classList.toggle('d-none');

}

/*==================================================================
[ Mostrar Informe ]*/

function getReporteVisitas(e) {

    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble recarga de datos ]*/
    btnGuardar = document.getElementById('btnMostarReporteVisitas');
    agregarLoadingButtonGuardar(btnGuardar);

    /*-------------------------------------------
    [ Asigna datos de variables ]*/
    let fecha_ini = document.getElementById("inputFechaIniPeriodo").value;
    let fecha_fin = document.getElementById("inputFechaFinPeriodo").value;

    let fecha_ini_send = Formato_Fecha_yyyymmdd(fecha_ini);
    let fecha_fin_send = Formato_Fecha_yyyymmdd(fecha_fin);

    /*-------------------------------------------
    [ DataTable Inicializa ]*/
    setConfigTablePerfilVisitas('Visitas', 'getListVisitas/' + fecha_ini_send + '/' + fecha_fin_send);
    tablePerfilVisitas = $(tablePerfilVisitasElement).DataTable(configTablePerfilVisitas);

}


/*==================================================================
[ DataTable ]*/

function setConfigTablePerfilVisitas(controlador, metodo) {

    configTablePerfilVisitas = {
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
                sheetName: 'Visitas',
                extend: 'excel',
                messageTop: "",
                title: 'Amores - Lista de Visitas',
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
            { className: "text-muted fs-15 fw-semibold text-center p-2", targets: [0, 1, 3, 4, 7] },
            { className: "text-muted fs-15 fw-semibold text-start p-2", targets: [2] },
            { className: "text-muted fs-15 fw-semibold p-2", targets: [5] },
            { className: "fs-15 fw-semibold text-center p-2", targets: [6] }
        ],
        "columns": [
            { "data": "No" },
            { "data": "created_at" },
            { "data": "residente" },
            { "data": "nombre" },
            { "data": "telefono" },
            { "data": "comentarios_adicionales" },
            { "data": "estatus" },
            { "data": "fecha_entrada" }
        ],
        'language': idioma_espanol

    };

}