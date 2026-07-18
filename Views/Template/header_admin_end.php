<!-- STYLE CSS -->
<link href="<?= media(); ?>/css/style.css?v=<?= version(); ?>" rel="stylesheet" />
<link href="<?= media(); ?>/css/dark-style.css?v=<?= version(); ?>" rel="stylesheet" />
<link href="<?= media(); ?>/css/skin-modes.css?v=<?= version(); ?>" rel="stylesheet" />

<!-- P-scroll bar css-->
<link href="<?= media(); ?>/plugins/p-scroll/perfect-scrollbar.css?v=<?= version(); ?>" rel="stylesheet" />

<!--- FONT-ICONS CSS -->
<link href="<?= media(); ?>/plugins/iconfonts/icons.css?v=<?= version(); ?>" rel="stylesheet" />
<link rel="stylesheet" href="<?= media(); ?>/iconfonts/fontawesome/css/all.css?v=<?= version(); ?>">

<!-- SIDEBAR CSS -->
<link href="<?= media(); ?>/plugins/sidebar/sidebar.css?v=<?= version(); ?>" rel="stylesheet">

<!-- INTERNAL GALLERY CSS -->
<link href="<?= media(); ?>/plugins/gallery/gallery.css?v=<?= version(); ?>" rel="stylesheet">

<!-- COLOR SKIN CSS -->
<link id="theme" rel="stylesheet" type="text/css" media="all" href="<?= media(); ?>/colors/color1.css?v=<?= version(); ?>" />

<!-- CUSTOM -->
<link rel="stylesheet" href="<?= media(); ?>/css/amores.css?v=<?= version(); ?>">

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

<body class="<?= $class_mode; ?>">

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <!-- Cargar Global Loader con CSS -->
        <div class="lds-double-ring">
            <div></div>
            <div></div>
        </div>
        <!-- Cargar Global Loader con SVG -->
        <!-- <img src="<?= media(); ?>/images/loader.svg" class="loader-img" alt="Loader"> -->
    </div>
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">