<?php
/*
FORMULAIRE DE MODIFICATION DES DROITS CHMOD DES FICHIERS ET DOSSIERS
Enregistrez ce fichier dans votre répertoire hébergement web, ouvrez-le 
avec votre navigateur et suivez les instructions.
Un rapport d'erreur est fourni. Supprimez le fichier après utilisation.
*/

// initialisation des variables
$dosPerm = "0";
$ficPerm = "0";
$retval = "0"; // nombre d'erreurs CHMOD

 // Chemin du dossier a traiter
    $chem = preg_replace("/[^_A-Za-z0-9-\.%\/]/i",'', $_POST["chemin"]);    // chemin de fichier absolu (avec nettoyage contre piratage)
    $chem = preg_replace("/\.\.\//",'', $chem);    // on interdit la commande ../
    define('ABSPATH', dirname(__FILE__));
    $chem = ABSPATH.$chem;    // chemin de fichier absolu de votre compte du genre /home/loginftp/www/ ou /home/loginftp/public_html/ etc.

//Droits des dossiers
    $d1 = preg_replace("/[^57]/",'', $_POST["dir1"]);
    $d2 = preg_replace("/[^057]/",'', $_POST["dir2"]);
    $d3 = preg_replace("/[^057]/",'', $_POST["dir3"]);
    $dosPerm = "0".$d1.$d2.$d3;
    $dosPerm = octdec($dosPerm);
//droits des fichiers
    $f1 = preg_replace("/[^46]/i",'', $_POST["fic1"]);
    $f2 = preg_replace("/[^046]/i",'', $_POST["fic2"]);
    $f3 = preg_replace("/[^046]/i",'', $_POST["fic3"]);
    $ficPerm = "0".$f1.$f2.$f3;
    $ficPerm = octdec($ficPerm);

// Formulaire html pour changer les droits
    print "<html><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />";
    print "<body><h3>Changer les droits d'acc&egrave;s CHMOD aux dossiers et fichiers <br />dans votre h&eacute;bergement.</h3>";
    print "<table><tr><td>";
    print "<form method=\"post\">";
    print "<tr><td>Droits des dossiers: </td>";
    print "<td><select name=\"dir1\"><option value=\"5\">5</option><option value=\"7\" selected>7</option></select><select name=\"dir2\"><option value=\"0\">0</option><option value=\"5\" selected>5</option><option value=\"7\">7</option></select><select name=\"dir3\"><option value=\"0\">0</option><option value=\"5\" selected>5</option><option value=\"7\">7</option></select></td></tr>";
    print "<tr><td>Droits des fichiers: </td>";
    print "<td><select name=\"fic1\"><option value=\"4\">4</option><option value=\"6\" selected>6</option></select><select name=\"fic2\"><option value=\"0\">0</option><option value=\"4\" selected>4</option><option value=\"6\">6</option></select><select name=\"fic3\"><option value=\"0\">0</option><option value=\"4\" selected>4</option><option value=\"6\">6</option></select></td></tr>";
    print "<tr><td>R&eacute;pertoire &agrave; contr&ocirc;ler: </td>";
    print "<td>".ABSPATH." <input type=\"text\" name=\"chemin\" maxlength=\"80\" size=\"30\" value=\"/\" ></td></tr>";
    print "<tr><td> </td><td><input type=\"submit\" value=\" Changer les CHMOD des Dossiers et Fichiers \">";
    print "</form>";
    print "</td></tr></table>";

if ( ($dosPerm||$ficPerm) > 0 ){

    function rChmod($chem,$dosPerm,$ficPerm) {
        echo "<p><b>Journal:</b></p>\r\n";

        $d = new RecursiveDirectoryIterator($chem);
        $d ->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
        foreach (new RecursiveIteratorIterator($d, 1) as $path) {
            $chmodret = false;
            $chmodresultat = "";
            if ( $path->isDir() ) {
            $chmodret = chmod( $path, $dosPerm ); }
            else {
            if ( is_file( $path )  ) {
            $chmodret = chmod( $path, $ficPerm ); }
            }
            if ($chmodret) {$chmodresultat = "OK"; }
            else {
                $chmodresultat = "ERREUR";
                ++$retval;
                }
            echo $chmodresultat . " " . $path . "<br />\r\n";
        }
    return $retval;
    }
    $nbfailed = rChmod($chem,$dosPerm,$ficPerm);
    echo "<p><b>";
    if ($nbfailed > 0) {
        echo $nbfailed . " erreur(s) CHMOD. Voyez le journal ci-dessus.";
        }
    else echo "Pas d'erreur apparente. Vérifiez par vous-même.</b> Supprimez le fichier après utilisation.</p>\r\n";
}
    print "</body></html>";
?>