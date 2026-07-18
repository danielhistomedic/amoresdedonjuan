<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="row align-items-center flex-row-reverse">
            <div class="col-md-12 col-sm-12 text-center">
                ©2021-2022 <a href="#">Fracc. Amores de Don Juan</a>. Todos los derechos reservados.
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER CLOSED -->

</div>
<!-- END PAGE -->

<!-- BACK-TO-TOP -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>


<!-- ########## START: MODAL ALERTAS ########## -->
<?php require_once("Views/Template/Modals/modalAlertas.php"); ?>
<!-- ########## END: MODAL ALERTAS ########## -->

<!-- ########## START: POPUS ########## -->
<?php require_once("Views/Template/Popus/popus.php"); ?>
<!-- ########## END: POPUS ########## -->


<script>
    const base_url = "<?= base_url(); ?>";
    const assets = "<?= media(); ?>";
    const vigencia_qr = "<?= VIGENCIA_QR; ?>";
    let menu = "<?= isset($data['menu']) ? $data['menu'] : ''; ?>";
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Invocamos cada 5 segundos ;)
        const milisegundos = 10 * 1000;
        setInterval(function() {
            // No esperamos la respuesta de la petición porque no nos importa
            <?php @session_start(); ?>
        }, milisegundos);
    });
</script>

<!-- JQUERY JS -->
<script src="<?= media(); ?>/js/jquery.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="<?= media(); ?>/plugins/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>

<!-- MDB -->
<script src="<?= media(); ?>/plugins/MDB5-3.9.0/js/mdb.min.js"></script>

<!-- JQUERY UI -->
<script src="<?= media(); ?>/plugins/jquery-ui-1.13.0/jquery-ui.min.js"></script>

<!-- Select2 -->
<script src="<?= media(); ?>/plugins/select2/js/select2.min.js"></script>
<script src="<?= media(); ?>/plugins/select2/js/i18n/es.js"></script>

<!-- DataTables -->
<script src="<?= media(); ?>/plugins/DataTables/datatables.min.js"></script>
<script src="<?= media(); ?>/plugins/DataTables/Responsive-2.2.9/js/dataTables.responsive.js"></script>

<!-- Datepicker-Master -->
<script src="<?= media(); ?>/plugins/datepicker-master/datepicker.js"></script>
<script src="<?= media(); ?>/plugins/datepicker-master/i18n/datepicker.es-ES.js"></script>

<!-- INPUT MASK JS-->
<script src="<?= media(); ?>/plugins/input-mask/jquery.mask.min.js"></script>

<!--HORIZONTAL JS-->
<script src="<?= media(); ?>/plugins/horizontal-menu/horizontal-menu.js"></script>

<!-- STICKY JS -->
<script src="<?= media(); ?>/js/stiky.js"></script>

<!-- SIDEBAR JS -->
<script src="<?= media(); ?>/plugins/sidebar/sidebar.js"></script>

<!-- Perfect SCROLLBAR JS-->
<!-- <script src="<?= media(); ?>/plugins/p-scroll/perfect-scrollbar.js"></script> -->
<!-- <script src="<?= media(); ?>/plugins/p-scroll/pscroll-1.js"></script> -->

<!-- INDEX JS -->
<!-- <script src="<?= media(); ?>/js/index.js"></script> -->

<!-- CUSTOM JS -->
<script src="<?= media(); ?>/js/custom.js?v=<?= version(); ?>"></script>