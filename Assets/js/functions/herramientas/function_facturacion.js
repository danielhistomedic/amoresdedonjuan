/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {


    /*==================================================================
    [ Facturación ]*/

    /*-------------------------------------------
     [ Agregar evento shown.mdb.tab a tab_config_facturacion para desplegar la primer card de cada tab en Facturacion  ]*/

    // if (document.querySelector('.tab_config_facturacion')) {
    //     const tab_config_facturacion = document.querySelector('.tab_config_facturacion');
    //     tab_config_facturacion.addEventListener('shown.mdb.tab', (event) => {
    //         var collapseConfiFactUnidadMedica = document.getElementById('collapseFactUnidadMedica');
    //         var bsCollapse = new bootstrap.Collapse(collapseConfiFactUnidadMedica, {
    //             show: true
    //         })
    //     })

    //     tab_config_facturacion.addEventListener('hidden.mdb.tab', (event) => {
    //         var collapseConfiFactUnidadMedica = document.getElementById('collapseFactUnidadMedica');
    //         var bsCollapse = new bootstrap.Collapse(collapseConfiFactUnidadMedica, {
    //             show: true
    //         })
    //     })

    // }

    /*-------------------------------------------
     [ Agregar evento click a Editar Facturación de Unidad Medica  ]*/

    if (document.getElementById("btnEditar_Confifg_FactUnidadMedica")) {
        var btnElement = document.getElementById('btnEditar_Confifg_FactUnidadMedica');
        btnElement.onclick = function() { fntEditarConfigFactUnidadMedica() };
    }

    /*-------------------------------------------
     [ Agregar evento click a Cancelar Edicion de Configuración de Facturación de Unidad Medica  ]*/

    if (document.getElementById("btnCancelar_Confifg_FactUnidadMedica")) {
        var btnElement = document.getElementById('btnCancelar_Confifg_FactUnidadMedica');
        btnElement.onclick = function() { fntCancelarConfigFactUnidadMedica() };
    }


});



/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    /*-------------------------------------------
    [ Funciones Fill Selects ]*/
    fillSelectRegimenFiscal();

    /*-------------------------------------------
    [ Activa el menu horizontal correspondiente del sidemenu ]*/
    var pageUrl = base_url + "/herramientas"
    fntActivarHorizontalMenu(pageUrl);

}, false)


/*==================================================================
[ Funciones de Eventos ]*/




/*==================================================================
[ Funciones Llenar Selects e Inicializa Select2]*/

function fillSelectRegimenFiscal() {

    if (document.querySelector('#comboRegimenFiscal')) {

        //Ajax LLenar Select de catalogo de Especialidades
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboRegimenFiscal').innerHTML = xhttp.responseText;

                /*-------------------------------------------
                [ Inicializa Select2 Mulitselect]*/
                $('#comboRegimenFiscal').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion'
                });

                //Asignar Valor Default después de Inicializar Seleclt2
                $('#comboRegimenFiscal').val('');
                $('#comboRegimenFiscal').trigger('change');

            }

        };
        var ajaxUrl = base_url + '/CatalogosSat/getAllSelectRegimenFiscal';
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();

    }

}


/*==================================================================
[ Funciones Facturación de Unidad Medica para Edit/View ]*/

function fntEditarConfigFactUnidadMedica() {


    var editar = document.getElementById('editar_config_fact_consultorio');
    var view = document.getElementById('view_config_fact_consultorio');
    view.style.display = "none";
    editar.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    var eLOrigen = $('.editar_config_fact_consultorio');

    //Elemento que recibe la animación
    var eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);


}

function fntCancelarConfigFactUnidadMedica() {

    var editar = document.getElementById('editar_config_fact_consultorio');
    var view = document.getElementById('view_config_fact_consultorio');
    editar.style.display = "none";
    view.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    var eLOrigen = $('.view_config_fact_consultorio');

    //Elemento que recibe la animación
    var eLDestino = view;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}