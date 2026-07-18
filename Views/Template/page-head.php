<div class="page-main">


    <!-- HEADER -->
    <div class="header hor-header">
        <div class="container">
            <div class="d-flex justify-content-start align-items-center">

                <a class="animated-arrow hor-toggle horizontal-navtoggle"><span></span></a>

                <a class="header-brand1" href="<?= base_url(); ?>/inicio">
                    <img src="<?= media(); ?>/images/brand/logo-3.png" class="header-brand-img desktop-logo" alt="logo">
                    <img src="<?= media(); ?>/images/brand/logo.png" class="header-brand-img light-logo" alt="logo">
                </a>
                <!-- LOGO -->
                 <!-- d-md-block -->
                <div class="d-flex main-header-center ms-3 d-none">
                    <input class="form-control" placeholder="Seleccionar Menu" type="search">
                    <!-- <button class="btn"><i class="fa fa-search" aria-hidden="true"></i></button> -->
                </div>
                <div class="d-flex order-lg-2 ms-auto header-right-icons">

                <!-- d-md-block -->
                    <div class="dropdown d-lg-none  d-none"> 
                        <a href="#" class="nav-link icon" data-bs-toggle="dropdown">
                            <i class="fe fe-search"></i>
                        </a>
                        <div class="dropdown-menu header-search dropdown-menu-start">
                            <div class="input-group w-100 p-2">
                                <input type="text" class="form-control" placeholder="Seleccionar Menu">
                                <div class="input-group-text btn btn-primary bg-primary-gradient">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- SEARCH -->
                    <button class="navbar-toggler navresponsive-toggler d-md-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon fe fe-more-vertical text-dark"></span>
                    </button>

                    <!-- User -->
                    <div class="dropdown d-none d-md-flex profile-1">
                        <a href="#" data-bs-toggle="dropdown" class="nav-link icon pe-2 leading-none d-flex">
                            <span>

                                <i class="fa-light fa-user tx-20"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <div class="drop-heading">
                                <div class="text-center">
                                    <h5 class="text-dark mb-0 tx-12"><?= $session->get('nombre'); ?></h5>
                                    <small class="text-muted"><?= $session->get('rol'); ?></small>
                                </div>
                            </div>
                            <div class="dropdown-divider m-0"></div>
                            <!-- <a class="dropdown-item" href="">
                                <i class="dropdown-icon fa-regular fa-key"></i> Cambiar Contraseña
                            </a> -->
                            <a class="dropdown-item" href="<?= base_url(); ?>/logout">
                                <i class="dropdown-icon fa-regular fa-power-off"></i> Cerrar sesión
                            </a>
                        </div>
                    </div>
                    <!-- Fin User -->

                </div>
            </div>
        </div>
    </div>
    <!-- End HEADER -->

    <!-- Mobile Header -->
    <div class="mobile-header hor-mobile-header">
        <div class="container">
            <div class="d-flex">
                <a class="animated-arrow hor-toggle horizontal-navtoggle"><span></span></a>
                <a class="header-brand" href="index.html">

                    <img src="<?= media(); ?>/images/brand/logo.png" class="header-brand-img desktop-logo" alt="logo">
                    <img src="<?= media(); ?>/images/brand/logo-3.png" class="header-brand-img desktop-logo mobile-light" alt="logo">
                </a>
                <div class="main-header-center ms-3 d-none d-md-block">
                    <input class="form-control" placeholder="Search for anything..." type="search"> <button class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
                <div class="d-flex order-lg-2 ms-auto header-right-icons">
                    <div class="dropdown d-lg-none d-md-block d-none">
                        <a href="#" class="nav-link icon" data-bs-toggle="dropdown">
                            <i class="fe fe-search"></i>
                        </a>
                        <div class="dropdown-menu header-search dropdown-menu-start">
                            <div class="input-group w-100 p-2">
                                <input type="text" class="form-control" placeholder="Seleccionar Menu">
                                <div class="input-group-text btn btn-primary bg-primary-gradient">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- SEARCH -->
                    <button class="navbar-toggler navresponsive-toggler d-md-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon fe fe-more-vertical text-dark"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-1 navbar navbar-expand-lg  responsive-navbar navbar-dark d-md-none bg-white">
        <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
            <div class="d-flex order-lg-2 ms-auto">
                <div class="dropdown d-sm-flex d-none">
                    <a href="#" class="nav-link icon" data-bs-toggle="dropdown">
                        <i class="fe fe-search"></i>
                    </a>
                    <div class="dropdown-menu header-search dropdown-menu-start">
                        <div class="input-group w-100 p-2">
                            <input type="text" class="form-control" placeholder="Seleccionar Menu">
                            <div class="input-group-text btn btn-primary bg-primary-gradient">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SEARCH -->

                <!-- Theme-Layout -->

                <!-- FULL-SCREEN -->

                <!-- NOTIFICATIONS -->

                <!-- MESSAGE-BOX -->

                <div class="dropdown d-none d-md-flex profile-1">
                    <a href="#" data-bs-toggle="dropdown" class="nav-link icon pe-2 leading-none d-flex">
                        <span>
                            <i class="fa-light fa-user tx-20"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <div class="drop-heading">
                            <div class="text-center">
                                <h5 class="text-dark mb-0 tx-12"><?= $session->get('nombre'); ?></h5>
                                <small class="text-muted"><?= $session->get('rol'); ?></small>
                            </div>
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <!-- <a class="dropdown-item" href="">
                            <i class="dropdown-icon fa-regular fa-key"></i> Cambiar Contraseña
                        </a> -->
                        <a class="dropdown-item" href="<?= base_url(); ?>/logout">
                            <i class="dropdown-icon fa-regular fa-power-off"></i> Cerrar sesión
                        </a>
                    </div>
                </div>


                <div class="dropdown d-md-flex profile-1">
                    <a href="#" data-bs-toggle="dropdown" class="nav-link icon pe-2 leading-none d-flex">
                        <span>
                            <i class="fa-light fa-user tx-20"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <div class="drop-heading">
                            <div class="text-center">
                                <h5 class="text-dark mb-0"><?= $session->get('nombre'); ?></h5>
                                <small class="text-muted"><?= $session->get('rol'); ?></small>
                            </div>
                        </div>
                        <!-- <a class="dropdown-item" href="">
                            <i class="dropdown-icon fa-regular fa-key"></i> Cambiar Contraseña
                        </a> -->
                        <a class="dropdown-item" href="<?= base_url(); ?>/logout">
                            <i class="dropdown-icon fa-regular fa-power-off"></i> Cerrar sesión
                        </a>
                    </div>
                </div>
                <!-- <div class="dropdown d-md-flex header-settings">
                    <a href="#" class="nav-link icon " data-bs-toggle="sidebar-right" data-target=".sidebar-right">
                        <i class="fe fe-menu"></i>
                    </a>
                </div> -->
                <!-- SIDE-MENU -->
            </div>
        </div>
    </div>
    <!-- /Mobile Header -->

    <!--/Horizontal-main -->
    <div class="sticky">
        <div class="horizontal-main hor-menu clearfix">
            <div class="horizontal-mainwrapper container clearfix">
                <!--Nav-->
                <nav class="horizontalMenu clearfix">
                    <ul class="horizontalMenu-list">
                        <li aria-haspopup="true">
                            <a href="<?= base_url(); ?>/inicio" class="">
                                <i class="fa-regular fa-grid-2"></i> Inicio
                            </a>
                        </li>

                        <?php if (
                            $data['permisos'][MOD_USUARIOS]['r'] ||
                            $data['permisos'][MOD_ROLES]['r'] ||
                            $data['permisos'][MOD_CONFIG]['r'] ||
                            $data['permisos'][MOD_CLASIFICACION_INGRESOS]['r'] ||
                            $data['permisos'][MOD_CLASIFICACION_GASTOS]['r']
                        ) { ?>
                            <li aria-haspopup="true">
                                <span class="horizontalMenu-click">
                                    <i class="horizontalMenu-arrow fa fa-angle-down"></i>
                                </span>

                                <a href="#" class="sub-icon">
                                    <i class="fa-regular fa-gear"></i> Configuración <i class="fa fa-angle-down horizontal-icon"></i>
                                </a>
                                <div class="horizontal-megamenu clearfix">
                                    <div class="container">
                                        <div class="mega-menubg">
                                            <div class="row">

                                                <?php if (
                                                    $data['permisos'][MOD_USUARIOS]['r'] ||
                                                    $data['permisos'][MOD_ROLES]['r'] ||
                                                    $data['permisos'][MOD_CONFIG]['r']
                                                ) { ?>
                                                    <div class="col-lg-3 col-md-12 col-xs-12 link-list">

                                                        <ul>

                                                            <li>
                                                                <h3 class="fs-14 fw-bold mb-1">
                                                                    <i class="fa-regular fa-shield-keyhole text-success text-success-shadow fs-16"></i> Seguridad
                                                                </h3>
                                                            </li>
                                                            <div class="dropdown-divider m-0"></div>
                                                            <?php if ($data['permisos'][MOD_USUARIOS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/herramientas/usuarios">Registro de Usuarios</a></li>
                                                            <?php  } ?>

                                                            <?php if ($data['permisos'][MOD_ROLES]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/herramientas/roles">Registro de Roles de Acceso</a></li>
                                                            <?php  } ?>

                                                            <?php if ($data['permisos'][MOD_CONFIG]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/configuracion">Configurar Parámetros Generales</a></li>
                                                            <?php  } ?>
                                                        </ul>

                                                    </div>

                                                <?php  } ?>

                                                <?php if (
                                                    $data['permisos'][MOD_CLASIFICACION_INGRESOS]['r'] ||
                                                    $data['permisos'][MOD_CLASIFICACION_GASTOS]['r']
                                                ) { ?>
                                                    <div class="col-lg-3 col-md-12 col-xs-12 link-list">
                                                        <ul>
                                                            <li>
                                                                <h3 class="fs-14 fw-bold mb-1">
                                                                    <i class="fa-regular fa-rectangle-vertical-history text-success text-success-shadow fs-16"></i> Catalogos
                                                                </h3>
                                                            </li>
                                                            <div class="dropdown-divider m-0"></div>
                                                            <?php if ($data['permisos'][MOD_CLASIFICACION_INGRESOS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu text-muted" href="<?= base_url(); ?>/catalogos/clasificacionIngresos">Clasificación de Ingresos</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_CLASIFICACION_GASTOS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu text-muted" href="<?= base_url(); ?>/catalogos/clasificacionGastos">Clasificación de Gastos</a></li>
                                                            <?php  } ?>
                                                        </ul>
                                                    </div>
                                                <?php  } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php  } ?>

                        <?php
                        if (
                            $data['permisos'][MOD_RECIBOS_COBRO]['r'] ||
                            $data['permisos'][MOD_RECIBOS_COBRO_OTROS_CONCEPTOS]['r'] ||
                            $data['permisos'][MOD_RECIBOS_COBRO_PASADOS]['r'] ||
                            $data['permisos'][MOD_REGISTRO_GASTOS]['r'] ||
                            $data['permisos'][MOD_TRANSFERIR_RECIBO]['r'] ||
                            $data['permisos'][MOD_ESTATUS_RESIDENTE]['r'] ||
                            $data['permisos'][MOD_REGISTRO_ACTIVACION_TAGS]['r'] ||
                            $data['permisos'][MOD_REGISTRO_VISITAS_QR]['r'] ||
                            $data['permisos'][MOD_REGLAMENTO_INTERNO]['r'] ||
                            $data['permisos'][MOD_DIRECTORIO_RESIDENTES]['r'] ||
                            $data['permisos'][MOD_REPORTES_VIGILANCIA]['r'] ||
                            $data['permisos'][MOD_ESTATUS_ASUNTOS_JURIDICOS]['r'] ||
                            $data['permisos'][MOD_REGISTRO_SEGUIMIENTO_ASUNTOS_JURIDICOS]['r'] ||
                            $data['permisos'][MOD_INFORMES_INGRESOS_EGRESOS]['r'] ||
                            $data['permisos'][MOD_INFORMES_ADEUDOS_CALLE]['r'] ||
                            $data['permisos'][MOD_INFORMES_ESTADO_CUENTA_RESIDENTE]['r'] ||
                            $data['permisos'][MOD_REPORTES_INGRESOS]['r'] ||
                            $data['permisos'][MOD_REPORTES_PAGOS_PASADOS]['r'] ||
                            $data['permisos'][MOD_REPORTES_CARTERA_VENCIDA]['r'] ||
                            $data['permisos'][MOD_REPORTES_GASTOS]['r']
                        ) { ?>
                            <li aria-haspopup="true">
                                <span class="horizontalMenu-click">
                                    <i class="horizontalMenu-arrow fa fa-angle-down"></i>
                                </span>

                                <a href="#" class="sub-icon">
                                    <i class="fa-regular fa-house-laptop"></i> Administración <i class="fa fa-angle-down horizontal-icon"></i>
                                </a>
                                <div class="horizontal-megamenu clearfix">
                                    <div class="container">
                                        <div class="mega-menubg">
                                            <div class="row">

                                                <?php if (
                                                    $data['permisos'][MOD_RECIBOS_COBRO]['r'] ||
                                                    $data['permisos'][MOD_RECIBOS_COBRO_OTROS_CONCEPTOS]['r'] ||
                                                    $data['permisos'][MOD_RECIBOS_COBRO_PASADOS]['r'] ||
                                                    $data['permisos'][MOD_REGISTRO_GASTOS]['r'] ||
                                                    $data['permisos'][MOD_TRANSFERIR_RECIBO]['r']
                                                ) { ?>
                                                    <div class="col-lg-4 col-md-12 col-xs-12 link-list">
                                                        <ul>
                                                            <li>
                                                                <h3 class="fs-14 fw-bold mb-1">
                                                                    <i class="fa-regular fa-file-invoice-dollar text-secondary text-secondary-shadow fs-16"></i> Ingresos y Egresos
                                                                </h3>
                                                            </li>
                                                            <div class="dropdown-divider m-0"></div>
                                                            <?php if ($data['permisos'][MOD_RECIBOS_COBRO]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/recibos">Registro de Recibos de Cobro</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_RECIBOS_COBRO_OTROS_CONCEPTOS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/recibos/recibosOtrosConceptos">Registro de Recibos de Cobro Otros Conceptos</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_RECIBOS_COBRO_PASADOS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/recibosAnteriores">Registro de Recibos de Cobro Pasados</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_REGISTRO_GASTOS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/gastos">Registro de Gastos</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_TRANSFERIR_RECIBO]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/recibos/transferirRecibo">Transferir Recibo</a></li>
                                                            <?php  } ?>
                                                        </ul>
                                                    </div>
                                                <?php  } ?>

                                                <?php if (
                                                    $data['permisos'][MOD_ESTATUS_RESIDENTE]['r'] ||
                                                    $data['permisos'][MOD_REGISTRO_ACTIVACION_TAGS]['r'] ||
                                                    $data['permisos'][MOD_REGISTRO_VISITAS_QR]['r'] ||
                                                    $data['permisos'][MOD_REGLAMENTO_INTERNO]['r'] ||
                                                    $data['permisos'][MOD_DIRECTORIO_RESIDENTES]['r'] ||
                                                    $data['permisos'][MOD_REPORTES_VIGILANCIA]['r']
                                                ) { ?>
                                                    <div class="col-lg-4 col-md-12 col-xs-12 link-list">
                                                        <ul>
                                                            <li>
                                                                <h3 class="fs-14 fw-bold mb-1">
                                                                    <i class="fa-regular fa-user-police-tie text-secondary text-secondary-shadow fs-16"></i> Seguridad y Vigilancia
                                                                </h3>
                                                            </li>
                                                            <div class="dropdown-divider m-0"></div>
                                                            <?php if ($data['permisos'][MOD_ESTATUS_RESIDENTE]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/estatusResidente">Consultar Estatus de Residente</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_REGISTRO_ACTIVACION_TAGS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/tags">Registro y Activación de TAGS</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_REGISTRO_VISITAS_QR]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/visitas/registroVisitas">Registro de Visitas con Código QR</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_REGLAMENTO_INTERNO]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= media(); ?>/files/docs/reglamento.pdf" target="_blank">Reglamento Interno</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_DIRECTORIO_RESIDENTES]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/residentes/Directorio">Directorio de Residentes</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_REPORTES_VIGILANCIA]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/reportesVigilancia">Reportes de Vigilancia</a></li>
                                                            <?php  } ?>
                                                        </ul>
                                                    </div>
                                                <?php  } ?>

                                                <?php if (
                                                    $data['permisos'][MOD_ESTATUS_ASUNTOS_JURIDICOS]['r'] ||
                                                    $data['permisos'][MOD_REGISTRO_SEGUIMIENTO_ASUNTOS_JURIDICOS]['r']
                                                ) { ?>
                                                    <div class="col-lg-4 col-md-12 col-xs-12 link-list">
                                                        <ul>
                                                            <li>
                                                                <h3 class="fs-14 fw-bold mb-1">
                                                                    <i class="fa-regular fa-gavel text-secondary text-secondary-shadow fs-16"></i> Juridico
                                                                </h3>
                                                            </li>
                                                            <div class="dropdown-divider m-0"></div>
                                                            <?php if ($data['permisos'][MOD_ESTATUS_ASUNTOS_JURIDICOS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="#">Registro de Estatus</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_REGISTRO_SEGUIMIENTO_ASUNTOS_JURIDICOS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="#">Registro de Seguimiento de Asuntos Jurídicos</a></li>
                                                            <?php  } ?>
                                                        </ul>
                                                    </div>
                                                <?php  } ?>

                                                <?php if (
                                                    $data['permisos'][MOD_INFORMES_INGRESOS_EGRESOS]['r'] ||
                                                    $data['permisos'][MOD_INFORMES_ADEUDOS_CALLE]['r'] ||
                                                    $data['permisos'][MOD_INFORMES_ESTADO_CUENTA_RESIDENTE]['r']
                                                ) { ?>
                                                    <div class="col-lg-4 col-md-12 col-xs-12 link-list">
                                                        <ul>
                                                            <li>
                                                                <h3 class="fs-14 fw-bold mb-1">
                                                                    <i class="fa-regular fa-window-frame text-secondary text-secondary-shadow fs-16"></i> Transparencia Informes
                                                                </h3>
                                                            </li>
                                                            <div class="dropdown-divider m-0"></div>
                                                            <?php if ($data['permisos'][MOD_INFORMES_INGRESOS_EGRESOS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/estadoResultados">Informe de Ingresos y Egresos</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_INFORMES_ADEUDOS_CALLE]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/informeAdeudosCalle">Informe de Adeudos por Calle</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_INFORMES_ESTADO_CUENTA_RESIDENTE]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/informeEstadoCuentaResdiente">Informe de Estado de Cuenta de Residente</a></li>
                                                            <?php  } ?>

                                                        </ul>
                                                    </div>
                                                <?php  } ?>

                                                <?php if (
                                                    $data['permisos'][MOD_REPORTES_INGRESOS]['r'] ||
                                                    $data['permisos'][MOD_REPORTES_PAGOS_PASADOS]['r'] ||
                                                    $data['permisos'][MOD_REPORTES_CARTERA_VENCIDA]['r'] ||
                                                    $data['permisos'][MOD_REPORTES_GASTOS]['r']
                                                ) { ?>
                                                    <div class="col-lg-4 col-md-12 col-xs-12 link-list">
                                                        <ul>
                                                            <li>
                                                                <h3 class="fs-14 fw-bold mb-1">
                                                                    <i class="fa-regular fa-window-frame text-secondary text-secondary-shadow fs-16"></i> Transparencia Reportes
                                                                </h3>
                                                            </li>
                                                            <div class="dropdown-divider m-0"></div>
                                                            <?php if ($data['permisos'][MOD_REPORTES_INGRESOS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/reporteIngresos">Reporte de Ingresos</a></li>
                                                            <?php  } ?>

                                                            <?php if ($data['permisos'][MOD_REPORTES_GASTOS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/reporteEgresos">Reporte de Gastos</a></li>
                                                            <?php  } ?>

                                                            <?php if ($data['permisos'][MOD_REPORTES_PAGOS_PASADOS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="#">Reporte de Recibos Anteriores</a></li>
                                                            <?php  } ?>
                                                            <?php if ($data['permisos'][MOD_REPORTES_CARTERA_VENCIDA]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="#">Reporte de Cartera Vencida</a></li>
                                                            <?php  } ?>

                                                        </ul>
                                                    </div>
                                                <?php  } ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php  } ?>


                        <!-- Menu Seguimiento -->
                        <?php if (
                            $data['permisos'][MOD_BUZON]['r'] ||
                            $data['permisos'][MOD_VISITAS]['r']
                        ) { ?>

                            <li aria-haspopup="true">

                                <span class="horizontalMenu-click">
                                    <i class="horizontalMenu-arrow fa fa-angle-down"></i>
                                </span>

                                <a href="#" class="sub-icon">
                                    <i class="fa-regular fa-chalkboard"></i> Seguimiento <i class="fa fa-angle-down horizontal-icon"></i>
                                </a>
                                <div class="horizontal-megamenu clearfix">
                                    <div class="container">
                                        <div class="mega-menubg">
                                            <div class="row">

                                                <?php if (
                                                    $data['permisos'][MOD_BUZON]['r'] ||
                                                    $data['permisos'][MOD_VISITAS]['r']
                                                ) { ?>
                                                    <div class="col-lg-3 col-md-12 col-xs-12 link-list">

                                                        <ul>

                                                            <li>
                                                                <h3 class="fs-14 fw-bold mb-1">
                                                                    <i class="fa-regular fa-shield-keyhole text-success text-success-shadow fs-16"></i> Portal de Residentes
                                                                </h3>
                                                            </li>
                                                            <div class="dropdown-divider m-0"></div>
                                                            <?php if ($data['permisos'][MOD_BUZON]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/buzon/buzonList">Buzón de Quejas y Sugerencias</a></li>
                                                            <?php  } ?>

                                                            <?php if ($data['permisos'][MOD_VISITAS]['r']) { ?>
                                                                <li aria-haspopup="true"><a class="wrapper-menu" href="<?= base_url(); ?>/visitas/SeguimientoVisitas">Registro de Visitas</a></li>
                                                            <?php  } ?>

                                                        </ul>

                                                    </div>

                                                <?php  } ?>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php  } ?>
                        <!-- Menu Seguimiento End -->

                    </ul>
                </nav>
                <!--Nav-->
            </div>
        </div>
    </div>
    <!--/Horizontal-main -->

</div>