/*==================================================================
[ Variables de Archivo ]*/



/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*==================================================================
    [ Consultorio ]*/

    /*-------------------------------------------
     [ Select - Agregar evento onchange a select Paises ]*/

    if (document.getElementById("comboPais")) {
        var selectElement = document.getElementById('comboPais');
        selectElement.onchange = function() { cargarEntidades() };
    }

    /*-------------------------------------------
     [ Select - Agregar evento onchange a select Entidades ]*/

    if (document.getElementById("comboEntidad")) {
        var selectElement = document.getElementById('comboEntidad');
        selectElement.onchange = function() { cargarMunicipios() };
    }

    /*-------------------------------------------
    [ Select - Agregar evento onchange a select Municipio ]*/

    if (document.getElementById("comboMunicipio")) {
        var selectElement = document.getElementById('comboMunicipio');
        selectElement.onchange = function() { cargarCodigosPostales() };
    }

    /*-------------------------------------------
    [ Select - Agregar evento onchange a select Codigo Postal ]*/

    if (document.getElementById("comboCodigoPostal")) {
        var selectElement = document.getElementById('comboCodigoPostal');
        selectElement.onchange = function() { cargarColonia() };
    }

    /*-------------------------------------------
     [ Agregar evento click a Editar Configuración de Datos Generales de Unidad Medica  ]*/

    if (document.getElementById("btnEditar_Confifg_DatGenUnidadMedica")) {
        var btnElement = document.getElementById('btnEditar_Confifg_DatGenUnidadMedica');
        btnElement.onclick = function() { fntEditarConfigDatGenUnidadMedica() };
    }

    /*-------------------------------------------
     [ Agregar evento click a Cancelar Edicion de Configuración de Datos Generales de Unidad Medica  ]*/

    if (document.getElementById("btnCancelar_Confifg_DatGenUnidadMedica")) {
        var btnElement = document.getElementById('btnCancelar_Confifg_DatGenUnidadMedica');
        btnElement.onclick = function() { fntCancelarConfigDatGenUnidadMedica() };
    }

    /*-------------------------------------------
      [ Form - Agregar evento submit al formulario de registro de Unidad Medica]*/
    if (document.getElementById('formConfigDatosGeneralesUnidadMedica')) {
        var formConfigDatosGeneralesUnidadMedica = document.getElementById('formConfigDatosGeneralesUnidadMedica');
        formConfigDatosGeneralesUnidadMedica.addEventListener("submit", function(event) { setUnidadMedica(event) });
    }

});

/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    /*-------------------------------------------
    [ Funciones Fill Selects ]*/
    // fillSelectTipoUnidadesMedicas();
    fillSelectTipoVialidades();
    fillSelectTipoAsentamiento();
    fillSelectPais();
    // fillSelectMunicipio();
    // fillSelectCodigoPostal();

    /*-------------------------------------------
    [ Funciones Load Data ]*/
    getDatosUnidadMedica();

    /*-------------------------------------------
    [ Activa el menu horizontal (prinicipal) correspondiente del sidemenu ]*/
    var pageUrl = base_url + "/herramientas"
    fntActivarHorizontalMenu(pageUrl);


}, false)


/*==================================================================
[ Funciones ]*/

function getUnidadMedica(idUnidad, fntData) {

    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let responseObj = JSON.parse(xhttp.responseText);
            if (responseObj.respuesta == "ok") {
                fntData(responseObj);
            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) {};
                    if (result.dismissUser == true) {}
                });
            }
        }
    };

    var ajaxUrl = base_url + '/UnidadMedica/getUnidadMedica/' + idUnidad;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();

}

/* -------------------------------
[ Funciones de Geolocalizacion ]*/

function initMap(lat, lng) {

    lat = parseFloat(lat);
    lng = parseFloat(lng);

    var x = document.getElementById('map');

    var coordenadas = {
        lat: lat,
        lng: lng
    };
    showGoogleMap(coordenadas);

    function showGoogleMap(coordenadas) {

        var mapa = new google.maps.Map(document.getElementById('map'), {
            zoom: 18,
            center: coordenadas
        });

        var marker = new google.maps.Marker({
            position: coordenadas,
            map: mapa
        });

    }

}

