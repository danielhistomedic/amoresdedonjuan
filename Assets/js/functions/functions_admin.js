/*==================================================================
[ Developer ]*/

// Capturamos el click y lo pasamos a una funcion
// document.onclick = captura_click;

// function captura_click(e) {
//     // Funcion para capturar el click del raton
//     var HaHechoClick;
//     HaHechoClick = e.target;
//     console.log(HaHechoClick);
// }


/*==================================================================
[ Constantes ]*/

/* -- Constante para usar animateCSS. -- */
const animateCSS = (element, animation, prefix = 'animate__') =>
    // We create a Promise and return it
    new Promise((resolve, reject) => {
        const animationName = `${prefix}${animation}`;
        const node = document.querySelector(element);

        node.classList.add(`${prefix}animated`, animationName);

        // When the animation ends, we clean the classes and resolve the Promise
        function handleAnimationEnd() {
            node.classList.remove(`${prefix}animated`, animationName);
            resolve('Animation ended');
        }

        node.addEventListener('animationend', handleAnimationEnd, {
            once: true
        });
    });


/* -- Configuración de Idioma español para Datatable Versión Completa. -- */
const idioma_espanol_complete = {
    "processing": "Procesando...",
    "lengthMenu": "Mostrar _MENU_ registros",
    "zeroRecords": "No se encontraron resultados",
    "emptyTable": "Ningún dato disponible en esta tabla",
    "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "infoFiltered": "(filtrado de un total de _MAX_ registros)",
    "search": "Buscar:",
    "infoThousands": ",",
    "loadingRecords": "Cargando...",
    "paginate": {
        "first": "Primero",
        "last": "Último",
        "next": "Siguiente",
        "previous": "Anterior"
    },
    "aria": {
        "sortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sortDescending": ": Activar para ordenar la columna de manera descendente"
    },
    "buttons": {
        "copy": "Copiar",
        "colvis": "Visibilidad",
        "collection": "Colección",
        "colvisRestore": "Restaurar visibilidad",
        "copyKeys": "Presione ctrl o u2318 + C para copiar los datos de la tabla al portapapeles del sistema. <br \/> <br \/> Para cancelar, haga clic en este mensaje o presione escape.",
        "copySuccess": {
            "1": "Copiada 1 fila al portapapeles",
            "_": "Copiadas %d fila al portapapeles"
        },
        "copyTitle": "Copiar al portapapeles",
        "csv": "CSV",
        "excel": "Excel",
        "pageLength": {
            "-1": "Mostrar todas las filas",
            "1": "Mostrar 1 fila",
            "_": "Mostrar %d filas"
        },
        "pdf": "PDF",
        "print": "Imprimir"
    },
    "autoFill": {
        "cancel": "Cancelar",
        "fill": "Rellene todas las celdas con <i>%d<\/i>",
        "fillHorizontal": "Rellenar celdas horizontalmente",
        "fillVertical": "Rellenar celdas verticalmentemente"
    },
    "decimal": ",",
    "searchBuilder": {
        "add": "Añadir condición",
        "button": {
            "0": "Constructor de búsqueda",
            "_": "Constructor de búsqueda (%d)"
        },
        "clearAll": "Borrar todo",
        "condition": "Condición",
        "conditions": {
            "date": {
                "after": "Despues",
                "before": "Antes",
                "between": "Entre",
                "empty": "Vacío",
                "equals": "Igual a",
                "not": "No",
                "notBetween": "No entre",
                "notEmpty": "No Vacio"
            },
            "moment": {
                "after": "Despues",
                "before": "Antes",
                "between": "Entre",
                "empty": "Vacío",
                "equals": "Igual a",
                "not": "No",
                "notBetween": "No entre",
                "notEmpty": "No vacio"
            },
            "number": {
                "between": "Entre",
                "empty": "Vacio",
                "equals": "Igual a",
                "gt": "Mayor a",
                "gte": "Mayor o igual a",
                "lt": "Menor que",
                "lte": "Menor o igual que",
                "not": "No",
                "notBetween": "No entre",
                "notEmpty": "No vacío"
            },
            "string": {
                "contains": "Contiene",
                "empty": "Vacío",
                "endsWith": "Termina en",
                "equals": "Igual a",
                "not": "No",
                "notEmpty": "No Vacio",
                "startsWith": "Empieza con"
            }
        },
        "data": "Data",
        "deleteTitle": "Eliminar regla de filtrado",
        "leftTitle": "Criterios anulados",
        "logicAnd": "Y",
        "logicOr": "O",
        "rightTitle": "Criterios de sangría",
        "title": {
            "0": "Constructor de búsqueda",
            "_": "Constructor de búsqueda (%d)"
        },
        "value": "Valor"
    },
    "searchPanes": {
        "clearMessage": "Borrar todo",
        "collapse": {
            "0": "Paneles de búsqueda",
            "_": "Paneles de búsqueda (%d)"
        },
        "count": "{total}",
        "countFiltered": "{shown} ({total})",
        "emptyPanes": "Sin paneles de búsqueda",
        "loadMessage": "Cargando paneles de búsqueda",
        "title": "Filtros Activos - %d"
    },
    "select": {
        "1": "%d fila seleccionada",
        "_": "%d filas seleccionadas",
        "cells": {
            "1": "1 celda seleccionada",
            "_": "$d celdas seleccionadas"
        },
        "columns": {
            "1": "1 columna seleccionada",
            "_": "%d columnas seleccionadas"
        }
    },
    "thousands": "."
}

