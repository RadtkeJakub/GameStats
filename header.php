<!DOCTYPE html>
<html lang="pl">
<head>
    <title>TODO</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Staatliches" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="http://ddragon.leagueoflegends.com/cdn/8.24.1/css/view.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="lolstyle.css">
    <?php
        define("TOP","TOP");
        define("JUNGLE","JUNGLE");
        define("MID","MIDDLE");
        define("BOT","BOTTOM");
        define("SUPP","SUPPORT");
    ?>
</head>

<body class="bg-secondary">
    
<div class="container bg-dark">
    <header>
        <!-- Navigation bar -->
        <nav class="navbar sticky-top navbar-dark bg-dark navbar-expand-lg " style="background-color:#171a1c">
            <!-- Logo -->
            <a class="navbar-brand d-none d-sm-block" href="/inzynierkav2">
                <img src="logo.png" width="180" class="d-inline-block mr-1 align-bottom " alt="">
            </a>
            <a class="navbar-brand d-block d-sm-none" href="#">
                Home
            </a>

            <!-- Menu button, shows on medium and lower -->
            <button class="navbar-toggler float-right" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="mainmenu">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="champions.php">Champions</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Pros</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Info</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </nav>
    </header>
    