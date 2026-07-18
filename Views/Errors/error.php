<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?= $data['page_description']; ?>">
    <meta name="author" content="Ing. Ind. Víctor Daniel Pérez Vargas">
    <meta name="keywords" content="portal, fraccionamiento, residentes">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="<?= media(); ?>/images/brand/favicon.ico" />

    <!-- TITLE -->
    <title><?= $data['page_title']; ?></title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="<?= media(); ?>/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="<?= media(); ?>/css/style.css" rel="stylesheet" />
    <link href="<?= media(); ?>/css/dark-style.css" rel="stylesheet" />
    <link href="<?= media(); ?>/css/skin-modes.css" rel="stylesheet" />
    <link href="<?= media(); ?>/css/transparent-style.css" rel="stylesheet" />

    <!--- FONT-ICONS CSS -->
    <link href="<?= media(); ?>/css/icons.css" rel="stylesheet" />

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="<?= media(); ?>/colors/color1.css" />

</head>

<?php

$session = new Session;
$theme = $session->get('theme');
/*-------------------------------------------
[ Tema de la Sesión. Default light-mode ]*/
if ($theme == '' || $theme == 'light-mode') {
    $class_mode = "dark-hormenu light-mode color-header";
} else {
    $class_mode = "dark-hormenu dark-mode";
}
?>


<body class="error-bg <?= $class_mode; ?>">

    <!-- GLOBAL-LOADER -->
    <!-- <div id="global-loader">
        <img src="<?= media(); ?>/images/loader.svg" class="loader-img" alt="Loader">
    </div> -->
    <!-- End GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">

        <!-- PAGE-CONTENT OPEN -->
        <div class="page-content error-page error2">
            <div class="container text-center">
                <div class="error-template">
                    <h1 class="display-1 text-dark mb-2">404<span class="fs-20">error</span></h1>
                    <h5 class="error-details text-dark">
                        Lo sentimos, página no encontrada.
                    </h5>
                    <div class="text-center">

                        <a class="btn btn-primary bg-primary-gradient mt-5 mb-5" href="<?= base_url(); ?>/inicio"> <i class="fa fa-long-arrow-left"></i> Regresar a Inicio </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- PAGE-CONTENT OPEN CLOSED -->
    </div>
    <!-- End PAGE -->

    <!-- modalAlertas start -->
    <?php require_once("Views/Template/Modals/modalAlertas.php"); ?>
    <!-- modalAlertas end -->

    <script>
        const base_url = "<?= base_url(); ?>";
        const assets = "<?= media(); ?>";
    </script>


    <!-- JQUERY JS -->
    <script src="<?= media(); ?>/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="<?= media(); ?>/plugins/bootstrap/js/popper.min.js"></script>
    <script src="<?= media(); ?>/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- SPARKLINE -->
    <script src="<?= media(); ?>/js/jquery.sparkline.min.js"></script>

    <!-- CHART-CIRCLE -->
    <script src="<?= media(); ?>/js/circle-progress.min.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="<?= media(); ?>/plugins/p-scroll/perfect-scrollbar.js"></script>

    <!-- INPUT MASK PLUGIN-->
    <script src="<?= media(); ?>/plugins/input-mask/jquery.mask.min.js"></script>

    <!-- Color Theme js -->
    <script src="<?= media(); ?>/js/themeColors.js"></script>

    <!-- CUSTOM JS -->
    <script src="<?= media(); ?>/js/custom.js"></script>

    <!-- Custom javascripts-->
    <script src="<?= media(); ?>/app/js/animate.js?v=<?= version(); ?>"></script>
    <script src="<?= media(); ?>/app/js/main.js?v=<?= version(); ?>"></script>
    <script src="<?= media(); ?>/app/js/alertas.js?v=<?= version(); ?>"></script>
    <script src="<?= media(); ?>/app/js/<?= $data['page_functions_js']; ?>?v=<?= version(); ?>"></script>

</body>

</html>