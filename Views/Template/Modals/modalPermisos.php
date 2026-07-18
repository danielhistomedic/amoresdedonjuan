    <!-- Modal Formulario Permisos -->
    <div class="modal fade effect-scale modalPermisos" id="modalPermisos" tabindex="-1" aria-labelledby="modalPermisos1" aria-modal="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">

                <?php //dep($data);
                ?>

                <div class="modal-header p-0 ">
                    <!-- <button aria-label="Close" class="btn-close" style="position: absolute; left: calc(100% - 72px); font-size: 35px; top: 14px; cursor: pointer;" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button> -->

                    <div class="form-group col-12 m-0">

                        <div class="card-header border-bottom-title-form pd-t-0-force pd-b-8-force titulo_detalle">

                            <div class="d-flex justify-content-start align-items-center">
                                <i class="fa-regular fa-file-lock icon-size float-start text-secondary text-secondary-shadow me-2"></i>
                                <span class="tx-uppercase">Permisos para el Rol: <span class="ms-1 tx-light"><?= $data['rol']; ?></span> </span>
                            </div>
                            <!-- <div class="card-options">
                                <a href="#" class="card-options-fullscreen" data-bs-toggle="card-fullscreen">
                                    <i class="fa-regular fa-arrow-up-right-and-arrow-down-left-from-center"></i>
                                </a>
                            </div> -->
                            <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        </div>



                    </div>



                    <!-- <h5 class="modal-title" id="gridModalLabel">
                        <span class="tx-uppercase">
                            <i class="fa-solid fa-file-lock font-warning font-warning-shadow"></i> Permisos para el Rol: <span class="ms-1 tx-light"><?= $data['rol']; ?></span>
                        </span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" role="button"></button> -->

                </div>

                <div class="modal-body modal-body-permisos">


                    <form action="" id="formPermisos" name="formPermisos">

                        <div class="row">

                            <div class="col-12">

                                <div class="">

                                    <input type="hidden" id="rol_id" name="rol_id" value="<?= $data['rol_id']; ?>" required="">

                                    <div class="table-responsive">
                                        <!-- table border text-nowrap text-md-nowrap table-striped mg-b-0 -->
                                        <table class="table border text-nowrap text-md-nowrap table-hover table-striped mg-b-0">
                                            <thead class="gradient-custom-content">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Módulo</th>
                                                    <th class="modal-ancho-permisos">Ver</th>
                                                    <th class="modal-ancho-permisos">Crear</th>
                                                    <th class="modal-ancho-permisos">Actualizar</th>
                                                    <th class="modal-ancho-permisos">Eliminar</th>
                                                    <th class="modal-ancho-permisos" title="Exportar a excel">Exportar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                $modulos = $data['modulos'];
                                                for ($i = 0; $i < count($modulos); $i++) {

                                                    $permisos = $modulos[$i]['permisos'];
                                                    $rCheck = $permisos['r'] == 1 ? " checked " : "";
                                                    $cCheck = $permisos['c'] == 1 ? " checked " : "";
                                                    $uCheck = $permisos['u'] == 1 ? " checked " : "";
                                                    $dCheck = $permisos['d'] == 1 ? " checked " : "";
                                                    $pExcelCheck = $permisos['p_excel'] == 1 ? " checked " : "";
                                                    $modulo_id = $modulos[$i]['id'];
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <?= $no; ?>
                                                                <input type="hidden" name="modulos[<?= $i; ?>][modulo_id]" value="<?= $modulo_id ?>" required>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="d-flex justify-content-start align-items-center">
                                                                <?= $modulos[$i]['name']; ?>
                                                            </div>
                                                        </td>

                                                        <td class="modal-ancho-permisos">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <label class="custom-switch pos-relative" style="cursor: pointer;">
                                                                    <input type="checkbox" name="modulos[<?= $i; ?>][r]" <?= $rCheck ?> class="custom-switch-input">
                                                                    <!-- custom-switch-input -->
                                                                    <span class="custom-switch-indicator"></span>
                                                                </label>
                                                            </div>
                                                        </td>

                                                        <td class="modal-ancho-permisos">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <label class="custom-switch pos-relative" style="cursor: pointer;">
                                                                    <input type="checkbox" name="modulos[<?= $i; ?>][c]" <?= $cCheck ?> class="custom-switch-input">
                                                                    <span class="custom-switch-indicator"></span>
                                                                </label>
                                                            </div>
                                                        </td>

                                                        <td class="modal-ancho-permisos">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <label class="custom-switch pos-relative" style="cursor: pointer;">
                                                                    <input type="checkbox" name="modulos[<?= $i; ?>][u]" <?= $uCheck ?> class="custom-switch-input">
                                                                    <span class="custom-switch-indicator"></span>
                                                                </label>
                                                            </div>
                                                        </td>

                                                        <td class="modal-ancho-permisos">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <label class="custom-switch pos-relative" style="cursor: pointer;">
                                                                    <input type="checkbox" name="modulos[<?= $i; ?>][d]" <?= $dCheck ?> class="custom-switch-input">
                                                                    <span class="custom-switch-indicator"></span>
                                                                </label>
                                                            </div>
                                                        </td>

                                                        <td class="modal-ancho-permisos">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <label class="custom-switch pos-relative" style="cursor: pointer;">
                                                                    <input type="checkbox" name="modulos[<?= $i; ?>][p_excel]" <?= $pExcelCheck ?> class="custom-switch-input">
                                                                    <span class="custom-switch-indicator"></span>
                                                                </label>
                                                            </div>
                                                        </td>


                                                    </tr>
                                                <?php
                                                    $no++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>



                                </div>

                            </div>

                            <div class="col-12 mt-2">
                                <div class="d-flex justify-content-start align-items-center d-flex-pacientes-inicio">

                                    <?php if ($data[0]['c'] || $data[0]['u']) { ?>
                                        <button type="submit" class="btn btn-pill btn-primary-gradient btn-guardar-form d-flex justify-content-center align-items-center" id="btnActionFormPermisos">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <i class="fa-regular fa-floppy-disk-pen fa-fw fa-lg me-1"></i>
                                                <span class="">Guardar</span>
                                                <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                            </div>
                                        </button>
                                    <?php } ?>

                                    <button type="button" class="btn btn-pill btn-danger-gradient btn-cancelar-form d-flex justify-content-center align-items-center view_config_datgen_consultorio" data-bs-dismiss="modal">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <i class="fa-regular fa-arrow-up-from-square fa-rotate-270 fa-fw fa-lg me-1"></i>
                                            <span class="">Cerrar</span>
                                            <i class="fa-thin fa-loader fa-spin fa-fw fa-lg ms-2" style="display:none;"></i>
                                        </div>
                                    </button>

                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </form>
                    <!-- Fin Formulario -->

                </div>
                <!--end modal-body-->


                <!--end modal-footer-->

            </div>
            <!--end modal-content-->
        </div>
    </div>
    <!-- Fin Modal Formulario Permisos -->