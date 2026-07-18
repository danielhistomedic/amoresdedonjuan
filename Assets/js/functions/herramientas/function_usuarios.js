/*==================================================================
[ Variables ]*/

var tableUsuarios;
var tableUsuariosElement = "#tableUsuarios";
var tableUsuariosElementJS = "tableUsuarios";
var configTableUsuarios = "";

/*==================================================================
[ DOMContentLoaded ]*/

document.addEventListener("DOMContentLoaded", function(event) {

    /*==================================================================
    [ Form ]*/

    /*-------------------------------------------
    [ Form - Agregar evento onsubmit al formulario de registro de Usuarios ]*/
    if (document.getElementById('formUsuario')) {
        var formUsuario = document.getElementById('formUsuario');
        formUsuario.addEventListener("submit", function(event) { setUsuario(event) });
    }

    /*==================================================================
    [ Botons de Accion ]*/

    /*-------------------------------------------
    [ Agregar evento click a Nuevo Usuario  ]*/
    if (document.getElementById("btnCrear_Usuario")) {
        let btnElement = document.getElementById('btnCrear_Usuario');
        btnElement.onclick = function() { fntNuevoUsuario() };
    }


    /*-------------------------------------------
     [ Agregar evento click a Editar Usuarios  ]*/

    if (document.getElementById("btnEditar_Usuario")) {
        var btnElement = document.getElementById('btnEditar_Usuario');
        btnElement.onclick = function() { fntEditUsuario_Form() };
    }

    /*-------------------------------------------
     [ Agregar evento click a Cancelar Edicion/Creación de Usuario  ]*/

    if (document.querySelector(".btnCancelar_Usuario")) {
        let btnElement = document.querySelectorAll('.btnCancelar_Usuario');
        for (let index = 0; index < btnElement.length; index++) {
            const element = btnElement[index];
            element.onclick = function() { fntCancelarReturnListUsuario() };
        }
    }

    /*==================================================================
    [ DataTable ]*/

    if (document.getElementById(tableUsuariosElementJS)) {
        /*-------------------------------------------
        [ DataTable Inicializa ]*/
        setConfigTableUsuarios('Usuarios', 'getUsuarios');
        tableUsuarios = $(tableUsuariosElement).DataTable(configTableUsuarios);

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de inicializar la tabla ]*/
        $(tableUsuariosElement).on('init.dt', function() {

            ReDesignButonExcel();

            $('.dataTables_wrapper select').select2({
                language: "es",
                minimumResultsForSearch: Infinity
            });

            var thead = document.querySelector(tableUsuariosElement + ' thead');
            thead.classList.remove("bg-secondary");
            thead.classList.add("bg-thead");

            //Valida si se activa el botón excel .
            validaPermisoExportar(menu);

        });

        /*-------------------------------------------
          [ Agregar evento click a Colvis de Datatable para agregar icono para restaurar las columnas ]*/
        if (document.querySelector(".buttons-colvis")) {
            var btnElement = document.querySelector('.buttons-colvis');
            btnElement.onclick = function() {

                setTimeout(() => {
                    if (document.querySelector("div.dt-button-collection .buttons-colvisRestore span i")) {
                        var colvis_icon = document.querySelector('div.dt-button-collection .buttons-colvisRestore span i');
                        colvis_icon.classList.remove("far");
                        colvis_icon.classList.add("fa-regular");
                    }
                }, 500);

            };
        }

        /*-------------------------------------------
         [ DataTable - Se ejecuta después de redibujarse la tabla ]*/
        $(tableUsuariosElement).on('draw.dt', function() {

        });

        /*-------------------------------------------
        [ DataTable - Se ejecuta después de dar click en el primer elemento td del tr de la tabla ]*/
        $(tableUsuariosElement).on('click', 'tbody tr>td', function() {

        });
    }


    /*==================================================================
    [ Otros Eventos ]*/

    /*-------------------------------------------
    [ Agregar evento click para Mostrar Mas/Mostrar Menos ]*/
    if (document.querySelector(".mostrar_mas_menos_usuario")) {
        let more_less = document.querySelector(".mostrar_mas_menos_usuario");
        let mostrar_mas = document.getElementById("mostrar_mas_usuario");
        let mostrar_menos = document.getElementById("mostrar_menos_usuario");
        more_less.addEventListener("click", function() {
            mostrar_mas.classList.toggle("d-none");
            mostrar_menos.classList.toggle("d-none");
        });
    }

    /*-------------------------------------------
    [ Agregar evento click para Mostrar Mas/Mostrar Menos ]*/
    if (document.getElementById("dropdown-agregar-usuario-red")) {
        let iconUserRed = document.getElementById("dropdown-agregar-usuario-red");
        iconUserRed.onclick = function() { dropdownUserRedIcon() };
    }



});