function initMapPosicionActual() {

    var x = document.getElementById('map');

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        x.innerHTML = "No es compatible tu navegador";
    }

    function showPosition(position) {

        var coordenadas = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };
        showGoogleMap(coordenadas);

    }

    function showGoogleMap(coordenadas) {

        var mapa = new google.maps.Map(document.getElementById('map'), {
            zoom: 18,
            center: coordenadas
        });

        var marker = new google.maps.Marker({
            position: coordenadas,
            map: mapa
        });

    }

}

/* -------------------------------
[ Funciones de Plugins ]*/

function dropify() {

    $('.dropify').dropify({
        messages: {
            'default': 'Arrastre y suelte un archivo aquí o haga click',
            'replace': 'Arrastre y suelte o haga click para reemplazar',
            'remove': 'Remover',
            'error': 'Ooops, algo salió mal al anexar el archivo.'
        },
        error: {
            'fileSize': 'El tamaño del archivo es demasiado grande (2M máx.)'
        }
    });

}


/*==================================================================
[ Funciones de Eventos ]*/

function cargarEntidades() {
    var pais_id = $('#comboPais option:selected').val();
    fillSelectEntidad(pais_id);
}

function cargarMunicipios() {
    var entidad_id = $('#comboEntidad option:selected').val();
    fillSelectMunicipio(entidad_id);
}

function cargarCodigosPostales() {
    var municipio_id = $('#comboMunicipio option:selected').val();
    var entidad_id = $('#comboEntidad option:selected').val();
    fillSelectCodigoPostal(entidad_id, municipio_id);
}

function cargarColonia() {
    var codigo_postal_id = $('#comboCodigoPostal option:selected').val();
    fntSelectColonia(codigo_postal_id);
}


/*==================================================================
[ Funciones Llenar Selects e Inicializa Select2]*/

// function fillSelectTipoUnidadesMedicas() {

//     if (document.querySelector('#comboTipoUnidad')) {

//         //Ajax LLenar Select de catalogo de Especialidades
//         var xhttp = new XMLHttpRequest();
//         xhttp.onreadystatechange = function() {

//             if (this.readyState == 4 && this.status == 200) {

//                 document.querySelector('#comboTipoUnidad').innerHTML = xhttp.responseText;

//                 /*-------------------------------------------
//                 [ Inicializa Select2 Mulitselect]*/
//                 $('#comboTipoUnidad').select2({
//                     language: "es",
//                     placeholder: 'Seleccione una opcion',
//                     minimumResultsForSearch: Infinity
//                 });

//                 //Asignar Valor Default después de Inicializar Seleclt2
//                 // $('#comboTipoUnidad').val('1');
//                 // $('#comboTipoUnidad').trigger('change');

//             }

//         };
//         var ajaxUrl = base_url + '/Catalogos/getSelectTiposUnidad';
//         xhttp.open("GET", ajaxUrl, true);
//         xhttp.send();

//     }

// }

function fillSelectTipoVialidades() {

    if (document.querySelector('#comboTipoVialidad')) {

        //Ajax LLenar Select de catalogo de Especialidades
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboTipoVialidad').innerHTML = xhttp.responseText;

                /*-------------------------------------------
                [ Inicializa Select2 Mulitselect]*/
                $('#comboTipoVialidad').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion'
                });

                //Asignar Valor Default después de Inicializar Seleclt2
                $('#comboTipoVialidad').val('5');
                $('#comboTipoVialidad').trigger('change');

            }

        };
        var ajaxUrl = base_url + '/Catalogos/getSelectTipoVialidades';
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();

    }

}

function fillSelectTipoAsentamiento() {

    if (document.querySelector('#comboTipoAsentamiento')) {

        //Ajax LLenar Select de catalogo de Especialidades
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboTipoAsentamiento').innerHTML = xhttp.responseText;

                /*-------------------------------------------
                [ Inicializa Select2 Mulitselect]*/
                $('#comboTipoAsentamiento').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion'
                });

                //Asignar Valor Default después de Inicializar Seleclt2
                $('#comboTipoAsentamiento').val('7');
                $('#comboTipoAsentamiento').trigger('change');

            }

        };
        var ajaxUrl = base_url + '/Catalogos/getSelectTipoAsentamientos';
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();

    }

}

