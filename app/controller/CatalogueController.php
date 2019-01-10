<?php

include("../models/Catalogue.php");

$catalogue = new Catalogue();

if (isset($_POST['checkboxTrier'])) {
    $catalogue->setSorted();
}

if (isset($_POST['checkboxGrouper'])) {
    $tabResults = $catalogue->getGroupedCatalogue();

    require('../view/groupedResultats.php');

} else {
    $tabResults = $catalogue->getCatalogue();

    require('../view/resultats.php');
}


