<?php
include("session.php");

// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);

$mpdf->SetHTMLHeader('<h3>Évènements de ' . $_POST['libelle'].'</h3>');
$mpdf->setFooter('{PAGENO}');
$mpdf->SetTitle('Évènements de ' . $_POST['libelle']);
$mpdf->SetAuthor($_SESSION['nom'] . ' ' . $_SESSION['prenom']);

$mpdf->WriteHTML("
<style type='text/css'>
h2, table {
  text-align: center;
}
table, th, td {
  text-align: center;
  border: 1px solid black;
  border-collapse: collapse;
}    
</style>");
// Buffer the following html with PHP so we can store it to a variable later

$mpdf->WriteHTML(str_replace("✔", "X", $_SESSION['html']));
unset($_SESSION["html"]);
$mpdf->Output();
//$mpdf->Output($_POST['libelle'] . ' - ' . date('d-m-Y') . '.pdf', 'D');