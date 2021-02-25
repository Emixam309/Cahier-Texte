<?php include("session.php");
ob_start(); ?>
<br>
    <h3>Évaluation : <?php echo $_POST['nomEval'] ?></h3>
    <table width="85%">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Note</th>
            <th>Commentaire</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $query = $bdd->query('SELECT * FROM stagiaires WHERE idPromo = ' . $_POST['promotion'] . ' GROUP BY nom, prenom, idStagiaire');
        while ($resultat = $query->fetch_object()) { ?>
            <tr>
                <td><?php echo $resultat->nom ?></td>
                <td><?php echo $resultat->prenom ?></td>
                <td><?php echo $_POST['note-' . $resultat->idStagiaire] ?></td>
                <td><?php echo $_POST['commentaire-' . $resultat->idStagiaire] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php
$html = ob_get_contents();
ob_end_clean();

// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8']);
$title = "Évaluation de " . $_POST['libelle'] . " du " . date('d/m/Y', strtotime($_POST['date']));
$mpdf->SetHTMLHeader('<h3>' . $title . ' par ' . $_SESSION['nom'] . ' ' . $_SESSION['prenom'] . '</h3>');
$mpdf->SetHTMLFooter('
<table style="" width="100%">
    <tr>
        <td width="90%" align="left">Document généré le : {DATE j/m/Y}</td>
        <td width="10%" align="right">{PAGENO}/{nbpg}</td>
    </tr>
</table>');
$mpdf->SetTitle($title);
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
$mpdf->WriteHTML($html);
$mpdf->Image('img/arep.png', 177, 15, 20, 20, 'png', '', true, false);

$mpdf->Output();
//$mpdf->Output($_POST['libelle'] . ' - ' . date('d-m-Y') . '.pdf', 'D');