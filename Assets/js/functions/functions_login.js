/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {


    /*==================================================================
    [ Form Registro de Usuarios ]*/

    /*-------------------------------------------
    [ Form - Agregar evento onsubmit al formulario de registro de Usuarios en Login]*/
    if (document.getElementById('formUsuarioRegister')) {
        var formUsuarioRegister = document.getElementById('formUsuarioRegister');
        formUsuarioRegister.addEventListener("submit", function(event) { guardarUsuarioLogin(event) });
    }

    /*-------------------------------------------
    [ Form - Agregar plugin de validación de campos ]*/
    if (document.getElementById('formUsuarioRegister')) {
        $('#formUsuarioRegister').parsley();
    }


    /*==================================================================
    [ Form Login ]*/

    /*-------------------------------------------
    [ Form - Agregar evento onsubmit al formulario de Login ]*/
    if (document.getElementById('formLogin')) {
        var formLogin = document.getElementById('formLogin');
        formLogin.addEventListener("submit", function(event) { loginAcceso(event) });
    }

    /*-------------------------------------------
    [ Form - Agregar plugin de validación de campos ]*/
    // if (document.getElementById('formLogin')) {
    //     $('#formLogin').parsley();
    // }

    /*==================================================================
    [ Form Restablecer Contraseña ]*/

    /*-------------------------------------------
    [ Form - Agregar evento onsubmit al formulario de ResetPassword ]*/
    if (document.getElementById('formResetPassword')) {
        var formResetPassword = document.getElementById('formResetPassword');
        formResetPassword.addEventListener("submit", function(event) { resetPassword(event) });
    }

    /*-------------------------------------------
    [ Form - Agregar evento onsubmit al formulario de Cambiar Contraseña ]*/
    if (document.getElementById('formCambiarPassword')) {
        var formCambiarPassword = document.getElementById('formCambiarPassword');
        formCambiarPassword.addEventListener("submit", function(event) { cambiarPassword(event) });
    }

    /*-------------------------------------------
    [ Form - Autocomplete ]*/

    if (document.getElementById('inputRegisterEscuela')) {
        $('#inputRegisterEscuela').autocomplete({
            source: function(request, response) {
                get_escuelas_mexico(request.term, function(result) {
                    response(result);
                });
            },
            minLength: 3,
            select: function(event, ui) {
                // console.log("label = " + ui.item.label + " id = " + ui.item.id);
            }
        }).data('ui-autocomplete')._renderItem = function(ul, item) {
            return $('<li class="ui-automplete-row"></li>')
                .data('item.autocomplete', item)
                .append(item.label)
                .appendTo(ul);
        }
    }


});


/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    /*-------------------------------------------
    [ Funciones Fill Selects ]*/
    fillSelectPais();
    fillSelectTipoLicencia();
    fillSelectOrigenEntera();
    fillSelectEspecialidades();

    /*-------------------------------------------
    [ Ajustar max-width para autocomplete de registro.php ]*/
    if (document.getElementById('inputRegisterEscuela')) {
        var input_width = $("#inputRegisterEscuela");
        var anchura = input_width.outerWidth();
        var autocomplete = document.getElementById("ui-id-1");
        anchura = anchura + 'px';
        autocomplete.style.maxWidth = anchura;
    }


    /*-------------------------------------------
    [ Asignar el foco al cmapo Nombre del Formulario de registro en Login ]*/
    if (document.getElementById("inputRegisterNombreUsuario")) {
        document.getElementById("inputRegisterNombreUsuario").focus();
    }


}, false)


/*==================================================================
[ Autocomplete ]*/

function get_escuelas_mexico(filtro, result) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var dataObj = JSON.parse(xhttp.responseText);
            result(dataObj);
        }
    };
    var ajaxUrl = base_url + '/Login/getEscuelasAutocomplete/' + filtro;
    xhttp.open("GET", ajaxUrl, true);
    xhttp.send();
}


/*==================================================================
[ Funciones Llenar Selects e Inicializa Select2]*/

function fillSelectPais() {

    if (document.querySelector('#comboPais')) {

        //Ajax LLenar Select de catalogo de Paises
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboPais').innerHTML = xhttp.responseText;
                console.log(xhttp.responseText);

                /*-------------------------------------------
                [ Inicializa Select2 ]*/
                $('#comboPais').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion',
                    minimumResultsForSearch: Infinity
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
        var ajaxUrl = base_url + '/Login/getSelectPaises';
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();

    }

}