function fillSelectMunicipio(entidad_id = 0) {

    if (document.querySelector('#comboMunicipio')) {

        //Ajax LLenar Select de catalogo de Especialidades
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboMunicipio').innerHTML = xhttp.responseText;

                /*-------------------------------------------
                [ Inicializa Select2 Mulitselect]*/
                $('#comboMunicipio').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion'
                });

                //Asignar Valor Default después de Inicializar Seleclt2
                var municipio_id = document.getElementById("comboMunicipio").getAttribute("data-id");
                if (municipio_id == '') {
                    $('#comboMunicipio').val('');
                    $('#comboMunicipio').trigger('change');
                } else {
                    $('#comboMunicipio').val(municipio_id);
                    $('#comboMunicipio').trigger('change');
                }

            }

        };
        var ajaxUrl = base_url + '/Catalogos/getSelectMunicipios/' + entidad_id;
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();

    }

}

function fillSelectEntidad(pais_id = 0) {

    if (document.querySelector('#comboEntidad')) {

        //Ajax LLenar Select de catalogo de Especialidades
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboEntidad').innerHTML = xhttp.responseText;

                /*-------------------------------------------
                [ Inicializa Select2 Mulitselect]*/
                $('#comboEntidad').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion'
                });

                //Asignar Valor Default después de Inicializar Seleclt2
                var entidad_id = document.getElementById("comboEntidad").getAttribute("data-id");
                if (entidad_id == 0) {
                    $('#comboEntidad').val('');
                    $('#comboEntidad').trigger('change');
                } else {
                    $('#comboEntidad').val(entidad_id);
                    $('#comboEntidad').trigger('change');
                }

            }

        };
        var ajaxUrl = base_url + '/Catalogos/getSelectEntidades/' + pais_id;
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();

    }

}

function fillSelectPais() {

    if (document.querySelector('#comboPais')) {
        //Ajax LLenar Select de catalogo de Especialidades
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector('#comboPais').innerHTML = xhttp.responseText;
                /*-------------------------------------------
                [ Inicializa Select2 ]*/
                $('#comboPais').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion'
                });
                //Asignar Valor Default después de Inicializar Seleclt2
                var pais_id = document.getElementById("comboPais").getAttribute("data-id");
                if (pais_id == '') {
                    $('#comboPais').val('136');
                    $('#comboPais').trigger('change');
                } else {
                    $('#comboPais').val(pais_id);
                    $('#comboPais').trigger('change');
                }
            }
        };
        var ajaxUrl = base_url + '/Catalogos/getSelectPaises';
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();
    }

}

function fillSelectCodigoPostal(entidad_id = 0, minicipio_id = 0) {

    if (document.querySelector('#comboCodigoPostal')) {

        //Ajax LLenar Select de catalogo de Especialidades
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboCodigoPostal').innerHTML = xhttp.responseText;

                /*-------------------------------------------
                [ Inicializa Select2 Mulitselect]*/
                $('#comboCodigoPostal').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion'
                        // tags: true
                });

                //Asignar Valor Default después de Inicializar Seleclt2
                var cp_id = document.getElementById("comboCodigoPostal").getAttribute("data-id");
                if (cp_id == '') {
                    $('#comboCodigoPostal').val('');
                    $('#comboCodigoPostal').trigger('change');
                } else {
                    $('#comboCodigoPostal').val(cp_id);
                    $('#comboCodigoPostal').trigger('change');
                }

            }

        };
        var ajaxUrl = base_url + '/Catalogos/getSelectCodigoPostal/' + entidad_id + '/' + minicipio_id;
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();

    }

}

