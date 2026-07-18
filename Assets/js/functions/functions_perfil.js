/*==================================================================
[ Variables de Archivo ]*/



/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*==================================================================
    [ Perfil Usuario ]*/

    /*-------------------------------------------
     [ Agregar evento click a Editar Perfil de Usaurio  ]*/

    if (document.getElementById("btnEditar_PerfilUsuario")) {
        var btnElement = document.getElementById('btnEditar_PerfilUsuario');
        btnElement.onclick = function() { fntEditarPerfilUsuario() };
    }

    /*-------------------------------------------
     [ Agregar evento click a Cancelar Edicion de Perfil de Usaurio  ]*/

    if (document.getElementById("btnCancelar_PerfilUsuario")) {
        var btnElement = document.getElementById('btnCancelar_PerfilUsuario');
        btnElement.onclick = function() { fntCancelarPerfilUsuario() };
    }

    /*-------------------------------------------
      [ Form - Agregar evento submit al formulario de registro de Perfil de Usaurio]*/
    if (document.getElementById('formPerfilUsuario')) {
        var formPerfilUsuario = document.getElementById('formPerfilUsuario');
        formPerfilUsuario.addEventListener("submit", function(event) { setPerfilUsuario(event) });
    }


    /*-------------------------------------------
    [ Form - Inicializa textarea ]*/

    tinymce.init({
        selector: '#inputAcercaDeMi',
        width: "100%",
        height: 400,
        statubar: true,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
    });



    // if (document.getElementById("inputAcercaDeMi")) {
    //     $('#inputAcercaDeMi').summernote({
    //         lang: 'es-ES', // default: 'en-US'
    //         placeholder: 'Descripción acerca de mí.',
    //         tabsize: 1,
    //         height: 300,
    //         toolbar: [
    //             ['style', ['style']],
    //             ['font', ['bold', 'underline', 'clear']],
    //             ['color', ['color']],
    //             ['para', ['ul', 'ol', 'paragraph']],
    //             ['table', ['table']],
    //             ['insert', ['link', 'picture']],
    //             ['view', ['help']]
    //         ]
    //     });
    // }


});



/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    /*-------------------------------------------
    [ Funciones Fill Selects ]*/


    /*-------------------------------------------
    [ Funciones Load Data ]*/
    // getDatosPerfilUsuario();

    /*-------------------------------------------
    [ Activa el menu horizontal (prinicipal) correspondiente del sidemenu ]*/


}, false)



/*==================================================================
[ Funciones de Eventos ]*/


/*==================================================================
[ Funciones Llenar Selects e Inicializa Select2]*/


/*==================================================================
[ Funciones de Manejo de Datos de Unidad Medica para Set, Edit and View ]*/

function setPerfilUsuario(e) {


    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    var btnGuardar = document.getElementById('btnGuardar_PerfilUsuario');
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

            console.log(responseObj);
            return;

            if (responseObj.respuesta == "ok") {

                // getDatosPerfilUsuario();

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
    var ajaxUrl = base_url + '/Usuarios/setPerfilUsuario';
    var formData = new FormData(formPerfilUsuario);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}

function getDatosPerfilUsuario() {

    /*-------------------------------------------
    [ Obtiene id de registro ]*/
    var idRegistro = document.getElementById('btnGuardar_DatGen_UnidadMedica').getAttribute("data-id");


    /*-------------------------------------------
    [ Ajax ]*/
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {

            var responseObj = JSON.parse(xhttp.responseText);

            if (responseObj.respuesta == "ok") {
                cargarDatosPerfilUsuario(responseObj);
            } else {
                //error
            }

        }

    };
    var ajaxUrl = base_url + '/UnidadMedica/getUnidadMedica/' + idRegistro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();

}

