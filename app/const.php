<?php

const BASE_URL_ONE = "https://Api.archives-ouvertes.fr/search/?q=authFullName_t:(";
const BASE_URL_TWO = ")&rows=10000&fl=label_s,uri_s,authFullName_s,modifiedDate_s";
const GROUP = "&group=true&group.field=docType_s&group.limit=5000";
const TRI = "&sort=modifiedDate_s%20desc";
const MESSAGE_ADD_PUBLICATION_ERROR = "Problème lors de l'ajout d'une publication.";
const MESSAGE_NAME_PROFESSOR_ERROR = "Merci de renseigner un nom, un prénom ou les deux";
const MESSAGE_NO_RESULTS_AVAILABLE_ERROR = "Il n'y a pas de résultat de disponible.";
const MESSAGE_NO_DATA_AVAILABLE_ERROR = "Il n'y a pas de données.";
const MESSAGE_PROBLEM_WITH_URL_ERROR = "Il y a un problème avec l'URL";

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