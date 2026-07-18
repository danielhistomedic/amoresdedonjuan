<!DOCTYPE html>
<html lang="es">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Meta -->
    <meta name="description" content="<?= $data['page_content']; ?>">
    <meta name="author" content="Ing. Ind. Victor Daniel Perez Vargas">

    <title><?= $data['page_tag']; ?></title>

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= media(); ?>/img/favicon.ico">

    <!-- Font Awesome -->
    <link href="<?= media(); ?>/iconfonts/fontawesome/css/all.css" rel="stylesheet">

    <!-- Material Design Icons -->
    <link rel="stylesheet" href="<?= media(); ?>/iconfonts/materialdesignicons-6.1.95/css/materialdesignicons.min.css">

    <!-- BOOTSTRAP CSS -->
    <link href="<?= media(); ?>/plugins/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- MDB -->
    <link href="<?= media(); ?>/plugins/MDB5-3.9.0/css/mdb.min.css" rel="stylesheet" />

    <!-- JQuery UI -->
    <link href="<?= media(); ?>/plugins/jquery-ui-1.13.0/jquery-ui.css" rel="stylesheet" type="text/css" />

    <!-- Animate -->
    <link href="<?= media(); ?>/lib/animate/animate.css" rel="stylesheet" type="text/css">

    <!-- Select2 -->
    <link href="<?= media(); ?>/lib/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="<?= media(); ?>/css/bracket.css?v=<?= version(); ?>">
    <link rel="stylesheet" href="<?= media(); ?>/css/custom.css?v=<?= version(); ?>">

    <!-- STYLE CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="<?= media(); ?>/css/style.css?v=<?= version(); ?>" />
    <link href="<?= media(); ?>/css/skin-modes.css" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="<?= media(); ?>/colors/color1.css?v=<?= version(); ?>" />

    <!-- Custom CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="<?= media(); ?>/css/main.css?v=<?= version(); ?>" />
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="<?= media(); ?>/css/main-dark-mode.css?v=<?= version(); ?>" />


</head>

