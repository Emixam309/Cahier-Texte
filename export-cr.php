<?php
include("session.php");

// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);

$mpdf->SetHTMLHeader('<h3>Comptes Rendus de  ' . $_POST['libelle'] . '</h3>');
$mpdf->setFooter('{PAGENO}');
$mpdf->SetTitle('Comptes Rendus de ' . $_POST['libelle']);
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
$html = $_SESSION['html'];
// Buffer the following html with PHP so we can store it to a variable later
$html = strip_tags_content($_SESSION['html'], '<a><form>', TRUE); //suprime le contenu de la colonne actions.
$html = str_replace('<td id="a">', null, $html);
$html = str_replace(' </td>', null, $html); // 4 tabulation pour faire comme l'html.
$html = str_replace('<th scope="col">Actions</th>', '', $html); //Supprime le head de la colon ne actions.
$html = str_replace("✔", "X", $html);
$mpdf->WriteHTML($html);
unset($_SESSION["html"]);
$mpdf->Output();
//$mpdf->Output($_POST['libelle'] . ' - ' . date('d-m-Y') . '.pdf', 'D');


function strip_tags_content($text, $tags = '', $invert = FALSE)
{
    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
    $tags = array_unique($tags[1]);
    if (is_array($tags) and count($tags) > 0) {
        if ($invert == FALSE) {
            return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
        } else {
            return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
        }
    } elseif ($invert == FALSE) {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }
    return $text;
}