function fntSelectColonia(codigo_postal_id = 0) {

    if (codigo_postal_id == 0) {
        document.querySelector('#inputRegisterColonia').value = "";
        return;
    }

    if (document.querySelector('#inputRegisterColonia')) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var objDataResponse = JSON.parse(xhttp.responseText);
                if (objDataResponse.respuesta == "ok") {
                    document.querySelector('#inputRegisterColonia').value = objDataResponse.data.colonia;
                } else {
                    document.querySelector('#inputRegisterColonia').value = "";
                }
            }
        };
        var ajaxUrl = base_url + '/Catalogos/getCodigoPostal/' + codigo_postal_id;
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();
    }
}


/*==================================================================
[ Funciones de Manejo de Datos de Unidad Medica para Set, Edit and View ]*/

function setUnidadMedica(e) {


    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnGuardar_DatGen_UnidadMedica');
    agregarLoadingButtonGuardar(btnGuardar);


    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();


    /*-------------------------------------------
    [ Valida Inputs que no estpen vacíos. ]*/
    var check = true;
    var input = document.querySelectorAll("input.inputForm100");

    for (let index = 0; index < input.length; index++) {
        const element = input[index];
        if (element.value == '') {
            validaInputs(element);
            check = false;
        }
    }
    if (!check) {

        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe llenar los campos requeridos.',
            textButton: 'Cerrar'
        }).then(function(result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return false;
    }


    /*-------------------------------------------
    [ Valida Selects que no estpen vacíos. ]*/
    if (!check) {
        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe llenar los campos requeridos.',
            textButton: 'Cerrar'
        }).then(function(result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return false;
    }


    /*-------------------------------------------
    [ Verifica Resultado de Validaciones ]*/
    if (!check) {
        return false;
    }



    /*-------------------------------------------
    [ Ajax ]*/
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {

            var responseObj = JSON.parse(xhttp.responseText);


            if (responseObj.respuesta == "ok") {

                getDatosUnidadMedica();

                mensajeAlertaModal({
                    icon: 'success',
                    timer: 4000,
                    title: iconMensajeSuccess + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                    if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
                });

            } else {

                mensajeAlertaModal({
                    icon: 'error',
                    timer: 4000,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                    if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
                });
            }

        }

    };
    var ajaxUrl = base_url + '/UnidadMedica/setUnidadMedica';
    var formData = new FormData(formConfigDatosGeneralesUnidadMedica);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}

function getDatosUnidadMedica() {

    /*-------------------------------------------
    [ Obtiene id de registro ]*/
    var idRegistro = document.getElementById('btnGuardar_DatGen_UnidadMedica').getAttribute("data-id");
    getUnidadMedica(idRegistro, function ftnData(responseObj) {
        /*-------------------------------------------
        [ Asigar datos de Unidad Medica Obtenidos ]*/
        caragrVistaUnidadMedica(responseObj);
        caragrFormEditUnidadMedica(responseObj);

        /*-------------------------------------------
        [ Inicializa dropify para elementos de Upload Files ]*/
        dropify();

    })

}