/* -- Configuración de Idioma español para Datatable. -- */
const idioma_espanol = {
    "sProcessing": "Procesando...",
    "sLengthMenu": "Mostrar _MENU_ registros",
    "sZeroRecords": "No se encontraron resultados",
    "sEmptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    "sInfoEmpty": "Ningún dato disponible en esta tabla",
    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix": "",
    "sSearch": "Buscar:",
    "sUrl": "",
    "sInfoThousands": ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    },
    "buttons": {
        "copy": "Copiar",
        "excel": "<i class='fas fa-file-excel'></i>&nbsp;&nbsp;Excel",
        "colvis": "<i class='fas fa-columns'></i>&nbsp;&nbsp;Columnas",
        "colvisRestore": "<i class='far fa-window-restore'></i>&nbsp;&nbsp;Restablecer Vista de Columnas&nbsp;&nbsp;&nbsp;"
    },
    'select': {
        'rows': {
            _: '[%d filas seleccionadas]',
            0: '[Ninguna linea seleccionada]',
            1: '[1 fila seleccionada]'
        }
    },
    "decimal": ".",
    "thousands": ","

};

/* -- Constantes para usar el icono representativo en los mensajes modales de Alerta. -- */
// const iconMensajeSuccess = '<i class="fa-light fa-face-smile-wink me-1 fa-lg"></i>';
// const iconMensajeError = '<i class="fa-light fa-face-frown-slight me-1 fa-lg"></i>';
// const iconMensajeWarning = '<i class="fa-light fa-face-thinking me-1 fa-lg"></i>';
// const iconMensajeInfo = '<i class="fa-light fa-face-grin-wink me-1 fa-lg"></i>';


/*==================================================================
[ Resolución conlficto con Tinymce - Bootstrap ]*/

$(document).on('focusin', function(e) {
    if ($(e.target).closest('.tox-dialog').length) {
        e.stopImmediatePropagation();
    }
});


/*-------------------------------------------
[ Variables de Validación de Formularios ]*/

/* -- Usa el loading en los botones antes de ejecutar la acción del botón. -- */
var loading;


/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*-------------------------------------------
     [ Agregar evento click para cerrar panel de registro en Main ]*/

    if (document.querySelector(".close-registros-main")) {
        var tabClose = document.querySelectorAll(".close-registros-main");
        for (let index = 0; index < tabClose.length; index++) {
            tabClose[index].addEventListener("click", function(e) { fntCerrarTabPaneRegistrosMain(this) });
        }
    }
    if (document.querySelector(".close-registros-main-cancel")) {
        var tabClose = document.querySelectorAll(".close-registros-main-cancel");
        for (let index = 0; index < tabClose.length; index++) {
            tabClose[index].addEventListener("click", function(e) { fntCerrarTabPaneRegistrosMainCancel(this) });
        }
    }


    /*==================================================================
    [ Input ]*/

    /*-------------------------------------------
    [ Input - Agregar evento focusin a los inputs seleccionados ]*/

    if (document.querySelector(".inputForm100")) {
        var inputForm = document.querySelectorAll(".inputForm100");
        for (let index = 0; index < inputForm.length; index++) {
            const element = inputForm[index];
            element.addEventListener("focusin", function(e) { limpiarInputs(this) });
        }
    }


    /*==================================================================
    [ CARD FULL SCREEN ]*/

    const DIV_CARD = 'div.card';
    $(document).on('click', '[data-bs-toggle="card-fullscreen"]', function(e) {

        let $card = $(this).closest(DIV_CARD);
        $card.toggleClass('card-fullscreen').removeClass('card-collapsed');
        e.preventDefault();
        return false;

    });

    const DIV_MODAL = 'div.modal-content';
    $(document).on('click', '[data-bs-toggle="card-fullscreen-modal"]', function(e) {

        let $card = $(this).closest(DIV_MODAL);
        $card.toggleClass('card-fullscreen').removeClass('card-collapsed');
        e.preventDefault();
        return false;

    });


    /*==================================================================
    [ Menu Mobile ]*/

    /*-------------------------------------------
    [ Agregar evento click para desplazar arriba y abajo el contanido principal con la clase app-content-mobile   ]*/
    if (document.getElementById("display-menu-mobile-app-content")) {
        var display_menu = document.getElementById("display-menu-mobile-app-content");
        display_menu.addEventListener("click", function(e) {
            if (document.getElementById("app-content-mobile")) {
                let app_content_mobile = document.getElementById("app-content-mobile");
                app_content_mobile.classList.toggle("app-content-mobile");
            }
        });
    }



    /*==================================================================
    [ Select2 ]*/

    $('.select2').on('select2:open', function(e) {
        document.querySelector(".select2-search__field").focus();
    });



    /*-------------------------------------------
    [ Form - Autocomplete ]*/

    if (document.getElementById('search_residente')) {
        $('#search_residente').autocomplete({
            source: function(request, response) {
                get_residente_search(request.term, function(result) {
                    response(result);
                });
            },
            minLength: 3,
            select: function(event, ui) {
                get_residente(ui.item.id, function(result) {
                    loadResidente(result);
                    // alert(result.calle);
                    // response(result);
                });
                // alert(ui.item.id);
                // window.location.href = ui.item.url;
            }
        }).data('ui-autocomplete')._renderItem = function(ul, item) {
            return $('<li class="ui-automplete-row"></li>')
                .data('item.autocomplete', item)
                .append(item.label)
                .appendTo(ul);
        }
    }


    if (document.getElementById('search_residente_caseta')) {
        $('#search_residente_caseta').autocomplete({
            source: function(request, response) {
                get_residente_search_caseta(request.term, function(result) {
                    response(result);
                });
            },
            minLength: 3,
            select: function(event, ui) {
                get_residente(ui.item.id, function(result) {
                    loadResidente(result);
                    // alert(result.calle);
                    // response(result);
                });
                // alert(ui.item.id);
                // window.location.href = ui.item.url;
            }
        }).data('ui-autocomplete')._renderItem = function(ul, item) {
            return $('<li class="ui-automplete-row"></li>')
                .data('item.autocomplete', item)
                .append(item.label)
                .appendTo(ul);
        }
    }

    if (document.getElementById('search_menu')) {
        $('#search_menu').autocomplete({
            source: function(request, response) {
                get_menus(request.term, function(result) {
                    response(result);
                });
            },
            minLength: 3,
            select: function(event, ui) {
                window.location.href = ui.item.url;
            }
        }).data('ui-autocomplete')._renderItem = function(ul, item) {
            return $('<li class="ui-automplete-row"></li>')
                .data('item.autocomplete', item)
                .append(item.label)
                .appendTo(ul);
        }
    }

    if (document.getElementById('search_menu_mobile')) {
        $('#search_menu_mobile').autocomplete({
            source: function(request, response) {
                get_menus(request.term, function(result) {
                    response(result);
                });
            },
            minLength: 3,
            select: function(event, ui) {
                window.location.href = ui.item.url;
            }
        }).data('ui-autocomplete')._renderItem = function(ul, item) {
            return $('<li class="ui-automplete-row"></li>')
                .data('item.autocomplete', item)
                .append(item.label)
                .appendTo(ul);
        }
    }

    if (document.getElementById('search_calle')) {
        $('#search_calle').autocomplete({
            source: function(request, response) {
                get_calle_search(request.term, function(result) {
                    response(result);
                });
            },
            minLength: 3,
            select: function(event, ui) {
                get_calle(ui.item.id, function(result) {
                    loadCalle(result);
                    // alert(result.calle);
                    // response(result);
                });
                // alert(ui.item.id);
                // window.location.href = ui.item.url;
            }
        }).data('ui-autocomplete')._renderItem = function(ul, item) {
            return $('<li class="ui-automplete-row"></li>')
                .data('item.autocomplete', item)
                .append(item.label)
                .appendTo(ul);
        }
    }

    /*-------------------------------------------
    [ Agregar evento click para Mostrar Mas/Mostrar Menos ]*/
    if (document.querySelector(".mostrar_mas_menos")) {
        let more_less = document.querySelector(".mostrar_mas_menos");
        let mostrar_mas = document.getElementById("mostrar_mas");
        let mostrar_menos = document.getElementById("mostrar_menos");
        more_less.addEventListener("click", function() {
            mostrar_mas.classList.toggle("d-none");
            mostrar_menos.classList.toggle("d-none");
        });
    }


});



