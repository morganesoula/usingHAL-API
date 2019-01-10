<?php
header('Content-Type: text/html; charset=utf-8');

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
        'OUV' => 'OUvrage (y compris édition critique et traduction)',
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
        'MEM' => 'Mémore / Rapport de stage'
    );
    protected $arrayResultat = array();

    public function rechercher()
    {
        $name = $_POST['nameProfessor'];

        // Si l'un des arguments n'est pas indiqué
        if ($name == null) {
            echo "Merci de renseigner un nom, un prénom ou les deux";
            return false;
        }

        // $name = str_replace(' ', '+', $name);
        $name = rawurlencode($name);

        // var_dump("Le nom est " . $name);

        // Construction de l'URL à appeler, avec les différents paramètres
        $url = "https://Api.archives-ouvertes.fr/search/?q=authFullName_t:('.$name.')&rows=10000&fl=label_s,uri_s,authFullName_s";


        // On vérifie dans un premier temps si la requête nous affiche une page correcte (E.G Error 404 Page not found)
        try {

            echo "<h2>Publications et références disponibles en archives ouvertes: <a href='index.html'><input type='button' value='Retour' /></a></h2>";

            if (isset($_POST['checkboxTrier'])) {
                $url = $this->trier($url);
            }

            if (isset($_POST['checkboxGrouper'])) {
                $this->grouper($url);
            } else {

                $json = file_get_contents($url);

                if ($json !== null) {
                    $data = json_decode($json, true);

                    // On enregistre les tableaux dans lesquels on va se "promener"
                    $tabGroups = $data['response']['docs'];

                    // var_dump($tabGroups);

                    // Pour chaque tableau (dans le tableau "docs"), on sélectionne le Label, l'URI et les auteurs (nom/prenom)
                    foreach ($tabGroups as $key => $element) {

                        // var_dump("Le groupe est " . $element['groupValue']);
                        // var_dump("La clé est " . $tab['key']);
                        /* foreach ($tabTypeDocument as $type => $texte) {
                            if ($element['groupValue'] === $type) {
                                echo "<span style='font-weight: bold; font-size: 20px; border: solid 2px black;'>" . mb_strtoupper($texte) . "</span><br /><br />";
                            }
                        } */


                        //echo "<span style='font-weight: bold'>Type de document: " .$element['groupValue'] . "</span><br /><br />";

                        //var_dump($tabGroups);

                        // $tabDocs = $element['doclist']['docs'];

                        //var_dump($element['doclist']);

                        // foreach ($tabDocs as $id => $value) {

                        echo "<span style='font-weight: bold'>Label: </span>" . $element['label_s'] . "<br />";

                        if (count($element['authFullName_s']) > 1) {
                            echo "<span style='font-weight: bold'>Les auteurs sont: </span>" . implode(", ", $element['authFullName_s']) . "<br />";
                        } else {
                            echo "<span style='font-weight: bold'>L'auteur est: </span>" . implode(", ", $element['authFullName_s']) . "<br />";
                        }

                        echo "<span style='font-weight: bold'>URI: <a href='" . $element['uri_s'] . "' target='_blank'>" . $element['uri_s'] . "</a></span><br /><br /><br />";

                        // }


                        /* if (count($element['authFullName_s']) > 1)
                        {
                            echo "<span style='font-weight: bold'>Les auteurs sont: </span>" . implode(", ", $element['authFullName_s']) . "<br /><br />";
                        } else {
                            echo "<span style='font-weight: bold'>L'auteur est: </span>" . implode(", ", $element['authFullName_s']) . "<br /><br />";
                        } */


                        /* foreach ($tabThreeLines as $key => $value)
                        {
                            echo("Le label est " . $tabThreeLines['label_s']. "<br />");
                            echo("L'uri est " . $tabThreeLines['uri_s'] . "<br /><br />");

                            /* for ($i = 0; $i < count($tabThreeLines); $i++)
                            {
                                echo("Le label est " . $tabThreeLines['label_s']. "<br />");
                                echo("L'uri est " . $tabThreeLines['uri_s'] . "<br /><br />");
                            }

                        } */

                    }

                    if (count($this->arrayResultat) < 1) {
                        echo "Il n'y pas de résultat disponible.";
                    }

                }
            }

        } catch
        (Exception $e) {
            echo "L'URL n'est pas valide " . $e;
        }

        // On n'oublie pas de retourner le tableau récapitulatif
        return $this->arrayResultat;
    }

    public function trier($baseURL)
    {
        $urltri = $baseURL . "&sort=modifiedDate_s%20desc";

        return $urltri;
    }

    public function grouper($baseURL)
    {
        $urlGroup = $baseURL . "&group=true&group.field=docType_s&group.limit=5000";

        $json = file_get_contents($urlGroup);

        if ($json !== null) {
            $data = json_decode($json, true);
        }

        $tabGroups = $data['grouped']['docType_s']['groups'];

        foreach ($tabGroups as $key => $element) {

            foreach ($this->tabTypeDocument as $type => $texte) {
                if ($type === $element['groupValue']) {
                    echo "<span style='font-weight: bold; font-size: 20px; border: solid 2px black;'>" . mb_strtoupper($texte) . "</span><br /><br />";
                }
            }

            $tabDocs = $element['doclist']['docs'];

            foreach ($tabDocs as $id => $value) {

                echo "<span style='font-weight: bold'>Label: </span>" . $value['label_s'] . "<br />";

                if (count($value['authFullName_s']) > 1) {
                    echo "<span style='font-weight: bold'>Les auteurs sont: </span>" . implode(", ", $value['authFullName_s']) . "<br />";
                } else {
                    echo "<span style='font-weight: bold'>L'auteur est: </span>" . implode(", ", $value['authFullName_s']) . "<br />";
                }

                echo "<span style='font-weight: bold'>URI: <a href='" . $value['uri_s'] . "' target='_blank'>" . $value['uri_s'] . "</a></span><br /><br /><br />";

                }
        }

        if (count($this->arrayResultat) < 0) {
            echo "Il n'y pas de résultat disponible.";
        }

        return $this->arrayResultat;
    }

}

$archive = new ArchiveOuverte();

// On appelle la fonction, avec les arguments souhaités

$resultat = $archive->rechercher();

?>