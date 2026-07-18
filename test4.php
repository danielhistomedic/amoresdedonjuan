<?php

$dn = array(
    'amores' => 'amores'
);

$privkey = openssl_pkey_new();
$csr = openssl_csr_new($dn, $privkey);
$sscert = openssl_csr_sign($csr, null, $privkey, 365);
openssl_csr_export($csr, $csrout) and var_dump($csrout);
openssl_x509_export($sscert, $certout);



var_dump($certout);