function fillSelectTipoLicencia() {

    if (document.querySelector('#comboTipoLicencia')) {

        //Ajax LLenar Select de catalogo de Especialidades
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboTipoLicencia').innerHTML = xhttp.responseText;

                /*-------------------------------------------
                [ Inicializa Select2 ]*/
                $('#comboTipoLicencia').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion',
                    minimumResultsForSearch: Infinity
                });

                //Asignar Valor Default después de Inicializar Seleclt2
                $('#comboTipoLicencia').val('2');
                $('#comboTipoLicencia').trigger('change');

            }

        };
        var ajaxUrl = base_url + '/Login/getTipoLicencia';
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();

    }

}

function fillSelectOrigenEntera() {

    if (document.querySelector('#comboOrigenEntera')) {

        //Ajax LLenar Select de catalogo de Especialidades
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboOrigenEntera').innerHTML = xhttp.responseText;

                /*-------------------------------------------
                [ Inicializa Select2 ]*/
                $('#comboOrigenEntera').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion',
                    minimumResultsForSearch: Infinity
                });

                //Asignar Valor Default después de Inicializar Seleclt2
                $('#comboOrigenEntera').val('');
                $('#comboOrigenEntera').trigger('change');

            }

        };
        var ajaxUrl = base_url + '/Login/getOrigenEntera';
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();

    }

}

function fillSelectEspecialidades() {

    if (document.querySelector('#comboEspecialidad')) {
        //Ajax LLenar Select de catalogo de Especialidades
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboEspecialidad').innerHTML = xhttp.responseText;

                /*-------------------------------------------
                [ Inicializa Select2 Mulitselect]*/
                $('#comboEspecialidad').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion'
                });

                //Asignar Valor Default después de Inicializar Seleclt2
                $('#comboEspecialidad').val('');
                $('#comboEspecialidad').trigger('change');

            }

        };
        var ajaxUrl = base_url + '/Login/getSelectEspecialidades';
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();
    }

}

/*==================================================================
[ Acceso a Sistema ]*/

function loginAcceso(e) {

    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    let btnGuardar = document.getElementById('btnActionForm');
    agregarLoadingButtonGuardar(btnGuardar);

    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();

    //Asigna Valores de Formulario
    let inputEmail = document.getElementById('inputEmail').value;
    let inputPassword = document.getElementById('inputPassword').value;


    /*-------------------------------------------
    [ Verifica que los campos no estén vacíos ]*/
    if (inputEmail == '' || inputPassword == '') {

        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe indicar usuario y/o password',
            textButton: 'Cerrar'
        }).then(function(result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return;

    }

    /*-------------------------------------------
    [ Ajax ]*/
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {

            var responseObj = JSON.parse(xhttp.responseText);

            if (responseObj.respuesta == "ok") {
                window.location = base_url + '/inicio';
            } else {
                if (responseObj.mostrar_mensaje == true) {

                    mensajeAlertaModal({
                        icon: 'error',
                        timer: 4000,
                        title: iconMensajeError + ' ¡Atención!',
                        text: responseObj.mensaje,
                        textButton: 'Cerrar'
                    }).then(function(result) {
                        if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
                        if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
                    })

                }

            }
        }

    };
    var ajaxUrl = base_url + '/Login/loginUser';
    var formData = new FormData(formLogin);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}


/*==================================================================
[ ResetPassword ]*/

function resetPassword(e) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    var btnEnviar = document.getElementById('btnActionFormReset');
    btnEnviar.setAttribute('disabled', 'disabled');

    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();

    //Asigna Valores de Formulario
    let inputEmailReset = document.getElementById('inputEmailReset').value;


    /*-------------------------------------------
    [ Verifica que los campos no estén vacíos ]*/
    if (inputEmailReset == '') {

        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe indicar email',
            textButton: 'Cerrar'
        }).then(function(result) {
            if (result.dismissTimer == true) { btnEnviar.removeAttribute('disabled'); };
            if (result.dismissUser == true) { btnEnviar.removeAttribute('disabled'); }
        })

        return;
    }

    /*-------------------------------------------
    [ Ajax - Before Send ]*/
    var divLoading = document.getElementById('divLoading');
    divLoading.style.display = "flex";

    /*-------------------------------------------
    [ Ajax ]*/
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {

            var responseObj = JSON.parse(xhttp.responseText);

            // btnEnviar.innerHTML = '<span id="btnText">' + btnText + '</span>';
            btnEnviar.removeAttribute('disabled');
            divLoading.style.display = "none";

            if (responseObj.respuesta == "ok") {
                if (responseObj.mostrar_mensaje == true) {

                    mensajeAlertaModal({
                        icon: 'success',
                        timer: responseObj.tiempo,
                        title: iconMensajeSuccess + ' OK!',
                        text: responseObj.mensaje,
                        textButton: 'Cerrar'
                    }).then(function(result) {
                        if (result.dismissTimer == true) { window.location = base_url; };
                        if (result.dismissUser == true) { window.location = base_url; }
                    })

                }

            } else {

                if (responseObj.mostrar_mensaje == true) {

                    mensajeAlertaModal({
                        icon: 'error',
                        timer: responseObj.tiempo,
                        title: iconMensajeError + ' ¡Atención!',
                        text: responseObj.mensaje,
                        textButton: 'Cerrar'
                    }).then(function(result) {
                        if (result.dismissTimer == true) { window.location = base_url; };
                        if (result.dismissUser == true) { window.location = base_url; }
                    })

                }
            }

        }

    };
    var ajaxUrl = base_url + '/Login/resetPassword';
    var formData = new FormData(formResetPassword);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}


