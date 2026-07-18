<!-- Header Admin -->
<?php require_once("Views/Template/header_admin.php"); ?>

<!-- Custom Css/Script -->
<link href="<?= media(); ?>/css/checkout.css?v=<?= version(); ?>" rel="stylesheet">
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

        <!-- Display a payment form -->
        <form id="payment-form">
            <div id="payment-element">
                <!--Stripe.js injects the Payment Element-->
            </div>
            <button id="submit">
                <div class="spinner hidden" id="spinner"></div>
                <span id="button-text">Pay now</span>
            </button>
            <div id="payment-message" class="hidden"></div>
        </form>

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
<script src="https://js.stripe.com/v3/"></script>
<script src="<?= media(); ?>/js/functions/checkout.js" defer></script>
<!-- Fin Custom Script Footer -->

<?php require_once("Views/Template/footer_admin_end.php"); ?>
<!-- Fin Footer -->