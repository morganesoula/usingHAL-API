<?php


class Publication
{
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

    private $label;
    private $author = array();
    private $uri;
    private $documentType;
    private $modifiedDate;


    public function __construct($label, array $author, $uri, $modifiedDate, $documentType = null)
    {
        $this->setLabel($label);
        $this->setAuthor($author);
        $this->setUri($uri);
        $this->setModifiedDate($modifiedDate);
        $this->setDocumentType($documentType);
    }


    /* ********************************* GETTER AND SETTER ********************************* */

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = (string) $label;
    }

    /**
     * @return array
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param array $author
     */
    public function setAuthor(array $author)
    {
        $this->author = (array) $author;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = (string) $uri;
    }

    /**
     * @return null || string
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * @param null $documentType
     */
    public function setDocumentType($documentType)
    {
        // Si la clé existe dans le tableau-mapping
        if (array_key_exists($documentType, static::DOCUMENT_TYPE_MAP)) {
            $this->documentType = static::DOCUMENT_TYPE_MAP[$documentType];
        } else {
            // Sinon on l'enregistre dans le tableau en tant que nouvelle clé (la valeur sera alors nulle)
            $this->documentType = $documentType;
        }
    }

    /**
     * @return mixed
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * @param mixed $modifiedDate
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;
    }

}