/*==================================================================
[ Window ]*/

window.addEventListener('load', function() {

    /*-------------------------------------------
    [ Funciones Init ]*/

    /*-------------------------------------------
    [ Funciones Fill Selects ]*/
    fillSelectRegimenFiscal();

    fillSelectRolesUsuario();


    /*-------------------------------------------
    [ Activa el menu horizontal (prinicipal) correspondiente del sidemenu ]*/
    var pageUrl = base_url + "/herramientas"
    fntActivarHorizontalMenu(pageUrl);

}, false)


/*==================================================================
[ Funciones ]*/

function getUsuario(idUsuario, btnElement, btnAction, class_old, fntData) {

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
                    if (result.dismissTimer == true) {
                        if (btnElement != "") {
                            restablecerButtonOpcionesDataTable(btnElement, class_old);
                        } else {
                            removerClasesButtonGuardar(btnAction, loading);
                        }
                    };
                    if (result.dismissUser == true) {
                        if (btnElement != "") {
                            restablecerButtonOpcionesDataTable(btnElement, class_old);
                        } else {
                            removerClasesButtonGuardar(btnAction, loading);
                        }
                    }
                });
            }
        }
    };

    let ajaxUrl = base_url + '/Usuarios/getUsuario/';
    let formData = new FormData();
    formData.append('idUsuario', idUsuario);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}

function dropdownUserRedIcon() {

    if (document.querySelector("#dropdown-agregar-usuario-red .fa-angle-down")) {
        var iconEl = document.querySelector("#dropdown-agregar-usuario-red .fa-angle-down");
        console.log(iconEl);
        iconEl.classList.remove("fa-angle-down");
        iconEl.classList.add("fa-angle-up");
    } else {
        var iconEl = document.querySelector("#dropdown-agregar-usuario-red .fa-angle-up");
        console.log(iconEl);
        iconEl.classList.remove("fa-angle-up");
        iconEl.classList.add("fa-angle-down");
    }
}



/*==================================================================
[ Funciones Fill Selects e Inicializa Select2]*/