/*==================================================================
[ CambiarPassword ]*/

function cambiarPassword(e) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    var btnEnviar = document.getElementById('btnActionFormResetPassword');
    btnEnviar.setAttribute('disabled', 'disabled');

    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();

    //Asigna Valores de Formulario
    let inputPassword = document.getElementById('inputResetPassword').value;
    let inputConfirmPassword = document.getElementById('inputResetConfirmPassword').value;

    /*-------------------------------------------
    [ Verifica que los campos no estén vacíos ]*/
    if (inputPassword == '' || inputConfirmPassword == '') {


        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Los campos no pueden estar vacíos',
            textButton: 'Cerrar'
        }).then(function(result) {
            if (result.dismissTimer == true) { btnEnviar.removeAttribute('disabled'); };
            if (result.dismissUser == true) { btnEnviar.removeAttribute('disabled'); }
        })

        return;
    }


    /*-------------------------------------------
    [ Verifica que las contraeñas coincidan ]*/
    if (inputPassword != inputConfirmPassword) {

        mensajeAlertaModal({
            icon: 'error',
            timer: 4000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Las contraseñas no coinciden, verifique',
            textButton: 'Cerrar'
        }).then(function(result) {
            if (result.dismissTimer == true) { btnEnviar.removeAttribute('disabled'); };
            if (result.dismissUser == true) { btnEnviar.removeAttribute('disabled'); }
        })

        return;

    }


    /*-------------------------------------------
    [ Ajax - Before Send ]*/
    var divLoading = document.getElementById('divLoading');
    divLoading.style.display = "flex";
    // var btnText = document.getElementById('btnText').textContent;
    // btnEnviar.innerHTML = '<i class="spinner-border text-white" aria-hidden="true"></i> <span id="btnText">' + btnText + '</span>';


    /*-------------------------------------------
    [ Ajax ]*/
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {

        if (this.readyState == 4 && this.status == 200) {

            var responseObj = JSON.parse(xhttp.responseText);
            // btnEnviar.innerHTML = '<span id="btnText">' + btnText + '</span>';
            btnEnviar.removeAttribute('disabled');
            divLoading.style.display = "none";

            if (responseObj.respuesta == "ok") {
                if (responseObj.mostrar_mensaje == true) {

                    mensajeAlertaModal({
                        icon: 'success',
                        timer: responseObj.tiempo,
                        title: iconMensajeSuccess + ' OK!',
                        text: responseObj.mensaje,
                        textButton: 'Cerrar'
                    }).then(function(result) {
                        if (result.dismissTimer == true) { window.location = base_url + '/login'; };
                        if (result.dismissUser == true) { window.location = base_url + '/login'; }
                    })

                }

            } else {

                if (responseObj.mostrar_mensaje == true) {

                    mensajeAlertaModal({
                        icon: 'error',
                        timer: responseObj.tiempo,
                        title: iconMensajeError + ' ¡Atención!',
                        text: responseObj.mensaje,
                        textButton: 'Cerrar'
                    }).then(function(result) {
                        if (result.dismissTimer == true) { window.location = base_url + '/login'; };
                        if (result.dismissUser == true) { window.location = base_url + '/login'; }
                    })

                }

            }

        }

    };
    var ajaxUrl = base_url + '/Login/setPassword';
    var formData = new FormData(formCambiarPassword);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}


// (function() {
//     "use strict";
//     window.addEventListener("load", (function() {
//         if (document.activeElement) {
//             var activeEl = document.activeElement;
//             var tag = activeEl.tagName.toLowerCase();
//             if (tag === "input" || tag === "select" || tag === "textarea") {
//                 return
//             }
//         }
//         var featureSearchInput = document.getElementById("txtQuickFind");
//         var indexSearchInput = document.querySelector("#quickjump");
//         if (indexSearchInput && indexSearchInput.value && indexSearchInput.value !== "") {
//             indexSearchInput.focus()
//         } else if (featureSearchInput) {
//             featureSearchInput.focus()
//         }
//     }))
// })();