/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    fntValidText();
    fntValidEmail();
    fntValidNumber();

    fntAgregarEventosInputIcon();
    fntMostrarPassword();
    fntMostrarPasswordRegister();
    fntInicializarSelects();
    fntInicializarMask();
    fntInicializarTippy();
    fntInitDateRangrPicker();
    fntInicializarDatepicker();

    if (document.querySelector('.form-parsley')) {
        $('.form-parsley').parsley();
    }

    if (document.querySelector('.br-toggle')) {
        // Toggles
        $('.br-toggle').on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('on');
        })
    }

    /*-------------------------------------------
    [ Ajustar max-width para autocomplete de registro.php ]*/

    if (document.getElementById('search_menu')) {
        var input_width = $("#search_menu");
        var anchura = input_width.outerWidth();
        var autocomplete = document.getElementById("ui-id-1");
        anchura = anchura + 'px';
        autocomplete.style.maxWidth = anchura;
    }

    if (document.getElementById('search_menu_mobile')) {
        var input_width = $("#search_menu_mobile");
        var anchura = input_width.outerWidth();
        var autocomplete = document.getElementById("ui-id-2");
        anchura = anchura + 'px';
        autocomplete.style.maxWidth = anchura;
    }

    /*-------------------------------------------
    [ Envia el foco a search residente ]*/
    if (document.getElementById("search_residente")) {

        setTimeout(() => {
            document.getElementById("search_residente").focus();
        }, 500);

        /*-------------------------------------------
        [ Ajustar max-width para autocomplete de registro.php ]*/

        if (document.getElementById('search_residente')) {
            var input_width = $("#search_residente");
            var anchura = input_width.outerWidth();
            var autocomplete = document.getElementById("ui-id-1");
            anchura = anchura + 'px';
            autocomplete.style.maxWidth = anchura;
        }

    }


    /*-------------------------------------------
    [ Envia el foco a search calle ]*/
    if (document.getElementById("search_calle")) {

        setTimeout(() => {
            document.getElementById("search_calle").focus();
        }, 500);

        /*-------------------------------------------
        [ Ajustar max-width para autocomplete de registro.php ]*/

        if (document.getElementById('search_calle')) {
            var input_width = $("#search_calle");
            var anchura = input_width.outerWidth();
            var autocomplete = document.getElementById("ui-id-1");
            anchura = anchura + 'px';
            autocomplete.style.maxWidth = anchura;
        }

    }




}, false);



/*==================================================================
[ Autocomplete ]*/

function get_menus(filtro, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/Home/getMenus/' + filtro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}

function get_residente_search(filtro, result) {

    filtro = filtro.replace(' ', '/');
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/Residentes/getResidenteSearch/' + filtro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}


function get_residente_search_caseta(filtro, result) {

    filtro = filtro.replace(' ', '/');
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/Residentes/getResidenteSearchCaseta/' + filtro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}

function get_calle_search(filtro, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/Catalogos/getCallesSearch/' + filtro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}

/*==================================================================
[ Funciones Get Principales ]*/

function get_residente(filtro, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj.data);
        }
    };
    var ajaxUrl = base_url + '/Residentes/getResidente/' + filtro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}



function get_calle(filtro, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj.data);
        }
    };
    var ajaxUrl = base_url + '/Catalogos/getCalle/' + filtro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}