function fillSelectRegimenFiscal() {

    if (document.querySelector('#comboRegimenFiscal')) {

        //Ajax LLenar Select de catalogo de Regimen Fiscales
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

function fillSelectRolesUsuario() {

    if (document.querySelector('#comboRoles')) {

        console.log('entra fillSelectRolesUsuario');

        //Ajax LLenar Select de catalogo de roles
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {

            if (this.readyState == 4 && this.status == 200) {

                document.querySelector('#comboRoles').innerHTML = xhttp.responseText;

                /*-------------------------------------------
                [ Inicializa Select2 ]*/
                $('#comboRoles').select2({
                    language: "es",
                    placeholder: 'Seleccione una opcion',
                    minimumResultsForSearch: Infinity
                });

                //Asignar Valor Default después de Inicializar Seleclt2
                $('#comboRoles').val('');
                $('#comboRoles').trigger('change');

            }

        };
        var ajaxUrl = base_url + '/Roles/getSelectRoles';
        xhttp.open("GET", ajaxUrl, true);
        xhttp.send();
    }

}


/*==================================================================
[ Nuevo Registro ]*/

function fntNuevoUsuario() {


    /*-------------------------------------------
    [ Limpiar Form ]*/
    let formElement = document.getElementById('formUsuario');
    document.getElementById("inputIdUsuario").value = '';
    resetFormNoPasley(formElement);

    let list = document.getElementById('list_htas_usuario');
    let editar = document.getElementById('crear_editar_htas_usuario');
    let view = document.getElementById('view_htas_usuario');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    // //Asignar Titulo de Encabezado
    // var tipo = document.getElementById("btnCrear_Usuario").getAttribute("data-tipo");
    // if (tipo == 2) {
    //     document.getElementById("titulo-editar").innerHTML = "Registrar Nueva(o) Recepcionista.";
    // } else {
    //     document.getElementById("titulo-editar").innerHTML = "Registrar Nuevo Usuario.";
    // }
    // document.getElementById("titulo-editar-icon").innerHTML = '<i class="fa-regular fa-file-plus icon-size float-start text-secondary text-secondary-shadow me-2"></i>';

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_htas_usuario');

    //Elemento que recibe la animación
    let eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}



/*==================================================================
[ Guardar Registro ]*/

function setUsuario(e) {


    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    let btnGuardar = document.getElementById('btnGuardar_Usuario');
    agregarLoadingButtonGuardar(btnGuardar);


    /*-------------------------------------------
    [ Evita la recarga de la pagina. ]*/
    e.preventDefault();


    /*-------------------------------------------
    [ Valida Inputs que no estpen vacíos. ]*/
    let check = true;
    let input = document.querySelectorAll("input.inputForm100");

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
            timer: 3000,
            title: iconMensajeError + ' ¡Atención!',
            text: 'Debe llenar los campos requeridos.',
            textButton: 'Cerrar'
        }).then(function(result) {
            if (result.dismissTimer == true) { removerClasesButtonGuardar(btnGuardar, loading); };
            if (result.dismissUser == true) { removerClasesButtonGuardar(btnGuardar, loading); }
        })

        return false;
    }


    let inputPassword = document.getElementById('inputRegisterPassword').value;
    let inputConfirmPassword = document.getElementById('inputRegisterConfirmPassword').value;

    if (inputPassword != inputConfirmPassword) {
        check = false;
        if (!check) {
            mensajeAlertaModal({
                icon: 'error',
                timer: 3000,
                title: iconMensajeError + ' ¡Atención!',
                text: 'Las Contraseñas No Coinciden.',
                textButton: 'Cerrar'
            }).then(function(result) {
                if (result.dismissTimer == true) { btnGuardar.removeAttribute('disabled'); };
                if (result.dismissUser == true) { btnGuardar.removeAttribute('disabled'); }
            })

            return false;
        }
    }


    /*-------------------------------------------
    [ Verifica Resultado de Validaciones ]*/
    if (!check) {
        return false;
    }



    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let responseObj = JSON.parse(xhttp.responseText);

            if (responseObj.respuesta == "ok") {
                tableUsuarios.ajax.reload(function() {});
                fntCancelarReturnListUsuario();
                mensajeAlertaModal({
                    icon: 'success',
                    timer: responseObj.tiempo,
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
                    timer: responseObj.tiempo,
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
    let ajaxUrl = base_url + '/Usuarios/setUsuario';
    let formData = new FormData(formUsuario);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formData);

}



/*==================================================================
[ Editar Registro ]*/

function fntCancelarReturnListUsuario() {

    let list = document.getElementById('list_htas_usuario');
    let editar = document.getElementById('crear_editar_htas_usuario');
    let view = document.getElementById('view_htas_usuario');
    list.style.display = "block";
    editar.style.display = "none";
    view.style.display = "none";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.list_htas_usuario');

    //Elemento que recibe la animación
    let eLDestino = list;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);

}

