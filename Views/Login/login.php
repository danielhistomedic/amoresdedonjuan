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

    <!-- BOOTSTRAP CSS -->
    <link href="<?= media(); ?>/plugins/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- MDB -->
    <link href="<?= media(); ?>/plugins/MDB5-3.9.0/css/mdb.min.css" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="<?= media(); ?>/css/style.css" rel="stylesheet" />
    <link href="<?= media(); ?>/css/dark-style.css" rel="stylesheet" />
    <link href="<?= media(); ?>/css/skin-modes.css" rel="stylesheet" />

    <!-- SINGLE-PAGE CSS -->
    <link href="<?= media(); ?>/plugins/single-page/css/main.css" rel="stylesheet" type="text/css">

    <!--C3 CHARTS CSS -->
    <link href="<?= media(); ?>/plugins/charts-c3/c3-chart.css" rel="stylesheet" />

    <!-- P-scroll bar css-->
    <link href="<?= media(); ?>/plugins/p-scroll/perfect-scrollbar.css" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="<?= media(); ?>/plugins/iconfonts/icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= media(); ?>/iconfonts/fontawesome/css/all.css?v=<?= version(); ?>">

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="<?= media(); ?>/colors/color1.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?= media(); ?>/css/amores.css?v=<?= version(); ?>" />
    <!-- <link id="theme" rel="stylesheet" type="text/css" media="all" href="<?= media(); ?>/css/main-dark-mode.css?v=<?= version(); ?>" /> -->


</head>

<body>

    <!-- BACKGROUND-IMAGE -->
    <div class="login-img">

        <!-- GLOABAL LOADER -->
        <div id="global-loader">
            <img src="<?= media(); ?>/images/loader.svg" class="loader-img" alt="Loader">
        </div>
        <!-- /GLOABAL LOADER -->

        <!-- PAGE -->
        <div class="page">
            <div class="">
                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto">
                    <div class="text-center">
                        <img src="<?= media(); ?>/images/brand/logo.png" class="header-brand-img" alt="" style="height: 5.25rem;">
                    </div>
                </div>
                <div class="container-login100">
                    <div class="wrap-login100 p-0">
                        <div class="card-body">
                            <form class="login100-form validate-form" name="formLogin" id="formLogin" action="">
                                <span class="login100-form-title pb-0">
                                    Sistema de Administración
                                </span>
                                <span class="login100-form-title fs-16">
                                    Fraccionamiento Amores de Don Juan
                                </span>
                                <div class="wrap-input100 validate-input" data-bs-validate="Un email valido es requerido: micorreo@mail.com">
                                    <input class="input100" type="text" name="inputEmail" id="inputEmail" placeholder="Ingrese Usuario" value="<?php $usuario = isset($_GET['usuario']) ? $_GET['usuario'] : '';
                                                                                                                                                echo $usuario; ?>" required>
                                    <span class=" focus-input100"></span>
                                    <span class="symbol-input100">
                                        <i class="zmdi zmdi-email" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <div class="wrap-input100 validate-input" data-bs-validate="Password is required">
                                    <input class="input100" type="password" name="inputPassword" id="inputPassword" placeholder="Ingrese Contraseña">
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <i class="zmdi zmdi-lock" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <!-- <div class="text-end pt-1">
                                    <p class="mb-0"><a href="forgot-password.html" class="text-secondary ms-1">¿Olvidaste tu password?</a></p>
                                </div> -->

                                <div class="container-login100-form-btn">
                                    <button type="submit" class="login100-form-btn btn btn-secondary bg-warning-gradient  d-flex justify-content-center align-items-center" id="btnActionForm">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <i class="fad fa-arrow-alt-to-right fa-fw fa-lg me-1"></i>
                                            <span class="">Inicar Sesión</span>
                                            <i class="far fa-spinner fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                        </div>
                                    </button>
                                    <!-- <button type="submit" class="login100-form-btn btn-secondary bg-warning-gradient " id="btnActionForm">
                                        Inicar Sesión
                                    </button> -->
                                </div>
                                <!-- <div class="text-center pt-3">
                                    <p class="text-dark mb-0">Not a member?<a href="register.html" class="text-secondary ms-1">Create an Account</a></p>
                                </div> -->
                            </form>
                        </div>
                        <!-- <div class="card-footer">
                            <div class="d-flex justify-content-center my-3">
                                <a href="" class="social-login  text-center me-4">
                                    <i class="fa fa-google"></i>
                                </a>
                                <a href="" class="social-login  text-center me-4">
                                    <i class="fa fa-facebook"></i>
                                </a>
                                <a href="" class="social-login  text-center">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </div>
                        </div> -->
                    </div>
                </div>
                <!-- CONTAINER CLOSED -->
            </div>
        </div>
        <!-- End PAGE -->

    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->

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
    <script src="<?= media(); ?>/plugins/MDB5-3.9.0/js/mdb.min.js"></script>

    <!-- SPARKLINE JS -->
    <script src="<?= media(); ?>/js/jquery.sparkline.min.js"></script>

    <!-- CHART-CIRCLE JS -->
    <script src="<?= media(); ?>/js/circle-progress.min.js"></script>

    <!-- INPUT MASK JS -->
    <script src="<?= media(); ?>/plugins/input-mask/jquery.mask.min.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="<?= media(); ?>/plugins/p-scroll/perfect-scrollbar.js"></script>

    <!-- CUSTOM JS-->
    <script src="<?= media(); ?>/js/custom.js"></script>


    <!-- Custom javascripts-->
    <script src="<?= media(); ?>/js/functions/function_animate.js?v=<?= version(); ?>"></script>
    <script src="<?= media(); ?>/js/functions/functions_admin.js?v=<?= version(); ?>"></script>
    <script src="<?= media(); ?>/js/functions/alertas.js?v=<?= version(); ?>"></script>
    <script src="<?= media(); ?>/js/functions/<?= $data['page_functions_js']; ?>?v=<?= version(); ?>"></script>


</body>

</html>