/*==================================================================
[ Functions Get Ajax ]*/

/**
 * Metodo GET. Obtiene el html a través de ajax, con el metodo GET.
 * @param controller Nombre del controlador a ejecutar. 
 * @param method Nombre del metodo del controlador a ejecutar. 
 * @param filtro Cadena que se desea filtrar.
 * @param html Resultado devuelto por el ajax en formato de cadena en formato html.
 * 
 */
function getFunctionHTML(controller, method, filtro, html) {

    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = xhttp.responseText;
            html(response);
        }
    };
    var ajaxUrl = base_url + '/' + controller + '/' + method + '/' + filtro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.setRequestHeader('Cache-Control', 'no-cache');
    xhttp.send();

}

/**
 * Metodo GET. Obtiene objeto de datos a través de ajax, con el metodo GET.
 * @param controller Nombre del controlador a ejecutar. 
 * @param method Nombre del metodo del controlador a ejecutar. 
 * @param filtro Cadena que se desea filtrar.
 * @param result Resultado devuelto por el ajax en formato de objeto.
 * 
 */
function getFunctionData(controller, method, filtro, result) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(xhttp.responseText);
            result(response);
        }
    };
    var ajaxUrl = base_url + '/' + controller + '/' + method + '/' + filtro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}



/*==================================================================
[ Functions Set Ajax ]*/

/**
 * Metodo POST. Establece, Valida u Obtiene los registros en la base, y recibe un objeto de datos con el resultado de la función ejeuctada a través de ajax, con el metodo POST.
 * @param form Formulario con los datos que se envían al controlador. 
 * @param controller Nombre del controlador a ejecutar. 
 * @param method Nombre del metodo del controlador a ejecutar. 
 * @param result Resultado devuelto por el ajax en formato de objeto.
 * 
 */
function postFunction(form, controller, method, result) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (xhttp.responseText === '') {
                var dataObj = null;
                result(dataObj);
            } else {
                var dataObj = JSON.parse(xhttp.responseText);
                result(dataObj);
            }
        }
    };
    var ajaxUrl = base_url + '/' + controller + '/' + method;
    let formData = new FormData(form);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);
}

/**
 * Metodo POST. Establece, Valida u Obtiene los registros en la base, y recibe un objeto de datos con el resultado de la función ejeuctada a través de ajax, con el metodo POST.
 * @param formData Formulario tipo FormData con los datos que se envían al controlador. 
 * @param controller Nombre del controlador a ejecutar. 
 * @param method Nombre del metodo del controlador a ejecutar. 
 * @param result Resultado devuelto por el ajax en formato de objeto.
 * 
 */
function postFunctionData(formData, controller, method, result) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (xhttp.responseText === '') {
                var dataObj = null;
                result(dataObj);
            } else {
                var dataObj = JSON.parse(xhttp.responseText);
                result(dataObj);
            }
        }
    };
    var ajaxUrl = base_url + '/' + controller + '/' + method;
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);
}


/*==================================================================
[ Funciones Header All Forms ]*/

function fntActivarHorizontalMenu(pageUrl) {

    console.log(pageUrl);

    $(".horizontalMenu-list li a").each(function() {

        // var pageUrl = window.location.href.split(/[?#]/)[0];
        if (this.href == pageUrl) {
            $(this).addClass("active");
            $(this).parent().addClass("active"); // add active to li of the current link
            $(this).parent().parent().prev().addClass("active"); // add active class to an anchor
            $(this).parent().parent().prev().click(); // click the item to make it drop
        }
    });

}


/*==================================================================
[ Funciones Main ]*/

function fntCerrarTabPaneRegistrosMain(idTabPane) {

    var tabClose = idTabPane.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement;
    var idTabPane = tabClose.getAttribute('id');
    var tab_pane = document.getElementById(idTabPane);
    tab_pane.classList.remove('active');
    tab_pane.classList.remove('show');

    var classTabPane = '.' + idTabPane;
    var navTabs = document.querySelector(classTabPane);
    navTabs.classList.remove('active');
    navTabs.classList.remove('show');

}

function fntCerrarTabPaneRegistrosMainCancel(idTabPane) {

    var tabClose = idTabPane.parentElement.parentElement.parentElement.parentElement.parentElement;
    var idTabPane = tabClose.getAttribute('id');
    var tab_pane = document.getElementById(idTabPane);
    tab_pane.classList.remove('active');
    tab_pane.classList.remove('show');

    var classTabPane = '.' + idTabPane;
    var navTabs = document.querySelector(classTabPane);
    navTabs.classList.remove('active');
    navTabs.classList.remove('show');

}


/*==================================================================
[ Funciones Mensajes ]*/

function mensajeSwalGuardar(btnGuardar, mensaje, title, icon, timer) {

    mensajeAlertaModal({
        icon: icon,
        timer: timer,
        title: title,
        text: mensaje,
        textButton: 'Cerrar'
    }).then(function(result) {
        if (result.dismissTimer == true) { btnGuardar.removeAttribute('disabled'); };
        if (result.dismissUser == true) { btnGuardar.removeAttribute('disabled'); }
    })

}

/*-------------------------------------------
[ Mensajes de Alerta Tipo Modal de Oeración del Sistema ]*/

// function mensajeAlertaModal(params) {

//     return new Promise(function(resolve, reject) {

//         try {

//             $('.modalTitle').html(params.title);
//             $('.modalMensaje').html(params.text);
//             $('.modalButtonTitle').html(params.textButton);
//             if (params.textCancelButton == undefined) {
//                 params.textCancelButton = "No";
//                 $('.modalCancelButtonTitle').html(params.textCancelButton);
//             } else {
//                 $('.modalCancelButtonTitle').html(params.textCancelButton);
//             }