function fntEditUsuario(btnElement) {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");



    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-pencil-alt')


    //Asignar Titulo de Encabezado
    // document.getElementById("titulo-editar").innerHTML = "Editar Usuario Seleccionado.";
    // document.getElementById("titulo-editar-icon").innerHTML = '<i class="fa-regular fa-file-pen icon-size float-start text-secondary text-secondary-shadow me-2"></i>';


    //-----------------------------------
    //[ Animación de Paneles ]

    let list = document.getElementById('list_htas_usuario');
    let editar = document.getElementById('crear_editar_htas_usuario');
    let view = document.getElementById('view_htas_usuario');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_htas_usuario');

    //Elemento que recibe la animación
    let eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);


    //Se obtiene los datos del usuario
    getUsuario(idRegistro, btnElement, "", "fa-pencil-alt", function ftnData(responseObj) {
        /*-------------------------------------------
        [ Asigna los valores de inputs ]*/
        cargarDatosEditUsuario(responseObj);
        restablecerButtonOpcionesDataTable(btnElement, 'fa-pencil-alt');
    })

}

function cargarDatosEditUsuario(modelDataObj) {

    /*===========================================
    [ Obtiene datos de Form  ]*/

    /*-------------------------------------------
    [ Asigna lso valores de inputs  ]*/

    document.getElementById("inputIdUsuario").value = modelDataObj.dataId;
    document.getElementById("inputNombreUsuario").value = modelDataObj.data.nombre;
    document.getElementById("inputApellidoPaterno").value = modelDataObj.data.paterno;
    document.getElementById("inputApellidoMaterno").value = modelDataObj.data.materno;
    document.getElementById("inputEmail").value = modelDataObj.data.email;
    document.getElementById("inputTelefono").value = modelDataObj.data.telefono;

    /*-------------------------------------------
    [ Asogna lso valores de selects2  ]*/
    $('#comboSexo').val(modelDataObj.data.sexo_id);
    $('#comboSexo').trigger('change');

    $('#comboRoles').val(modelDataObj.data.rol_id);
    $('#comboRoles').trigger('change');

    document.getElementById("inputRegisterPassword").value = "";
    document.getElementById("inputRegisterConfirmPassword").value = "";


}

function fntEditUsuario_Form() {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = document.getElementById("inputIdUsuario").value;

    /*-------------------------------------------
      [ Deshabilita elemento para prevenir doble registro ]*/
    let btnEditar = document.getElementById('btnEditar_Usuario');
    agregarLoadingButtonGuardar(btnEditar);


    //Asignar Titulo de Encabezado
    // document.getElementById("titulo-editar").innerHTML = "Editar Usuario Seleccionado.";
    // document.getElementById("titulo-editar-icon").innerHTML = '<i class="fa-regular fa-file-pen icon-size float-start text-secondary text-secondary-shadow me-2"></i>';


    //-----------------------------------
    //[ Animación de Paneles ]

    let list = document.getElementById('list_htas_usuario');
    let editar = document.getElementById('crear_editar_htas_usuario');
    let view = document.getElementById('view_htas_usuario');
    view.style.display = "none";
    list.style.display = "none";
    editar.style.display = "block";

    //Elemento que dispara la animación
    let eLOrigen = $('.crear_editar_htas_usuario');

    //Elemento que recibe la animación
    let eLDestino = editar;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);


    //Se obtiene los datos del usuario
    getUsuario(idRegistro, "", btnEditar, "", function ftnData(responseObj) {
        /*-------------------------------------------
        [ Asigna los valores de elementos ]*/
        cargarDatosEditUsuario(responseObj);
        removerClasesButtonGuardar(btnEditar, loading);
    })

}


/*==================================================================
[ Reactivar Registro ]*/