function caragrVistaUnidadMedica(responseObj) {

    /*-------------------------------------------
    [ Logo ]*/
    var logo_actual_read = document.getElementById("logo_actual_read");
    if (responseObj.data.logo !== null) {
        logo_actual_read.innerHTML = `<img style="max-width: 100%; height:120px;" src="${assets}/images/logos/${responseObj.data.logo}" alt="Logo de la empresa" class="logo-uploader__image img-fluid img-thumbnail"></img>`;
    }


    /*-------------------------------------------
    [ Cargar en DOM data-id de selects para almacenar los valores de la DB]*/
    document.getElementById("comboPais").setAttribute("data-id", responseObj.data.pais_id);
    document.getElementById("comboEntidad").setAttribute("data-id", responseObj.data.entidad_id);
    document.getElementById("comboMunicipio").setAttribute("data-id", responseObj.data.municipio_id);
    document.getElementById("comboCodigoPostal").setAttribute("data-id", responseObj.data.cp_id);


    /*-------------------------------------------
    [ Datos Generales y de Contacto ]*/
    document.getElementById("inputRegisterNombreUnidad_read").innerHTML = responseObj.data.nombre_unidad;

    if (responseObj.data.telefono_unidadmedica != '') {
        document.getElementById("inputRegisterTelefonoUnidadMedica_read").innerHTML = responseObj.data.telefono_unidadmedica;
    }

    if (responseObj.data.email_contacto_unidadmedica != '') {
        document.getElementById("inputRegisterEmailUnidadMedica_read").innerHTML = responseObj.data.email_contacto_unidadmedica;
    }


    /*-------------------------------------------
    [ Servicios de Unidad ]*/
    var servicios_unidad = responseObj.data.servicios;
    var lista_servicios = "";

    for (let index = 0; index < servicios_unidad.length; index++) {
        var id = servicios_unidad[index]['id'];
        var descripcion = servicios_unidad[index]['descripcion'];
        var precio = Formato_Moneda(servicios_unidad[index]['precio']);
        var clase = "";
        if ((index % 2) == 0) {
            clase = "bg-primary"
        } else {
            clase = "bg-secondary"
        }

        lista_servicios += `<li data-id="${id}">
                                    <i class="task-icon ${clase}"></i>
                                    <h6>${descripcion}<span class="text-muted fs-11 ms-2">${precio}</span></h6>
                                </li>`;
    }

    if (servicios_unidad.length > 0) {
        document.getElementById("servicios_unidad").innerHTML = lista_servicios;
    }


    /*-------------------------------------------
    [ Datos Geograficos ]*/
    if (responseObj.data.domicilio_id != null && responseObj.data.domicilio_id != 0) {
        document.getElementById("comboPais_read").innerHTML = responseObj.data.pais;
        console.log(responseObj.data.pais);

        var group_domicilio = document.getElementById("groupDomicilio_read");
        group_domicilio.innerHTML = `${responseObj.data.calle} ${responseObj.data.num_ext} ${responseObj.data.num_int} Colonia: ${responseObj.data.colonia} Entidad: ${responseObj.data.entidad} Municipio:${responseObj.data.municipio} Codigo Postal: ${responseObj.data.codigo_postal}`;

        if (responseObj.data.referencia != "") {
            document.getElementById("inputRegisterReferencia_read").innerHTML = `<a href="${responseObj.data.referencia}" target="_blank">${responseObj.data.referencia}</a>`;
        }

        /*-------------------------------------------
          [ Mostrar Mapa de Google ]*/
        if (responseObj.data.referencia != '') {
            document.getElementById("google-maps-db").setAttribute("href", "https://www.google.com/maps/");
        } else {
            document.getElementById("google-maps-db").setAttribute("href", responseObj.data.referencia);
        }

        // ------ Mostrar en Google Maps ------

        // let str = responseObj.data.referencia;
        // var myArr = str.split("@");
        // var str2 = myArr[1];
        // var myArr2 = str2.split(",");
        // var lat = myArr2[0];
        // var lng = myArr2[1];
        // console.log(lat);
        // console.log(lng);
        // setTimeout(() => {
        //     initMap(lat, lng);
        // }, 500);

    }

}