//             if (params.icon == 'success') {
//                 var modalAlerta = "modalSuccess";

//             } else if (params.icon == 'warning') {
//                 var modalAlerta = "modalWarning";

//             } else if (params.icon == 'info') {
//                 var modalAlerta = "modalInfo";

//             } else if (params.icon == 'error') {
//                 var modalAlerta = "modalDanger";

//             } else {
//                 var modalAlerta = "modalUndefined";

//             }

//             var modalEl = document.getElementById(modalAlerta);
//             var myModalAlert = new bootstrap.Modal(modalEl, {
//                 keyboard: false
//             })


//             // -------------------------------------------
//             // [ Ejecuta el toggle para mostrar el modal ] ---- //
//             myModalAlert.toggle();


//             // -------------------------------------------
//             // [ Envia resolve para cerrar automaticamente el Modal ] ---- //
//             if (modalAlerta != "modalWarning") {
//                 setTimeout(() => {
//                     myModalAlert.hide()
//                     resolve({
//                         dismissTimer: true,
//                         dismissUser: false,
//                         dismiss: true
//                     });
//                 }, params.timer);
//             }


//             // -------------------------------------------
//             // [ Envia resolve al ejecutarse el evento de cerrar el modal manualmente ] ---- //
//             modalEl.addEventListener('hidden.bs.modal', function(event) {
//                 resolve({
//                     dismissTimer: false,
//                     dismissUser: true,
//                     dismiss: true
//                 });
//             })

//             // -------------------------------------------
//             // [ Envia resolve al ejecutarse el evento de cerrar el modal manualmente ] ---- //
//             var btnWarinign = document.querySelector(".btn-warning-si")
//             btnWarinign.addEventListener('click', function(event) {
//                 resolve({
//                     dismissTimer: false,
//                     dismissUser: true,
//                     dismiss: true,
//                     si: true
//                 });
//             })


//         } catch (error) {
//             reject('error');
//         }

//     });

// }

/*==================================================================
[ Inicializar Mask ]*/


function fntInicializarMask() {

    if (document.querySelector('.inputDateMask')) {
        $('.inputDateMask').mask('99/99/9999');
    }

}

/*==================================================================
[ Inicializar Select2 ]*/

function fntInicializarSelects() {

    if (document.querySelector('#comboOrdenar')) {
        $('#comboOrdenar').select2({
            language: "es",
            placeholder: 'Seleccione una opcion',
            minimumResultsForSearch: Infinity
        });
    }

    if (document.querySelector('#comboStatus')) {
        $('#comboStatus').select2({
            language: "es",
            placeholder: 'Seleccione una opcion',
            minimumResultsForSearch: Infinity
        });
    }

    if (document.querySelector('#comboSexo')) {
        $('#comboSexo').select2({
            language: "es",
            placeholder: 'Seleccione una opcion',
            minimumResultsForSearch: Infinity
        });
    }

}

/*==================================================================
[ Inicializar Datepicker ]*/

function fntInicializarDatepicker() {

    if (document.querySelector('[data-toggle="datepicker"]')) {

        $('[data-toggle="datepicker"]').datepicker({
            language: 'es-ES',
            format: 'dd/mm/yyyy',
            autoHide: true,
            weekStart: 0
        });
    }

    // $.datepicker.regional['es'] = {
    //     closeText: 'Cerrar',
    //     prevText: '< Ant',
    //     nextText: 'Sig >',
    //     currentText: 'Hoy',
    //     monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    //     monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    //     dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    //     dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    //     dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    //     weekHeader: 'Sm',
    //     dateFormat: 'dd/mm/yy',
    //     firstDay: 1,
    //     isRTL: false,
    //     showMonthAfterYear: false,
    //     yearSuffix: ''
    // };

    // $.datepicker.setDefaults($.datepicker.regional['es']);

    // if (document.querySelector('#inputRegisterFechaNacimiento')) {
    //     $('#inputRegisterFechaNacimiento').datepicker();
    // }

}


/*==================================================================
[ Inicializar Wysiwig ]*/
if (document.querySelector('#elm1')) {

    tinymce.init({
        selector: "textarea#elm1",
        language: 'es_MX',
        theme: "modern",
        height: 300,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
        style_formats: [
            { title: 'Bold text', inline: 'b' },
            { title: 'Red text', inline: 'span', styles: { color: '#ff0000' } },
            { title: 'Red header', block: 'h1', styles: { color: '#ff0000' } },
            { title: 'Example 1', inline: 'span', classes: 'example1' },
            { title: 'Example 2', inline: 'span', classes: 'example2' },
            { title: 'Table styles' },
            { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' }
        ]
    });
}


/*==================================================================
[ Inicializar Tippy ]*/

function fntInicializarTippy() {

    if (document.querySelector('.tippy-btn')) {
        tippy('.tippy-btn');
    }

    if (document.querySelector('#ayuda-botones-accion')) {
        document.querySelector('#ayuda-botones-accion__html').style.display = 'block';
        tippy('#ayuda-botones-accion', {
            html: document.querySelector('#ayuda-botones-accion__html'), // DIRECT ELEMENT option
            arrow: true,
            animation: 'fade'
        });
    }

}


/*==================================================================
[ Valida Patrones Inputs ]*/

function controlTag(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8) return true;
    else if (tecla == 0 || tecla == 9) return true;
    patron = /[0-9\s]/;
    n = String.fromCharCode(tecla);
    return patron.test(n);
}

function testText(txtString) {
    var stringText = new RegExp(/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü\s]+$/);
    if (stringText.test(txtString)) {
        return true;
    } else {
        return false;
    }
}

