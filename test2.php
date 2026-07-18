<?php


$folio = 'A-000013';

$rest = substr($folio, 2);

echo $rest;

echo '<br><br>';


$rest = intval($rest);

echo '<br><br>';

$rest += 1;

// A-000013
//   000013
echo '<br><br>';
echo '<br><br>';
echo '<br>====<br>';

$folio = 'A-000013';
$rest = substr($folio, 2);
$rest = intval($rest);
$rest += 1;

$folio = 'A-' . str_pad($rest, 6, "0", STR_PAD_LEFT);
echo $folio;
