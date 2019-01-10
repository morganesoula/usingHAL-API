<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Page de recherche</title>

    <!-- Bootstrap core CSS -->
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../vendor/simple-line-icons/css/simple-line-icons.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- Custom styles for this template -->
    <link href="../../css/landing-page.min.css" rel="stylesheet">

</head>

<body>

<header class="masthead text-white text-center">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-9 mx-auto">
                <h1 class="mb-5">Formulaire de recherche des publications d'un professeur</h1>
            </div>
            <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">

                <form action="../controller/CatalogueController.php" method="post">
                    <div class="form-row">
                        <div class="col-6 col-md-6 mb-2 mb-md-0">
                            <input type="text" name="nameProfessor" class="form-control form-control-lg" placeholder="Entrez un nom, un prénom...">
                        </div>
                        <div class="col-2 col-md-2 mb-1 mb-md-0">
                            <input type="checkbox" id="checkboxTrier" name="checkboxTrier" class="form-control form-control-lg">
                            <label for="checkboxTrier">Trier par date</label>
                        </div>
                        <div class="col-3 col-md-4 mb-1 mb-md-0">
                            <input type="checkbox" id="checkboxGrouper" name="checkboxGrouper" class="form-control form-control-lg">
                            <label for="checkboxGrouper">Grouper par type de document</label>
                        </div>
                        <div class="col-12 col-md-12">
                            <button type="submit" class="btn btn-block btn-lg btn-primary">Valider</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</header>

</body>

</html>