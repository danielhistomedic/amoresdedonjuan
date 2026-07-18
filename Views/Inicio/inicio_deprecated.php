<!-- Header Admin -->
<?php require_once("Views/Template/header_admin.php"); ?>

<!-- Custom Css/Script -->

<!-- Fin Custom Css/Script -->

<?php require_once("Views/Template/header_admin_end.php"); ?>
<!-- Fin Header Admin -->

<!-- Panel Head -->
<?php require_once("Views/Template/page-head.php"); ?>
<!-- Fin Panel Head -->

<!-- App Content -->
<div class="wrapper-sidemenu d-flex align-items-stretch" id="app-content-mobile">

    <!-- Page Content  -->
    <div class="page-content-custom-bg"></div>

    <div id="content" class="d-flex flex-column justify-content-start align-items-start page-content-custom">
        <div class="page-custom">
            <div class="row">

                <div class="col-12">

                    <div class="form-group pos-relative">
                        <label class="d-block tx-12 ml-1" for="inputAbrirExpediente">Buscar Expediente:</label>
                        <span class="bar-left-input"><i class="fa-regular fa-folder-user fa-fw tx-18 lh-0 op-7"></i></span>
                        <input type="text" class="form-control" name="inputAbrirExpediente" id="inputAbrirExpediente" placeholder="Escriba el nombre de su paciente..." required="">
                        <button class="btn btn-search">
                            <i class="fa-light fa-magnifying-glass"></i>
                        </button>
                    </div>

                </div>

                <div class="col-12 mt-2">

                    <div class="card">

                        <div class="card-status bg-secondary br-tr-7 br-tl-7"></div>

                        <div class="card-header">
                            <h3 class="card-title tx-15">
                                <div class="d-flex justify-content-center align-items-center">
                                    <i class="fa-regular fa-hospital-user tx-22 text-secondary"></i>
                                    <span class="ms-2"><strong>Opciones de Inicio</strong></span>
                                </div>
                            </h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-bs-toggle="card-collapse">
                                    <i class="fa-regular fa-angle-up"></i>
                                </a>
                                <!-- <i class="fa-regular fa-angle-up"></i> -->
                                <!-- <a href="#" class="card-options-remove" data-bs-toggle="card-remove">
                            <i class="fe fe-x"></i>
                        </a> -->
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="card-pay">
                                <ul class="tabs-menu nav">
                                    <li class="">
                                        <a href="#tab_citashoy" class="tab_citashoy" data-animation="slideInLeft" data-mdb-toggle="tab">
                                            <div class="d-flex justify-content-start justify-content-sm-center align-items-center">
                                                <span class="fa-stack tx-10">
                                                    <i class="fa-regular fa-clipboard-list-check" style="font-size: 25px; top: -3px; left: -6px;"></i>
                                                </span>
                                                <span class="ms-1">Citas de Hoy</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_nuevopaciente" data-mdb-toggle="tab" data-animation="fadeInRight" class="tab_nuevopaciente">
                                            <div class="d-flex justify-content-start justify-content-sm-center align-items-center">
                                                <span class="fa-stack tx-10">
                                                    <i class="fa-regular fa-user-plus" style="font-size: 22px;top: -2px;left: -3px;"></i>
                                                </span>
                                                <span class="ms-1">Nuevo Paciente</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_agendaexpress" data-mdb-toggle="tab" data-animation="slideInLeft" class="tab_agendaexpress">
                                            <div class="d-flex justify-content-start justify-content-sm-center align-items-center">
                                                <span class="fa-stack tx-10">
                                                    <i class="fa-regular fa-calendar-pen" style="font-size: 25px; top: -3px; left: -6px;"></i>
                                                </span>
                                                <span class="ms-1">Agenda Express</span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">

                                    <div class="tab-pane fade show" id="tab_citashoy">

                                    </div>

                                    <div class="tab-pane fade animationPaciente" id="tab_nuevopaciente">
                                        <form name="formPacienteInicio" id="formPacienteInicio" action="" data-parsley-validate>

                                            <div class="row">

                                                <p class="col-12">Llene los datos requeridos y presione el botón <strong>Guardar</strong></p>
                                                <!-- Nombre -->
                                                <div class="form-group col-12 col-sm-4">
                                                    <label class="d-block tx-12 ml-1" for="inputNombrePaciente">Nombre:</label>
                                                    <span class="bar-left-input-row"><i class="fa-thin fa-text-height fa-fw tx-18 lh-0 op-6"></i></span>
                                                    <input type="text" autocomplete="nope" class="form-control inputForm100" name="inputNombrePaciente" id="inputNombrePaciente" placeholder="Ingrese Nombre" required>
                                                </div><!-- form-group -->
                                                <!-- Fin Nombre -->

                                                <!-- Apellido Paterno -->
                                                <div class="form-group col-12 col-sm-4">
                                                    <label class="d-block tx-12 ml-1" for="inputPaternoPaciente">Apellido Paterno:</label>
                                                    <span class="bar-left-input-row"><i class="fa-thin fa-text-height fa-fw tx-18 lh-0 op-6"></i></span>
                                                    <input type="text" autocomplete="nope" class="form-control inputForm100" name="inputPaternoPaciente" id="inputPaternoPaciente" placeholder="Ingrese Apellido Paterno" required>
                                                </div><!-- form-group -->
                                                <!-- Fin Apellido Paterno -->

                                                <!-- Apellido Materno -->
                                                <div class="form-group col-12 col-sm-4">
                                                    <label class="d-block tx-12 ml-1" for="inputMaternoPaciente">Apellido Materno:</label>
                                                    <span class="bar-left-input-row"><i class="fa-thin fa-text-height fa-fw tx-18 lh-0 op-6"></i></span>
                                                    <input type="text" autocomplete="nope" class="form-control" name="inputMaternoPaciente" id="inputMaternoPaciente" placeholder="Ingrese Apellido Materno">
                                                </div><!-- form-group -->
                                                <!-- Fin Apellido Materno -->

                                                <!-- Sexo -->
                                                <div class="form-group col-12 col-sm-4">
                                                    <label class="d-block tx-12 ml-1" for="comboSexo">Sexo:</label>
                                                    <div id="slWrapper1" class="parsley-select">
                                                        <select class="mb-3 custom-select selectForm100" data-parsley-class-handler="#slWrapper1" data-parsley-errors-container="#slErrorContainer2" name="comboSexo" id="comboSexo" style="width: 100%" required>
                                                            <option value="" selected="selected" disabled>Seleccione una opcion</option>
                                                            <option value="2">Femenino</option>
                                                            <option value="1">Masculino</option>
                                                        </select>
                                                        <div id="slErrorContainer2"></div>
                                                    </div>
                                                </div>
                                                <!-- Fin Sexo -->

                                                <!-- Fecha de Nacimiento -->
                                                <div class="form-group col-12 col-sm-4">
                                                    <label class="d-block tx-12 ml-1" for="inputFechaNacimientoPaciente">Fecha de Nacimiento:</label>
                                                    <span class="bar-left-input-row"><i class="fa-thin fa-calendar-day fa-fw tx-18 lh-0 op-6"></i></span>
                                                    <input type="text" class="form-control inputForm100 inputDateMask" data-toggle="datepicker" name="inputFechaNacimientoPaciente" id="inputFechaNacimientoPaciente" placeholder="dd/mm/aaaa" required>
                                                </div><!-- form-group -->
                                                <!-- Fin Fecha de Nacimiento -->

                                                <!-- Edad -->
                                                <div class="form-group col-12 col-sm-4 ">
                                                    <label class="d-block tx-12 ml-1">Edad:</label>
                                                    <span class="bar-left-input-row"><i class="fa-thin fa-square-info fa-fw tx-18 lh-0 op-6"></i></span>
                                                    <p class="form-control m-0 bg-read-only pForm100" id="inputEdadNuevoPaciente"></p>
                                                    <!-- <input type="text" class="form-control inputForm100" data-toggle="datepicker" name="" id="" placeholder="dd/mm/aaaa" required> -->
                                                </div><!-- form-group -->
                                                <!-- Fin Edad -->

                                                <!-- Email -->
                                                <div class="form-group col-12 col-sm-8">
                                                    <label class="d-block tx-12 ml-1" for="inputEmailPaciente">Email:</label>
                                                    <span class="bar-left-input-row"><i class="fa-thin fa-at fa-fw tx-18 lh-0 op-6"></i></span>
                                                    <input type="email" autocomplete="nope" class="form-control inputForm100" name="inputEmailPaciente" id="inputEmailPaciente" placeholder="Ingrese Email" required>
                                                </div><!-- form-group -->
                                                <!-- Fin Email -->

                                                <!-- Telefono -->
                                                <div class="form-group col-12 col-sm-4">
                                                    <label class="d-block tx-12 ml-1" for="inputTelefonoPaciente">Telefono:</label>
                                                    <span class="bar-left-input-row"><i class="fa-thin fa-mobile-screen fa-fw tx-18 lh-0 op-6"></i></span>
                                                    <input type="text" autocomplete="nope" class="form-control inputForm100" name="inputTelefonoPaciente" id="inputTelefonoPaciente" placeholder="Ingrese Telefono" required>
                                                </div><!-- form-group -->
                                                <!-- Fin Telefono -->

                                                <div class="form-group col-12 mb-0 mt-3">
                                                    <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">
                                                        <input type="hidden" name="inputIdPaciente" id="inputIdPaciente" val="">
                                                        <button type="submit" class="btn btn-pill btn-primary-gradient me-2 btn-inicio-guardar d-flex justify-content-center align-items-center" id="btnActionForm_PacienteInicio">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <i class="fa-regular fa-floppy-disk-pen fa-fw fa-lg me-1"></i>
                                                                <span class="">Guardar</span>
                                                                <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                                            </div>
                                                        </button>
                                                    </div>

                                                </div><!-- form-group -->

                                            </div>

                                        </form>

                                    </div>

                                    <div class="tab-pane fade" id="tab_agendaexpress">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


            </div>
        </div>
    </div>

    <!-- Fin Page Content  -->



</div>
<!-- Fin App Content -->

<!-- Panel footer -->
<?php require_once("Views/Template/page-footer.php"); ?>
<!--/Panel footer-->

<!-- Sidebar-right -->
<?php require_once("Views/Template/sidebar-rigth.php"); ?>
<!--/Sidebar-right-->

<!-- Footer -->
<?php require_once("Views/Template/footer_admin.php"); ?>

<!-- Custom Script Footer -->

<!-- Fin Custom Script Footer -->

<?php require_once("Views/Template/footer_admin_end.php"); ?>
<!-- Fin Footer -->