function fntActiveUsuario(btnElement) {

    /*-------------------------------------------
    [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");
    var idRegistroUM = btnElement.getAttribute("data-um-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-arrow-rotate-left')

    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let responseObj = JSON.parse(xhttp.responseText);
            if (responseObj.respuesta == "ok") {
                tableUsuarios.ajax.reload(function() {});
                mensajeAlertaModal({
                    icon: 'success',
                    timer: responseObj.tiempo,
                    title: iconMensajeSuccess + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) {};
                    if (result.dismissUser == true) {}
                });
            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-arrow-rotate-left'); };
                    if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-arrow-rotate-left'); }
                });
            }
        }
    };

    let ajaxUrl = base_url + '/Usuarios/setEstatusUsuario/';
    let formUsuarioTemp = new FormData();
    formUsuarioTemp.append("usuario_id", idRegistro);
    formUsuarioTemp.append("unidad_medica_id", idRegistroUM);
    formUsuarioTemp.append("activo", 1);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formUsuarioTemp);

    // /*-------------------------------------------
    //  [ Obtiene id de registro ]*/
    // var idRegistro = btnElement.getAttribute("data-id");

    // /*-------------------------------------------
    // [ Deshabilita elemento para prevenir doble registro ]*/
    // agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-arrow-rotate-left')

    // /*-------------------------------------------
    // [ Ajax ]*/
    // let xhttp = new XMLHttpRequest();
    // xhttp.onreadystatechange = function() {
    //     if (this.readyState == 4 && this.status == 200) {

    //         let responseObj = JSON.parse(xhttp.responseText);

    //         if (responseObj.respuesta == "ok") {
    //             tableUsuarios.ajax.reload(function() {});
    //             mensajeAlertaModal({
    //                 icon: 'success',
    //                 timer: responseObj.tiempo,
    //                 title: iconMensajeSuccess + ' ¡Atención!',
    //                 text: responseObj.mensaje,
    //                 textButton: 'Cerrar'
    //             }).then(function(result) {
    //                 if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-arrow-rotate-left'); };
    //                 if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-arrow-rotate-left'); }
    //             });
    //         } else {
    //             mensajeAlertaModal({
    //                 icon: 'error',
    //                 timer: responseObj.tiempo,
    //                 title: iconMensajeError + ' ¡Atención!',
    //                 text: responseObj.mensaje,
    //                 textButton: 'Cerrar'
    //             }).then(function(result) {
    //                 if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-arrow-rotate-left'); };
    //                 if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-arrow-rotate-left'); }
    //             });
    //         }
    //     }
    // };

    // let ajaxUrl = base_url + '/Usuarios/setEstatusUsuario/';
    // let strData = "";
    // strData += "id=" + idRegistro;
    // strData += "&estatus=1";
    // xhttp.open("POST", ajaxUrl, true);
    // xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    // xhttp.send(strData);

}


/*==================================================================
[ Eliminar Registro ]*/

function fntDeleteUsuario(btnElement) {

    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");
    var idRegistroUM = btnElement.getAttribute("data-um-id");

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-trash-can')

    /*-------------------------------------------
    [ Ajax ]*/
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let responseObj = JSON.parse(xhttp.responseText);
            if (responseObj.respuesta == "ok") {
                tableUsuarios.ajax.reload(function() {});
                mensajeAlertaModal({
                    icon: 'success',
                    timer: responseObj.tiempo,
                    title: iconMensajeSuccess + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) {};
                    if (result.dismissUser == true) {}
                });
            } else {
                mensajeAlertaModal({
                    icon: 'error',
                    timer: responseObj.tiempo,
                    title: iconMensajeError + ' ¡Atención!',
                    text: responseObj.mensaje,
                    textButton: 'Cerrar'
                }).then(function(result) {
                    if (result.dismissTimer == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-trash-can'); };
                    if (result.dismissUser == true) { restablecerButtonOpcionesDataTable(btnElement, 'fa-trash-can'); }
                });
            }
        }
    };

    let ajaxUrl = base_url + '/Usuarios/setEstatusUsuario/';
    let formUsuarioTemp = new FormData();
    formUsuarioTemp.append("usuario_id", idRegistro);
    formUsuarioTemp.append("unidad_medica_id", idRegistroUM);
    formUsuarioTemp.append("activo", 0);
    xhttp.open("POST", ajaxUrl, true);
    xhttp.send(formUsuarioTemp);

}


/*==================================================================
[ Vista Registro ]*/

