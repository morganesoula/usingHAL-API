<?php

include "Publication.php";

class Catalogue
{


    /* ************************************** TO DO ************************************** */

    /**
     * Créer un fichier de constantes
     *
     * Essayer d'adapter la vue au besoin recherché (ViewBean)
     *
     *
     * Envoyer le code sur GitHub
     */



    public $publications = array();


    /* ********************************* GETTER AND SETTER ********************************* */

    /**
     * @return array
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * @param array $publications
     */
    public function setPublications($publications)
    {
        $this->publications = $publications;
    }



    /* ************************************** VARIABLES POUR FUNCTION ************************************** */

    const BASE_URL_ONE = "https://Api.archives-ouvertes.fr/search/?q=authFullName_t:(";
    const BASE_URL_TWO = ")&rows=10000&fl=label_s,uri_s,authFullName_s,modifiedDate_s";
    const GROUP = "&group=true&group.field=docType_s&group.limit=5000";
    const TRI = "&sort=modifiedDate_s%20desc";
    const MESSAGE_ADD_PUBLICATION_ERROR = "Problème lors de l'ajout d'une publication.";
    const MESSAGE_NAME_PROFESSOR_ERROR = "Merci de renseigner un nom, un prénom ou les deux";
    const MESSAGE_NO_RESULTS_AVAILABLE_ERROR = "Il n'y a pas de résultat de disponible.";
    const MESSAGE_NO_DATA_AVAILABLE_ERROR = "Il n'y a pas de données.";
    const MESSAGE_PROBLEM_WITH_URL_ERROR = "Il y a un problème avec l'URL";
    private $isSorted = false;
    private $isGrouped = false;
    public $tabModified = array();

    /**
     * Tableau de mapping de type de document (Acronyme -> libellé)
     */
    const DOCUMENT_TYPE_MAP = array(
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





    /* ************************************** FUNCTIONS ************************************** */


    /**
     * Fonction pour ajouter une publication dans un tableau de publications
     *
     * @param array $tab
     * @param Publication $item
     * @return array
     */
    public function addPublication(Publication $item)
    {
        if ($item !== null)
        {
            // Si existence d'un type => c'est la méthode getGroupedCatalogue() qui sera appelé
            if ($item->getDocumentType() !== null)
            {
                // Type déjà définis dans la fonction getGroupedCatalaogue()
                $this->publications[$item->getDocumentType()][] = $item;
            } else {
                // Sinon, ça sera la méthode getCatalogue()
                $this->publications[] = $item;
            }
        } else {
           die(static::MESSAGE_ADD_PUBLICATION_ERROR);
        }

    }

    /**
     * Fonction pour récupérer le nom du professeur
     *
     * @return mixed
     */
    public function getNameOfProfessor()
    {
        if ($_POST['nameProfessor'] == null) {
            die(static::MESSAGE_NAME_PROFESSOR_ERROR);
        } else {
            $name = $_POST['nameProfessor'];
            $name = rawurlencode($name);
        }

        return $name;
    }

    /**
     * Fonction qui permet de choisir l'URL à passer
     *
     * @return string
     */
    public function getBaseURL()
    {
        $url = static::BASE_URL_ONE . $this->getNameOfProfessor() . static::BASE_URL_TWO;

        if ($this->isGrouped)
        {
            $url = $url . static::GROUP;
        }

        if ($this->isSorted)
        {
            $url = $url . static::TRI;
        }

        return $url;
    }

    /**
     * renvoie un Boolean qui active la fonction pour trier
     */
    public function setSorted()
    {
        $this->isSorted = true;
    }

    /**
     * renvoie un Boolean qui active la fonction pour grouper
     */
    public function setGrouped()
    {
        $this->isGrouped = true;
    }

    /**
     * Fonction qui retourne un tableau d'objets
     * @return array
     */
    public function getCatalogue()
    {

        $json = file_get_contents($this->getBaseURL());

        if ($json !== null) {
            $data = json_decode($json, true);

            if (count($data) > 0) {

                // On enregistre les tableaux dans lesquels on va se "promener"
                $tabResults = $data['response']['docs'];

                if (count($tabResults) > 0) {

                    // Pour chaque tableau (dans le tableau "docs"), on enregistre le Label, l'URI et les auteurs (nom/prenom)
                    foreach ($tabResults as $key => $element) {
                        $publication = new Publication($element['label_s'], $element['authFullName_s'], $element['uri_s'], $element['modifiedDate_s']);

                        $this->addPublication($publication);
                    }

                } else {
                    echo static::MESSAGE_NO_RESULTS_AVAILABLE_ERROR;
                }
            } else {
                die(static::MESSAGE_NO_DATA_AVAILABLE_ERROR);
            }
        } else {
            die(static::MESSAGE_PROBLEM_WITH_URL_ERROR);
        }

        return $this->publications;

    }

    /**
     * Fonction qui retourne un tableau d'objets groupés par type de document
     * @return array
     */
    public function getGroupedCatalogue()
    {
        $this->setGrouped();
        $type = null;

        $json = file_get_contents($this->getBaseURL());

        if ($json !== null) {
            $data = json_decode($json, true);

            if (count($data) > 0) {

                $tabGroups = $data['grouped']['docType_s']['groups'];

                if (count($tabGroups) > 0) {

                    foreach ($tabGroups as $key => $element) {

                        if (key_exists($element['groupValue'], static::DOCUMENT_TYPE_MAP)) {
                            $type = static::DOCUMENT_TYPE_MAP[$element['groupValue']];
                        }

                        $tabDocs = $element['doclist']['docs'];

                        foreach ($tabDocs as $id => $value) {
                            $publication = new Publication($value['label_s'], $value['authFullName_s'], $value['uri_s'], $value['modifiedDate_s'], $type);

                            $this->addPublication($publication);
                        }
                    }

                } else {
                    die(static::MESSAGE_NO_RESULTS_AVAILABLE_ERROR);
                }
            } else {
                die(static::MESSAGE_NO_DATA_AVAILABLE_ERROR);
            }
        } else {
            die(static::MESSAGE_PROBLEM_WITH_URL_ERROR);
        }

        // Tableau d'objet(s)
        return $this->publications;

    }

}