<body>

    <div class="row no-gutters flex-row-reverse ht-100v">

        <!-- bg-gray-100  bg-delicate  -->
        <div class=" overflow-auto col-lg-6 bg-custom-login bg-img-login-right-register d-flex align-items-center justify-content-center">

            <!-- Preloader -->
            <div id="divLoading">
                <!-- Cargar Loader con CSS -->
                <div class="lds-double-ring">
                    <div></div>
                    <div></div>
                </div>
                <!-- Cargar Loader con SVG -->
                <!--   <img src="<?= media(); ?>/img/loading.svg" alt="Loading"> -->
            </div>
            <!-- Fin Preloader -->

            <div class="bg-login-right-cover">
            </div>


            <div class="z-index-10 login-wrapper-register  wd-250 wd-sm-350 mg-y-30">

                <div class="tx-center login-logo-right mb-2">
                    <img src="<?= media(); ?>/img/logo-sm-dark.png" height="120" alt="logo" class="auth-logo">
                </div>

                <h4 class="tx-inverse tx-center tx-uppercase tx-semibold">Registro de Usuario</h4>
                <p class="tx-center mg-b-30 tx-custom-subtitle">Llena los datos requeridos, para ser parte de la familia Histoclin</p>

                <!-- Form Login -->
                <form class="row" name="formUsuarioRegister" id="formUsuarioRegister" action="" data-parsley-validate>

                    <!-- Pais -->
                    <div class="form-group col-12 col-sm-6">
                        <label class="login-label" for="comboPais">Pais:</label>
                        <div id=" slWrapperPais" class="parsley-select">
                            <select class="select2 mb-3 custom-select selectForm100" data-id="" data-parsley-class-handler="#slWrapperPais" data-parsley-errors-container="#slErrorContainerPais" name="comboPais" id="comboPais" style="width: 100%" data-parsley-required>
                            </select>
                            <div id="slErrorContainerPais"></div>
                        </div>
                    </div>
                    <!-- Fin Pais -->

                    <!-- Tipo Licencia -->
                    <div class="form-group col-12 col-sm-6">
                        <label class="login-label" for="comboTipoLicencia">Tipo Licencia:</label>
                        <div id=" slWrapperTipoLicencia" class="parsley-select">
                            <select class="select2 mb-3 custom-select selectForm100" data-id="" data-parsley-class-handler="#slWrapperTipoLicencia" data-parsley-errors-container="#slErrorContainerTipo Licencia" name="comboTipoLicencia" id="comboTipoLicencia" style="width: 100%" data-parsley-required>
                            </select>
                            <div id="slErrorContainerTipo Licencia"></div>
                        </div>
                    </div>
                    <!-- Fin Tipo Licencia -->

                    <!-- Nombre -->
                    <div class="form-group col-12 col-sm-6">
                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="inputRegisterNombreUsuario">Nombre:</label>
                        <span class="bar-left-input-row-init"><i class="fa-thin fa-user-doctor fa-fw tx-18 lh-0 op-6"></i></span>
                        <input type="text" class="form-control inputForm100" name="inputRegisterNombreUsuario" id="inputRegisterNombreUsuario" placeholder="Ingrese Nombre" required="">
                    </div><!-- form-group -->
                    <!-- Fin Nombre -->

                    <!-- Apellido Paterno -->
                    <div class="form-group col-12 col-sm-6">
                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="inputRegisterApellidoPaternoUsuario">Apellido Paterno:</label>
                        <span class="bar-left-input-row-init"><i class="fa-thin fa-user-doctor fa-fw tx-18 lh-0 op-6"></i></span>
                        <input type="text" class="form-control inputForm100" name="inputRegisterApellidoPaternoUsuario" id="inputRegisterApellidoPaternoUsuario" placeholder="Ingrese Apellido Paterno" required="">
                    </div><!-- form-group -->
                    <!-- Fin Apellido Paterno -->

                    <!-- Apellido Materno -->
                    <div class="form-group col-12 col-sm-6">
                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="inputRegisterApellidoMaternoUsuario">Apellido Materno:</label>
                        <span class="bar-left-input-row-init"><i class="fa-thin fa-user-doctor fa-fw tx-18 lh-0 op-6"></i></span>
                        <input type="text" class="form-control inputForm100" name="inputRegisterApellidoMaternoUsuario" id="inputRegisterApellidoMaternoUsuario" placeholder="Ingrese Apellido Materno" required="">
                    </div><!-- form-group -->
                    <!-- Fin Apellido Materno -->

                    <!-- Sexo -->
                    <div class="form-group col-12 col-sm-6">
                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="comboSexo">Sexo:</label>
                        <div id="slWrapper1" class="parsley-select">
                            <select class="select2 mb-3 custom-select selectForm100" data-parsley-class-handler="#slWrapper1" data-parsley-errors-container="#slErrorContainer2" name="comboSexo" id="comboSexo" style="width: 100%" required="">
                                <option value="" selected="selected" disabled>Seleccione una opcion</option>
                                <option value="2">Femenino</option>
                                <option value="1">Masculino</option>
                            </select>
                            <div id="slErrorContainer2"></div>
                        </div>
                    </div>
                    <!-- Fin Sexo -->

                    <!-- Especialidad -->
                    <div class="form-group col-12 col-sm-6">
                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="comboEspecialidad">Especialidad:</label>
                        <div id="slWrapper" class="parsley-select">
                            <select class="select2 mb-3 custom-select selectForm100" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer1" name="comboEspecialidad" id="comboEspecialidad" style="width: 100%" required>

                            </select>
                            <div id="slErrorContainer1"></div>
                        </div>
                    </div>
                    <!-- Fin Especialidad -->

                    <!-- Escuela -->
                    <div class="form-group col-12 col-sm-6">
                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="inputRegisterEscuela">Escuela:</label>
                        <span class="bar-left-input-row-init"><i class="fa-thin fa-graduation-cap fa-fw tx-18 lh-0 op-6"></i></span>
                        <input type="text" class="form-control inputForm100" name="inputRegisterEscuela" id="inputRegisterEscuela" placeholder="Ingrese Escuela" required="">
                    </div><!-- form-group -->
                    <!-- Fin Escuela -->

                    <!-- Cedula Profesional -->
                    <div class="form-group col-12 col-sm-6">
                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="inputRegisterCedula">Cédula Profesional:</label>
                        <span class="bar-left-input-row-init"><i class="fa-thin fa-seal-exclamation fa-fw tx-18 lh-0 op-6"></i></span>
                        <input type="text" class="form-control inputForm100" name="inputRegisterCedula" id="inputRegisterCedula" placeholder="Ingrese Cédula" required="">
                    </div><!-- form-group -->
                    <!-- Fin Cedula Profesional -->

                    <!-- Origen Entera -->
                    <div class="form-group col-12 col-sm-6">
                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="comboOrigenEntera">¿Cómo se enteró de nosotros?:</label>
                        <div id="slWrapper" class="parsley-select">
                            <select class="select2 mb-3 custom-select selectForm100" data-parsley-class-handler="#slWrapper" data-parsley-errors-container="#slErrorContainer1" name="comboOrigenEntera" id="comboOrigenEntera" style="width: 100%" required>

                            </select>
                            <div id="slErrorContainer1"></div>
                        </div>
                    </div>
                    <!-- Fin  Origen Entera -->

                    <!-- Telefono Móvil -->
                    <div class="form-group col-12 col-sm-6">
                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="inputRegisterTelefono">Telefono Móvil:</label>
                        <span class="bar-left-input-row-init"><i class="fa-thin fa-mobile-screen fa-fw tx-18 lh-0 op-6"></i></span>
                        <input type="text" class="form-control inputForm100" name="inputRegisterTelefono" id="inputRegisterTelefono" placeholder="Ingrese Telefono Móvil" required="">
                    </div><!-- form-group -->
                    <!-- Fin Telefono Móvil -->

                    <!-- Email -->
                    <div class="form-group col-12 col-sm-6">
                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="inputRegisterEmail">Usuario (Correo Electronico):</label>
                        <span class="bar-left-input-row-init"><i class="fa-thin fa-at fa-fw tx-18 lh-0 op-6"></i></span>
                        <input type="email" class="form-control inputForm100" name="inputRegisterEmail" id="inputRegisterEmail" placeholder="Ingrese Correo Electronico" required="">
                    </div><!-- form-group -->
                    <!-- Fin Email -->

                    <!-- Password -->
                    <div class="form-group show-password-parent col-12 col-sm-6">
                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="inputRegisterPassword">Contraseña:</label>
                        <span class="bar-left-input-row-init"><i class="fa-thin fa-unlock-keyhole fa-fw tx-18 lh-0 op-6"></i></span>
                        <input type="password" class="form-control inputForm100" name="inputRegisterPassword" id="inputRegisterPassword" placeholder="Ingrese Contraseña" required="">
                        <span class="show-password-register show text-primary"><i class="fa-regular fa-eye mostrar-password"></i></span>
                    </div><!-- form-group -->
                    <!-- Fin Password -->

                    <!-- Password -->
                    <div class="form-group show-password-parent col-12 col-sm-6">
                        <label class="d-block tx-12 tx-spacing-1 ml-1 login-label" for="inputRegisterConfirmPassword">Confirmar Contraseña:</label>
                        <span class="bar-left-input-row-init"><i class="fa-thin fa-unlock-keyhole fa-fw tx-18 lh-0 op-6"></i></span>
                        <input type="password" class="form-control inputForm100" name="inputRegisterConfirmPassword" id="inputRegisterConfirmPassword" placeholder="Confirmar Contraseña" required="">
                        <span class="show-password-register show text-primary"><i class="fa-regular fa-eye mostrar-password"></i></span>
                    </div><!-- form-group -->
                    <!-- Fin Password -->


                    <div class="form-group tx-12 login-label col-12">Al hacer clic en el botón
                        <i class="fa-regular fa-angles-left"></i> <strong>Finalizar Registro</strong> <i class="fa-regular fa-angles-right"></i>, acepta nuestra
                        <a href="#" class="" data-bs-toggle="modal" data-bs-target="#ModalPP"><strong>política de privacidad</strong> </a> y los
                        <a href="#" class="" data-bs-toggle="modal" data-bs-target="#ModalTCU"><strong>términos y condiciones de uso</strong></a> de nuestro sitio web.
                    </div>


                    <div class="form-group col-12">
                        <button type="submit" class="btn btn-pill btn-primary-gradient me-2 btn-inicio-guardar d-flex justify-content-center align-items-center" id="btnActionFormRegister">
                            <div class="d-flex justify-content-center align-items-center">
                                <i class="fa-regular fa-file-signature fa-fw fa-lg me-1"></i>
                                <span class="">Finalizar Registro</span>
                                <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                            </div>
                        </button>
                    </div>

                </form>
                <!-- Fin Form Login -->

                <div class="mg-t-20 tx-center">¿Ya eres usuario? <a href=" <?= base_url(); ?>/login" class="tx-primary">Iniciar Sesión</a></div>

            </div><!-- login-wrapper -->

        </div><!-- col -->



        <div class="col-lg-6 bg-white panel-login-left bg-img-login-left-register d-flex align-items-center justify-content-center">

            <div class="bg-login-left-cover">
            </div>

            <div class="wd-350 wd-xl-450 mg-y-30 login-info">
                <div class="tx-center">
                    <img src="<?= media(); ?>/img/logo-sm-dark.png" height="120" alt="logo" class="auth-logo">
                </div>
                <h5 class="tx-inverse tx-center tx-gray-100 mt-2 mb-0 ">Sistema de Gestión Médico-Administrativa</h5>
                <div class="tx-center tx-gray-200 mg-b-60">Expediente Clínico Electrónico</div>

                <h5 class="tx-inverse tx-gray-100">¿Por qué Histoclin?</h5>
                <p class="tx-gray-200 tx-13">En Histoclin deseamos que tengas una experiencia única en el manejo de tus expedientes clínicos.</p>
                <p class="tx-gray-200 tx-13 mg-b-60">Nos comprometemos a darte el mejor servicio que mereces, para que el uso de Histoclin sea un complemento amigable a tu proceso de Consulta y no una carga adicional.</p>

            </div><!-- wd-500 -->

        </div>

    </div><!-- row -->


    <!-- ########## START: MODAL TERMINOS Y CONDICIONES DE USO Y AVISO DE PRIVACIDAD ########## -->
    <?php require_once("Views/Template/Modals/modalTerminos.php"); ?>
    <?php require_once("Views/Template/Modals/modalPrivacidad.php"); ?>
    <!-- ########## END: MODAL TERMINSO Y CONDICIONES DE USO ########## -->


    <!-- ########## START: MODAL ALERTAS ########## -->
    <?php require_once("Views/Template/Modals/modalAlertas.php"); ?>
    <!-- ########## END: MODAL ALERTAS ########## -->


    <!-- Custom Scripts -->
    <script>
        const base_url = "<?= base_url(); ?>";
    </script>

    <!-- JQUERY JS -->
    <script src="<?= media(); ?>/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="<?= media(); ?>/plugins/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>

    <!-- MDB -->
    <script type="text/javascript" src="<?= media(); ?>/plugins/MDB5-3.9.0/js/mdb.min.js"></script>

    <!-- JQUERY UI -->
    <script src="<?= media(); ?>/plugins/jquery-ui-1.13.0/jquery-ui.min.js"></script>

    <!-- Select2 -->
    <script src="<?= media(); ?>/lib/select2/js/select2.min.js"></script>
    <script src="<?= media(); ?>/lib/select2/js/i18n/es.js"></script>

    <!-- Parsley -->
    <script src="<?= media(); ?>/lib/parsleyjs/parsley.min.js"></script>
    <script src="<?= media(); ?>/lib/parsleyjs/i18n/es.js"></script>

    <!-- CUSTOM JS-->
    <!-- <script src="<?= media(); ?>/js/custom.js?v=<?= version(); ?>"></script> -->

    <!-- Custom javascripts-->
    <script src="<?= media(); ?>/js/functions/function_animate.js?v=<?= version(); ?>"></script>
    <script src="<?= media(); ?>/js/functions/functions_admin.js?v=<?= version(); ?>"></script>
    <!-- <script src="<?= media(); ?>/js/functions/herramientas/function_usuarios.js?v=<?= version(); ?>"></script> -->
    <script src="<?= media(); ?>/js/functions/<?= $data['page_functions_js']; ?>?v=<?= version(); ?>"></script>

</body>

</html>