<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Résultats</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet"
          type="text/css">

    <!-- Custom styles for this template -->
    <link href="css/landing-page.min.css" rel="stylesheet">

</head>

<body>

<?php
header('Content-Type: text/html; charset=utf-8');

include 'BO/Publication.php';

class ArchiveOuverte
{

    protected $tabTypeDocument = array(
        'COMM' => 'Communication dans un congrès',
        'ART' => 'Article dans une revue',
        'POSTER' => 'Poster',
        'UNDEFINED' => 'Documents non publiés',
        'REPORT' => 'Rapport',
        'COUV' => 'Chapitre d\'ouvrage',
        'THESE' => 'Thèse',
        'OUV' => 'Ouvrage (y compris édition critique et traduction)',
        'DOUV' => 'Direction d\'ouvrage, Proceedings, Dossier',
        'OTHER' => 'Autre publication',
        'IMG' => 'Image',
        'SYNTHESE' => 'Notes de synthèse',
        'LECTURE' => 'Cours',
        'PRESCONF' => 'Document associé à des manifestations scientifiques',
        'SOFTWARE' => 'Logiciel',
        'OTHERREPORT' => 'Rapport technique',
        'PATENT' => 'Brevet',
        'SON' => 'Son',
        'MAP' => 'Carte',
        'NOTE' => 'Notes de lecture',
        'VIDEO' => 'Vidéo',
        'MEM' => 'Mémoire / Rapport de stage'
    );
    protected $tabPublications = array();

    const GROUP = "&group=true&group.field=docType_s&group.limit=5000";
    const TRI = "&sort=modifiedDate_s%20desc";

    public function rechercher()
    {
        $name = $_POST['nameProfessor'];

        // Si l'un des arguments n'est pas indiqué
        if ($name == null) {
            echo "Merci de renseigner un nom, un pr&eacute;nom ou les deux";
            return false;
        }

        $name = rawurlencode($name);

        // Construction de l'URL à appeler, avec les différents paramètres
        $url = "https://Api.archives-ouvertes.fr/search/?q=authFullName_t:('.$name.')&rows=10000&fl=label_s,uri_s,authFullName_s";

        // On vérifie dans un premier temps si la requête nous affiche une page correcte (E.G Error 404 Page not found)
        if (isset($_POST['checkboxTrier'])) {
            $url = $this->trier($url);
        }

        if (isset($_POST['checkboxGrouper'])) {
            $this->grouper($url);
        } else {

            try {

                echo "<div style='height: 50px' class='bg-dark'><h3 class='text-light bg-dark' style='margin-left: 10px; padding-top: 5px'>Publications et références disponibles en archives ouvertes: <a href='withoutMVC/index.html'><button type='button' class='btn btn-dark float-right'>Retour</button></a></h3></div>";

                $json = file_get_contents($url);

                if ($json !== null) {
                    $data = json_decode($json, true);

                    // On enregistre les tableaux dans lesquels on va se "promener"
                    $tabResults = $data['response']['docs'];

                    if (count($tabResults) > 0) {

                        // Pour chaque tableau (dans le tableau "docs"), on sélectionne le Label, l'URI et les auteurs (nom/prenom)
                        foreach ($tabResults as $key => $element) {

                            $publication = new Publication($element['label_s'], $element['authFullName_s'], $element['uri_s']);
                            $this->tabPublications[] = $publication;
                        }

                    } else {
                        ?>
                        <div class="text-center mt-5">
                            <?php echo "Il n'y a pas de résultat de disponible."; ?>
                        </div>
                        <?php
                    }
                }
            } catch (Exception $e) {
                echo "L'URL n'est pas valide " . $e;
            }
        }
    }

    public function ajouterParam($baseURL, $maChaine){
        return  $urlTri = $baseURL . $maChaine;
    }

    public function trier($baseURL)
    {
        $urlTri = $this->ajouterParam($baseURL, static::TRI);

        return $urlTri;
    }

    public function grouper($baseURL)
    {
        $urlGroup = $this->ajouterParam($baseURL, static::GROUP);

        try {
            echo "<div style='height: 50px' class='bg-dark'><h3 class='text-light bg-dark' style='margin-left: 10px; padding-top: 5px'>Publications et références disponibles en archives ouvertes: <a href='withoutMVC/index.html'><button type='button' class='btn btn-dark float-right'>Retour</button></a></h3></div>";

            $json = file_get_contents($urlGroup);

            if ($json !== null) {
                $data = json_decode($json, true);

                $tabGroups = $data['grouped']['docType_s']['groups'];

                if (count($tabGroups) > 0) {

                    foreach ($tabGroups as $key => $element) {

                        ?>
                        <div class="text-center" ; style="margin-top: 20px">
                            <?php
                            foreach ($this->tabTypeDocument as $type => $texte) {
                                if ($type === $element['groupValue']) {
                                    echo "<span style='font-weight: bold; font-size: 20px;'>" . mb_strtoupper($texte) . "</span><br /><br />";
                                }
                            }
                            ?>
                        </div>
                        <?php

                        $tabDocs = $element['doclist']['docs'];

                        foreach ($tabDocs as $id => $value) {
                            ?>
                            <div style="margin-bottom: 15px; background-color: #D3D3D3; border-radius: 20px">
                                <div class="container-fluid">
                                    <?php echo "<span style='font-weight: bold'>Label: </span>" . $value['label_s'] . "<br />";

                                    if (count($value['authFullName_s']) > 1) {
                                        echo "<span style='font-weight: bold'>Les auteurs sont: </span>" . implode(", ", $value['authFullName_s']) . "<br />";
                                    } else {
                                        echo "<span style='font-weight: bold'>L'auteur est: </span>" . implode(", ", $value['authFullName_s']) . "<br />";
                                    }

                                    echo "<span style='font-weight: bold'>URI: <a href='" . $value['uri_s'] . "' target='_blank'>" . $value['uri_s'] . "</a></span><br /><br /><br />"; ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } else {
                    ?>
                    <div class="text-center mt-5">
                        <?php echo "Il n'y a pas de résultat de disponible."; ?>
                    </div>
                    <?php
                }
            }

        } catch (Exception $e) {
            echo "L'URL n'est pas valide " . $e;
        }

    }
}

/* ------------------------------------------- PARTIE TEST ---------------------------------- */

$archive = new ArchiveOuverte();

// On appelle la fonction, avec les arguments souhaités

$resultat = $archive->rechercher();

?>

</body>
</html>