function fntViewUsuario(btnElement) {

    /*-------------------------------------------
    [ Deshabilita elemento para prevenir doble registro ]*/
    agregarLoadingButtonOpcionesDataTable(btnElement, 'fa-eye')

    let list = document.getElementById('list_htas_usuario');
    let editar = document.getElementById('crear_editar_htas_usuario');
    let view = document.getElementById('view_htas_usuario');
    list.style.display = "none";
    editar.style.display = "none";
    view.style.display = "block";

    //-----------------------------------
    //[ Animación de Paneles ]

    //Elemento que dispara la animación
    let eLOrigen = $('.view_htas_usuario');

    //Elemento que recibe la animación
    let eLDestino = view;

    // Ejecuta Funcion de animacion mostrar el Panel de Edición de Datos
    animationButton(eLOrigen, eLDestino);


    /*-------------------------------------------
     [ Obtiene id de registro ]*/
    var idRegistro = btnElement.getAttribute("data-id");

    //Se obtiene los datos del usuario
    getUsuario(idRegistro, btnElement, "", "fa-eye", function ftnData(responseObj) {
        /*-------------------------------------------
        [ Asigna los valores de inputs ]*/
        cargarDatosUsuario(responseObj);
        restablecerButtonOpcionesDataTable(btnElement, 'fa-eye');
    })

}

function cargarDatosUsuario(modelDataObj) {

    document.getElementById("inputIdUsuario").value = modelDataObj.dataId;

    /*-------------------------------------------
    [ Llena lso datos del form view  ]*/
    let nombre = "";
    if (modelDataObj.data.titulo == null || modelDataObj.data.titulo == "") {
        nombre = modelDataObj.data.nombre + ' ' + modelDataObj.data.paterno + ' ' + modelDataObj.data.materno;
    } else {
        nombre = modelDataObj.data.titulo + ' ' + modelDataObj.data.nombre + ' ' + modelDataObj.data.paterno + ' ' + modelDataObj.data.materno;
    }

    document.getElementById("inputNombreUsuario_read").innerHTML = nombre;
    document.getElementById("inputSexo_read").innerHTML = modelDataObj.data.sexo;
    document.getElementById("inputEmail_read").innerHTML = modelDataObj.data.email;
    document.getElementById("inputTelefono_read").innerHTML = modelDataObj.data.telefono;
    document.getElementById("inputRol_read").innerHTML = modelDataObj.data.rol;

    let estatus =
        modelDataObj.data.activo == 1 ?
        '<span class = "badge badge-success">Activo</span>' :
        '<span class = "badge badge-danger">Inactivo</span>';
    document.getElementById("estatus_read").innerHTML = estatus;

    document.getElementById("fechaRegistro_read").innerHTML = modelDataObj.data.usuario_updated_at;
    document.getElementById("usuarioRegistro_read").innerHTML = modelDataObj.data.usuario_register;

}

/*==================================================================
[ DataTable ]*/

function setConfigTableUsuarios(controlador, metodo) {

    configTableUsuarios = {
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
                sheetName: 'Usuarios',
                extend: 'excel',
                messageTop: "",
                title: 'Histoclin - Lista de Usuarios',
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
            { "data": "usuario" },
            { "data": "nombre" },
            { "data": "email" },
            { "data": "telefono" },
            { "data": "rol" },
            { "data": "activo" },
            { "data": "options" }
        ],
        'language': idioma_espanol

    };

}

/*==================================================================
[ DataTable Filtros ]*/

// function mostrarUsuariosHoy() {
//     setConfigTableUsuarios('Usuarios', 'getUsuarios/Hoy');
//     tableUsuarios = $(tableUsuariosElement).DataTable(configTableUsuarios);
// }


// function mostrarUsuariosTodos() {
//     setConfigTableUsuarios('Usuarios', 'getUsuarios');
//     tableUsuarios = $(tableUsuariosElement).DataTable(configTableUsuarios);
// }   setConfigTableUsuarios('Usuarios', 'getUsuarios');
//     tableUsuarios = $(tableUsuariosElement).DataTable(configTableUsuarios);
// }   setConfigTableUsuarios('Usuarios', 'getUsuarios');
//     tableUsuarios = $(tableUsuariosElement).DataTable(configTableUsuarios);
// }   tableUsuarios = $(tableUsuariosElement).DataTable(configTableUsuarios);
// }   tableUsuarios = $(tableUsuariosElement).DataTable(configTableUsuarios);
// }