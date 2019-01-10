<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Page&nbsp;de&nbsp;r&eacute;sultats</title>

    <!-- Bootstrap core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet"
          type="text/css">

    <!-- Custom styles for this template -->
    <link href="../../css/landing-page.min.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
</head>

<body>

<div id="bloc_title" class='bg-dark'>
    <h3 class='text-light bg-dark' id="main_title">Publications&nbsp;et&nbsp;r&eacute;f&eacute;rences&nbsp;disponibles&nbsp;en&nbsp;archives&nbsp;ouvertes:
        <a href='../view/home.php'>
            <button type='button' class='btn btn-dark float-right'>Retour</button>
        </a>
    </h3>
</div>

<?php


/**
 * @var Publication $element
 */
foreach ($tabResults as $key => $element) :

    ?>
    <div id="publication">
        <div class="container-fluid">
            <?php
            echo "<span id='publication_title'>Label:&nbsp;</span>" . $element->getLabel() . "<br />";

            if (count($element->getAuthor()) > 1) {
                echo "<span id='publication_title'>Les&nbsp;auteurs&nbsp;sont:&nbsp;</span>" . implode("&#130;&nbsp;", $element->getAuthor()) . "<br />";
            } else {
                echo "<span id='publication_title'>L&#146;auteur&nbsp;est:&nbsp;</span>" . implode(", ", $element->getAuthor()) . "<br />";
            }

            echo "<span id='publication_title'>URI:&nbsp;<a href='" . $element->getUri() . "' target='_blank'>" . $element->getUri() . "</a></span><br />";

            echo "<span id='publication_title'>Date&nbsp;de&nbsp;derni&egrave;re&nbsp;modification:&nbsp;</span>" . $element->getModifiedDate() . "<br />"; ?>
        </div>
        <br/>
    </div>

    <?php
endforeach;

?>


</body>
</html>