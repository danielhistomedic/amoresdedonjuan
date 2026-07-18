<!-- Custom javascripts-->
<script src="<?= media(); ?>/js/functions/function_animate.js?v=<?= version(); ?>"></script>
<script src="<?= media(); ?>/js/functions/functions_admin.js?v=<?= version(); ?>"></script>
<script src="<?= media(); ?>/js/functions/alertas.js?v=<?= version(); ?>"></script>

<!-- Custom javascripts from View-->
<?php if ($data['page_functions_js'] != "") { ?>
    <script src="<?= media(); ?>/js/functions/<?= $data['page_functions_js']; ?>?v=<?= version(); ?>"></script>
<?php } ?>

</body>

</html>