function testEntero(intCant) {
    var intCantidad = new RegExp(/^([0-9])*$/);
    if (intCantidad.test(intCant)) {
        return true;
    } else {
        return false;
    }
}

function fntEmailValidate(email) {
    var stringEmail = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
    if (stringEmail.test(email) == false) {
        return false;
    } else {
        return true;
    }
}

function fntValidText() {
    let validText = document.querySelectorAll(".validText");
    validText.forEach(function(validText) {
        validText.addEventListener('keyup', function() {
            let inputValue = this.value;
            if (!testText(inputValue)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
}

function fntValidNumber() {
    let validNumber = document.querySelectorAll(".validNumber");
    validNumber.forEach(function(validNumber) {
        validNumber.addEventListener('keyup', function() {
            let inputValue = this.value;
            if (!testEntero(inputValue)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
}

function fntValidEmail() {
    let validEmail = document.querySelectorAll(".validEmail");
    validEmail.forEach(function(validEmail) {
        validEmail.addEventListener('keyup', function() {
            let inputValue = this.value;
            if (!fntEmailValidate(inputValue)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
}



/*==================================================================
[ Funciones de Fechas ]*/


/**
 * DateAdd .
 *
 * @param {string} date la fecha en formato dd/mm/yyyy
 * @param {int} days dias que se van a agregar o restar a la fecha.
 * @return {string} fecha ajustada en formato dd/mm/yyyy.
 */
function addDays(date, days) {

    date = Formato_Fecha_yyyymmdd_diag(date);
    var result = new Date(date);

    if (days > 0) {
        result.setDate(result.getDate() + days);
    } else {
        days = days * -1;
        result.setDate(result.getDate() - days);
    }

    var fecha_new = '';
    fecha_new = fillLeft(result.getDate(), 2) + '/' + fillLeft((result.getMonth() + 1), 2) + '/' + result.getFullYear();

    return fecha_new;
}


function fntInitDateRangrPicker() {

    // if ($('#dash_date_nav').length == 0) {
    //     return;
    // }

    // var picker = $('#dash_date_nav');
    // var start = moment();
    // var end = moment();


    // var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    // var diasSemana = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    // var f = new Date();

    // var title = '';
    // title = diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear();
    // picker.find('#Day_Name').html(title);


}


/*==================================================================
[ Funciones de Formato ]*/

/**
 * Llena con ceros a la izquierda de un numero dado.
 *
 * @param {int|float} number. Valor del numero a dar formato con ceros a la izquierda;
 * @param {int} len. Cantidad de ceros a la izquierda que desea colocar;
 * @return {string} Cadena con el numero dado con la cantidad de ceros indicada.
 */
const fillLeft = (number, len) =>
    "0".repeat(len - number.toString().length) + number.toString();

/**
 * Da formato de nombre de mes a un valor numerico de mes espcecificado.
 *
 * @param {int} value. Valor numerico del mes como se obtiene del getMonth();
 * @return {string} meses[value]. Nombre completo del mes.
 */
const mesDescripcion = (value) => {
    var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    return meses[value];
}

// function Formato_Fecha_ddmmyyyy(date) {

//     // date origen = yyyy-mm-dd
//     var parts = date.split('-');
//     var dia = parts[2].substring(0, 2);
//     var fecha_res = dia + '/' + parts[1] + '/' + parts[0];
//     return fecha_res;

// }


/**
 * Modifica formato de una cadena de fecha.
 *
 * @param {string} date la fecha en formato yyyy-mm-dd
 * @return {string} fecha en formato dd/mm/yyyy.
 */
function Formato_Fecha_ddmmyyyy(date) {

    // date origen = yyyy-mm-dd
    var parts = date.split('-');
    var dia = parts[2].substring(0, 2);
    var fecha_res = dia + '/' + parts[1] + '/' + parts[0];
    return fecha_res;

}

/**
 * Modifica formato de una cadena de fecha.
 *
 * @param {string} date la fecha en formato dd/mm/yyyy
 * @return {string} fecha en formato yyyy/mm/dd.
 */
function Formato_Fecha_yyyymmdd_diag(date) {

    // date origen = dd/mm/yyyy
    var parts = date.split('/');
    var fecha_res = parts[2] + '/' + parts[1] + '/' + parts[0];
    return fecha_res;
}


/**
 * Modifica formato de una cadena de fecha.
 *
 * @param {string} date la fecha en formato dd/mm/yyyy
 * @return {string} fecha en formato mm/dd/yyyy.
 */
function Formato_Fecha_mmddyyyy(date) {

    // date origen = dd/mm/yyyy
    var parts = date.split('/');
    var fecha_res = parts[1] + '/' + parts[0] + '/' + parts[2];
    return fecha_res;
}


/**
 * Modifica formato de una cadena de fecha.
 *
 * @param {string} date la fecha en formato dd/mm/yyyy
 * @return {string} fecha en formato yyyy-mm-dd.
 */
function Formato_Fecha_yyyymmdd(date) {

    var parts = date.split('/');
    var fecha_res = parts[2] + '-' + parts[1] + '-' + parts[0];
    return fecha_res;
}

/**
 * Da formato de moneda a un monto espcecificado.
 *
 * @param {float} monto. Valor del monto a dar formato
 * @return {string} Cadena con el valor del monto en formato de cadena con el signo de la moneda.
 */
function Formato_Moneda(monto) {
    monto_convertido = new Intl.NumberFormat("es-MX", { style: "currency", currency: "MXN" }).format(monto);
    return monto_convertido;
}

/**
 * Da formato de nombre de mes a un valor numerico de mes espcecificado.
 *
 * @param {int} mes. Mes en formato de numero entero como se obtiene de getMonth();
 * @return {string} mes_name. Cadena con el nombre completo del mes.
 */
function Formato_MesName(mes) {

    var arrMeses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    var mes_name;
    mes_base = parseInt(mes);
    mes_name = arrMeses[mes_base];

    return mes_name;
}



/*==================================================================
[ Form ]*/

function resetForm(formElement) {

    /*-------------------------------------------
      [ Reset Form ]*/
    formElement.reset();

    /*-------------------------------------------
      [ Reset Selects ]*/
    if (document.querySelector(".select2")) {
        var selectsForm = document.querySelectorAll(".select2");
        selectsForm.forEach(function myFunction(item, index) {
            selectsForm[index].value = "";
            $(selectsForm[index]).trigger('change');
        });
    }

    // var selectsForm = document.querySelectorAll(".selectForm100");
    // selectsForm.forEach(function myFunction(item, index) {
    //     selectsForm[index].value = "";
    //     $(selectsForm[index]).trigger('change');
    // });

    /*-------------------------------------------
    [ Reset Campos Solo Lectura ]*/
    if (document.querySelector("p.pForm100")) {
        var pForm = document.querySelectorAll("p.pForm100");
        pForm.forEach(function myFunction(item, index) {
            pForm[index].innerHTML = "";
        });
    }


    /*-------------------------------------------
    [ Reset parsley ]*/
    var formEl = formElement.getAttribute('id');
    formEl = '#' + formEl;
    $(formEl).parsley().reset();


}

function resetFormNoPasley(formElement) {

    /*-------------------------------------------
      [ Reset Form ]*/
    formElement.reset();

    /*-------------------------------------------
      [ Reset Selects ]*/
    if (document.querySelector(".select2")) {
        var selectsForm = document.querySelectorAll(".select2");
        selectsForm.forEach(function myFunction(item, index) {
            selectsForm[index].value = "";
            $(selectsForm[index]).trigger('change');
        });
    }

}


function resetFormNew(formElement, form_id = "") {

    /*-------------------------------------------
    [ Reset Form ]*/
    formElement.reset();

    /*-------------------------------------------
      [ Reset Selects ]*/
    let selector = form_id + ' .select2';
    if (document.querySelector(selector)) {
        var selectsForm = document.querySelectorAll(selector);
        selectsForm.forEach(function myFunction(item, index) {
            selectsForm[index].value = "";
            $(selectsForm[index]).trigger('change');
        });
    }

}


/* -------------------------------------
[ Detener el submit on Entrer ]*/

function stopSubmitOnEnter(event) {

    var x = event.key;
    if (x == "Enter") {
        event.preventDefault();
    }

}

/* -------------------------------------
[ SendKeys en Input ]*/

function sendKeysTabOnEnterInInput(event) {

    var x = event.key;
    if (x == "Enter") {
        var eL = evt.currentTarget;
        $(eL).next().focus();
    }

}

/* -------------------------------------
[ Validaciones  ]*/

function validaInputsForm() {

    var input = document.querySelectorAll("input.inputForm100");

    for (let index = 0; index < input.length; index++) {
        const element = input[index];
        if (element.value == '') {
            return false;
        }
    }

    return true;

}

function validaSelectsForm() {

    var select = document.querySelectorAll("select.selectForm100");

    for (let index = 0; index < select.length; index++) {
        const element = select[index];
        if (element.value == '' || element.value == undefined) {
            return false;
        }
    }

    return true;
}

/* -------------------------------------
[ Funciones Loading Button ]*/

function removerClasesButtonGuardar(btnGuardar, loading) {

    // -- Habilitar Boton Guardar --
    btnGuardar.removeAttribute('disabled');

    // -- Remove Spin de Boton Guardar --
    loading.style.display = "none";

}

function agregarLoadingButtonGuardar(btnGuardar) {

    /*-------------------------------------------
    [ Deshabilita para evitar doble registro ]*/
    btnGuardar.setAttribute('disabled', 'disabled');

    /*-------------------------------------------
    [ Agrega Loadign para hacer el efecto (before de ajax) ]*/
    var children = btnGuardar.childNodes;

    children = children[1].childNodes;
    loading = children[5];
    loading.style.display = "block";

}

function addLoadingButton(btnElement, class_old) {

    /*-------------------------------------------
    [ Deshabilita para evitar doble registro ]*/
    var btnParent = btnElement.parentNode;
    btnParent.setAttribute('disabled', 'disabled');

    /*-------------------------------------------
    [ Agrega Loadign para hacer el efecto (before de ajax) ]*/
    btnElement.classList.remove(class_old);
    btnElement.classList.add('fa-cog', 'fa-spin');

}

function removeLoadingButton(btnElement, class_old) {

    /*-------------------------------------------
    [ Habilita el boton ]*/
    var btnParent = btnElement.parentNode;
    btnParent.removeAttribute('disabled', 'disabled');


    /*-------------------------------------------
    [ Remueve Loadign para hacer el efecto (after de ajax) ]*/
    btnElement.classList.remove('fa-cog', 'fa-spin');
    btnElement.classList.add(class_old);

}


function agregarLoadingButtonOpcionesDataTable(btnOpciones, class_old) {

    /*-------------------------------------------
    [ Deshabilita para evitar doble acción ]*/
    btnOpciones.setAttribute('disabled', 'disabled');

    /*-------------------------------------------
    [ Agrega Loadign para hacer el efecto (before de ajax) ]*/
    // 0: text
    // 1: i.fa-regular.tx-14.fa-cog.fa-spin.fa-eye
    // 2: text
    var children = btnOpciones.childNodes;
    loading = children[1];
    loading.classList.remove(class_old);
    loading.classList.add('fa-cog', 'fa-spin');


}


function restablecerButtonOpcionesDataTable(btnOpciones, class_old) {

    /*-------------------------------------------
    [ Deshabilita para evitar doble acción ]*/
    btnOpciones.removeAttribute('disabled');

    /*-------------------------------------------
    [ Agrega Loadign para hacer el efecto (before de ajax) ]*/
    // 0: text
    // 1: i.fa-regular.tx-14.fa-cog.fa-spin.fa-eye
    // 2: text
    var children = btnOpciones.childNodes;
    loading.classList.remove('fa-cog', 'fa-spin');
    loading = children[1];
    loading.classList.add(class_old);

}


/*==================================================================
[ DataTable ]*/

function ReDesignButonExcel() {

    if (document.querySelector(".dt-buttons button")) {
        var boton_excel = document.querySelectorAll('.dt-buttons button');
        for (let index = 0; index < boton_excel.length; index++) {
            boton_excel[index].classList.add("ripple");
            boton_excel[index].classList.add("bg-btn-excel");
            boton_excel[index].classList.add("btn-pill");
            boton_excel[index].classList.remove("btn-secondary");
            boton_excel[index].classList.add("btn-outline-light");
            boton_excel[index].style.setProperty("color", "#333");
            boton_excel[index].style.setProperty("z-index", "400");
        }
        var boton_excel_icon = document.querySelectorAll('.dt-buttons button span i');
        for (let index = 0; index < boton_excel_icon.length; index++) {
            boton_excel_icon[index].classList.remove("fas");
            boton_excel_icon[index].classList.add("fa-regular");
            boton_excel_icon[index].classList.add("tx-16");
            // <i class="fa-regular fa-file-excel"></i>
        }

    }

    if (document.querySelector(".dt-buttons .btn-group button")) {
        var boton_visibilidad = document.querySelectorAll('.dt-buttons .btn-group button');
        for (let index = 0; index < boton_visibilidad.length; index++) {
            boton_visibilidad[index].style.marginLeft = "7px";
            boton_visibilidad[index].style.width = "130px";
            boton_visibilidad[index].classList.add("ripple");
            boton_visibilidad[index].classList.add("btn-pill");
            boton_visibilidad[index].classList.remove("btn-secondary");
            boton_visibilidad[index].classList.add("btn-outline-light");
            boton_visibilidad[index].style.setProperty("color", "#333");
            boton_visibilidad[index].style.setProperty("margin-left", "95px");
        }
        // boton_visibilidad.style.marginLeft = "7px";
        // boton_visibilidad.classList.add("ripple");
        //margin-left: 85px;  min-width: 113px;
    }

}

function validaPermisoExportar(modulo_id) {
    /*-------------------------------------------
     [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let permiso_exportar_excel = parseInt(xhttp.responseText);
            if (permiso_exportar_excel == 0) {
                var btnExcel = document.querySelectorAll(".buttons-excel");
                for (let index = 0; index < btnExcel.length; index++) {
                    const element = btnExcel[index];
                    element.style.display = "none";
                }
            }
        }
    };
    let ajaxUrl = base_url + '/Permisos/getPermisosExcel/' + modulo_id;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}


/*-------------------------------------------
[ Valida Inputs ]*/

function validaInputs(input) {

    input.classList.add('is-invalid');
    var eLValidGroup = input.previousElementSibling;
    if (eLValidGroup == null) {
        eLValidGroup = input.parentElement;
        eLValidGroup = eLValidGroup.previousElementSibling;
    }
    eLValidGroup.parentElement;
    eLValidGroup.classList.add('has-danger');

}

function limpiarInputs(input) {

    input.classList.remove('is-invalid');
    var eLValidGroup = input.previousElementSibling;
    if (eLValidGroup == null) {
        eLValidGroup = input.parentElement;
        eLValidGroup = eLValidGroup.previousElementSibling;
    }
    eLValidGroup.parentElement;
    eLValidGroup.classList.remove('has-danger');

}


/*-------------------------------------------
[ Funciones Inputs Elementos Laterales ]*/

function fntAgregarEventosInputIcon() {

    let inputs = document.querySelectorAll(".input100");
    inputs.forEach(function(inputs) {
        inputs.addEventListener('focusin', function() {
            // var input_icon = this.previousElementSibling
            // input_icon.classList.add("bg-input");
        });

        inputs.addEventListener('focusout', function() {
            // var input_icon_out = this.previousElementSibling
            // input_icon_out.classList.remove("bg-input");
        });

    });

}


/*-------------------------------------------
[ Funciones Inputs Mostrara/Ocultar Password ]*/

function fntMostrarPassword() {

    let input_password = document.querySelectorAll(".show-password i");

    input_password.forEach(function(input_password) {

        input_password.addEventListener('click', function() {

            var doc = this.parentElement.parentElement;
            for (var i = 0; i < doc.children.length; i++) {
                if (doc.children[i].tagName == "INPUT") {
                    passwordInput = doc.children[i];
                }
            }

            if (this.classList.contains('mostrar-password')) {
                this.classList.remove('mostrar-password');
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
                passwordInput.type = 'text';
            } else {
                this.classList.add('mostrar-password');
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
                passwordInput.type = 'password';
            }
        });

    });

}


function fntMostrarPasswordRegister() {

    let input_password = document.querySelectorAll(".show-password-register i");
    input_password.forEach(function(input_password) {
        input_password.addEventListener('click', function() {

            var doc = this.parentElement.parentElement;
            for (var i = 0; i < doc.children.length; i++) {
                if (doc.children[i].tagName == "INPUT") {
                    passwordInput = doc.children[i];
                }
            }

            if (this.classList.contains('mostrar-password')) {
                this.classList.remove('mostrar-password');
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
                passwordInput.type = 'text';
            } else {
                this.classList.add('mostrar-password');
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
                passwordInput.type = 'password';
            }
        });

    });

}