function cargarDatosPerfilUsuario(responseObj) {

    /*==========================================
    [ ====== Unidad Medica ====== ]*/

    /*-------------------------------------------
    [ Cargar en DOM data-id de selects para almacenar los valores de la DB]*/
    document.getElementById("comboPais").setAttribute("data-id", responseObj.data.pais_id);
    document.getElementById("comboEntidad").setAttribute("data-id", responseObj.data.entidad_id);
    document.getElementById("comboMunicipio").setAttribute("data-id", responseObj.data.municipio_id);
    document.getElementById("comboCodigoPostal").setAttribute("data-id", responseObj.data.cp_id);

    /*-------------------------------------------
    [ Asigna los valores de inputs Edit]*/
    document.getElementById("inputRegisterNombreUnidad").value = responseObj.data.nombre_unidad;
    document.getElementById("inputRegisterEmailUnidadMedica").value = responseObj.data.email_contacto_unidadmedica;
    document.getElementById("inputRegisterTelefonoUnidadMedica").value = responseObj.data.telefono_unidadmedica;

    //Input Hide
    document.getElementById("inputIdUnidadMedica").value = responseObj.data.id;
    document.getElementById("inputIdDomicilio").value = responseObj.data.domicilio_id;
    document.getElementById("inputLogo").value = responseObj.data.logo;

    var logo_actual = document.getElementById("logo_actual");
    logo_actual.innerHTML = `<img style="top: 50%; -webkit-transform: translate(0, -50%); transform: translate(0, -50%); position: relative; max-width: 100%;
    max-height: 100%; background-color: #FFF; -webkit-transition: border-color 0.15s linear; transition: border-color 0.15s linear;" src="${assets}/images/logos/${responseObj.data.logo}" alt=""></img>`;


    /*-------------------------------------------
    [ Asigna los valores de inputs Read ]*/
    document.getElementById("inputRegisterNombreUnidad_read").innerHTML = responseObj.data.nombre_unidad;
    document.getElementById("inputRegisterTelefonoUnidadMedica_read").innerHTML = responseObj.data.telefono_unidadmedica;
    document.getElementById("inputRegisterEmailUnidadMedica_read").innerHTML = responseObj.data.email_contacto_unidadmedica;

    var logo_actual_read = document.getElementById("logo_actual_read");
    if (responseObj.data.logo == '' || responseObj.data.logo == null) {
        logo_actual_read.innerHTML = '';
    } else {
        logo_actual_read.innerHTML = `<img style="max-width: 100%; height:120px;" src="${assets}/images/logos/${responseObj.data.logo}" alt="Logo de la empresa" class="logo-uploader__image"></img>`;
    }



    /*-------------------------------------------
    [ Asigna los valores de selects Read ]*/
    var combo = document.getElementById("comboTipoUnidad");
    var selected = combo.options[combo.selectedIndex].text;
    document.getElementById("comboTipoUnidad_read").innerHTML = selected;


    /*==========================================
    [ ====== Domicilio Unidad Medica ====== ]*/

    if (responseObj.data.domicilio_id != null && responseObj.data.domicilio_id != 0) {

        /*-------------------------------------------
        [ Asigna los valores de selects (combos) Edit]*/
        fillSelectPais();


        /*-------------------------------------------
        [ Asigna los valores de inputs Edit]*/
        document.getElementById("inputRegisterCalle").value = responseObj.data.calle;
        document.getElementById("inputRegisterColonia").value = responseObj.data.colonia;
        document.getElementById("inputRegisterNumExt").value = responseObj.data.num_ext;
        document.getElementById("inputRegisterNumInt").value = responseObj.data.num_int;
        document.getElementById("inputRegisterReferencia").value = responseObj.data.referencia;



        /*-------------------------------------------
        [ Asigna los valores de domicilio agrupado Read ]*/
        document.getElementById("comboPais_read").innerHTML = responseObj.data.pais;

        var group_domicilio = document.getElementById("groupDomicilio_read");
        group_domicilio.innerHTML = `${responseObj.data.calle} ${responseObj.data.num_ext} ${responseObj.data.num_int} Colonia: ${responseObj.data.colonia} Entidad: ${responseObj.data.entidad} Municipio:${responseObj.data.municipio} Codigo Postal: ${responseObj.data.codigo_postal}`;

        document.getElementById("inputRegisterReferencia_read").innerHTML = `<a href="${responseObj.data.referencia}" target="_blank">${responseObj.data.referencia}</a>`;



        /*-------------------------------------------
        [ Mostrar Mapa de Google ]*/

        if (responseObj.data.referencia === '') {
            document.getElementById("google-maps-db").setAttribute("href", "https://www.google.com/maps/");
        } else {
            document.getElementById("google-maps-db").setAttribute("href", responseObj.data.referencia);
        }


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

function fntEditarPerfilUsuario() {


    var editar = document.getElementById('editar_perfilusuario');
    var view = document.getElementById('view_perfil_usuario');
    view.style.display = "none";
    editar.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    var eLOrigen = $('.editar_perfilusuario');

    //Elemento que recibe la animación
    var eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);


}

function fntCancelarPerfilUsuario() {

    var editar = document.getElementById('editar_perfilusuario');
    var view = document.getElementById('view_perfil_usuario');
    editar.style.display = "none";
    view.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    var eLOrigen = $('.view_perfil_usuario');

    //Elemento que recibe la animación
    var eLDestino = view;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}