function caragrFormEditUnidadMedica(responseObj) {

    /*-------------------------------------------
    [ Logo ]*/
    var img = document.getElementById("inputRegisterLogoUnidad")
    if (responseObj.data.logo != null) {
        var src = assets + "/images/logos/" + responseObj.data.logo;
        img.setAttribute("data-default-file", src)
    }

    /*-------------------------------------------
    [ Input Hide ]*/
    document.getElementById("inputIdUnidadMedica").value = responseObj.data.id;
    document.getElementById("inputIdDomicilio").value = responseObj.data.domicilio_id;
    document.getElementById("inputLogo").value = responseObj.data.logo;


    /*-------------------------------------------
    [ Datos Generales y de Contacto ]*/
    document.getElementById("inputRegisterNombreUnidad").value = responseObj.data.nombre_unidad;
    document.getElementById("inputRegisterEmailUnidadMedica").value = responseObj.data.email_contacto_unidadmedica;
    document.getElementById("inputRegisterTelefonoUnidadMedica").value = responseObj.data.telefono_unidadmedica;


    /*-------------------------------------------
    [ Servicios de Unidad ]*/

    var servicios_unidad_edit = responseObj.data.servicios;
    var lista_servicios_edit = "";

    for (let index = 0; index < servicios_unidad_edit.length; index++) {

        var id_edit = servicios_unidad_edit[index]['id'];
        var descripcion_edit = servicios_unidad_edit[index]['descripcion'];
        var precio_edit = servicios_unidad_edit[index]['precio'];

        lista_servicios_edit += `<div data-repeater-item="" class="row">

                                    <input type="hidden" name="servicios[${index}][idServicio]" value="${id_edit}">
                                  
                                    <div class="form-group col-12 col-lg-6">
                                        <label class="login-label" for="inputServicio">Servicio:</label>
                                        <span class="bar-left-input-row"><i class="fa-thin fa-circle-check fa-fw tx-18 lh-0 op-6"></i></span>
                                        <input type="text" class="form-control inputForm100" name="servicios[${index}][inputServicio]" id="inputServicio" value="${descripcion_edit}" placeholder="Ingrese Servicio" autocomplete="off" data-parsley-required>
                                    </div>

                                    <div class="form-group col-12 col-lg-6">
                                        <label class="login-label" for="inputPrecioServicio">Precio:</label>
                                        <span class="bar-left-input-row"><i class="fa-thin fa-circle-dollar fa-fw tx-18 lh-0 op-6"></i></span>
                                        <div class="d-flex">
                                            <input type="text" style="margin-right: 5px;" class="form-control inputForm100" value="${precio_edit}" name="servicios[${index}][inputPrecioServicio]" id="inputPrecioServicio" placeholder="Ingrese Precio del Servicio" autocomplete="off" data-parsley-required>
                                            <span onclick="delete_servicio(${id_edit})" data-repeater-delete="" class="btn btn-outline-danger" style="height:33px; box-shadow: none!important;">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group col-12 d-lg-none">
                                        <div class="border-bottom subtitulos_panel">
                                        </div>
                                    </div>
                                </div>`;
    }

    if (servicios_unidad_edit.length > 0) {
        document.getElementById("servicios_edit").innerHTML = lista_servicios_edit;
    }


    /*-------------------------------------------
    [ Datos Geograficos ]*/
    if (responseObj.data.domicilio_id != null && responseObj.data.domicilio_id != 0) {

        /*-------------------------------------------
        [ Asigna los valores de selects (combos)]*/
        fillSelectPais();

        /*-------------------------------------------
        [ Asigna los valores de inputs Edit]*/
        document.getElementById("inputRegisterCalle").value = responseObj.data.calle;
        document.getElementById("inputRegisterNumExt").value = responseObj.data.num_ext;
        document.getElementById("inputRegisterNumInt").value = responseObj.data.num_int;
        document.getElementById("inputRegisterColonia").value = responseObj.data.colonia;
        document.getElementById("inputRegisterReferencia").value = responseObj.data.referencia;

    }

}

function fntEditarConfigDatGenUnidadMedica() {

    var editar = document.getElementById('editar_config_datgen_consultorio');
    var view = document.getElementById('view_config_datgen_consultorio');
    view.style.display = "none";
    editar.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    var eLOrigen = $('.editar_config_datgen_consultorio');

    //Elemento que recibe la animación
    var eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}

function fntCancelarConfigDatGenUnidadMedica() {

    var editar = document.getElementById('editar_config_datgen_consultorio');
    var view = document.getElementById('view_config_datgen_consultorio');
    editar.style.display = "none";
    view.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    var eLOrigen = $('.view_config_datgen_consultorio');

    //Elemento que recibe la animación
    var eLDestino = view;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}

function delete_servicio(data_id) {

    /*-------------------------------------------
     [ Ajax ]*/
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var responseObj = JSON.parse(xhttp.responseText);
            if (responseObj.respuesta != "ok") {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: 4000,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) {};
                    if (result.dismissUser == true) {}
                });
            }
        }
    };
    var ajaxUrl = base_url + '/UnidadMedica/deleteServicioUnidadMedica/' + data_id;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();


}