<?php

// put PHP code here

$apiDate = "13.10.2025"; // placeholder date

$unzuverlaessigsteZuglinieWinner = "S7";
$unzuverlaessigsteZuglinieOverviev = "42";
$laengsteVerspaetungWinner = "IC";
$laengsteVerspaetungOverviev = "1H 30MIN";
$unzuverlaessigstesVerkehrsmittelWinner = "ICE";
$unzuverlaessigstesVerkehrsmittelOverviev = "201";
$ausfalllastigsteHaltestelleWinner = "Bahnhof Olten";
$ausfalllastigsteHaltestelleOverviev = "20";
$schlimmsteStundeWinner = "18:00";
$schlimmsteStundeOverviev = "125";
$verspaetetsteHaltestelleWinner = "Bern, Wander";
$verspaetetsteHaltestelleOverviev = "5";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SBB Railbait Awards</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>SBB </br> Railbait Awards</h1>
    </header>

    <section>
        <h2 class="award-day-txt">Award Gewinner vom <?php echo $apiDate; ?></h2>
        <div class="award-container">
            <a class="award-link" href="#">
                <div class="award-item" >
                    <h3 class="award-category">Unzuverlässigste Zuglinie</h3>
                    <p class="award-winner-short"><?php echo $unzuverlaessigsteZuglinieWinner; ?></p>
                    <p class="award-subtext"><?php echo $unzuverlaessigsteZuglinieOverviev; ?> Verspätungen</p>
                    <p class="find-out-more">Mehr erfahren</p>
                </div>
            </a>
            <a class="award-link" href="#">
                <div class="award-item" >
                    <h3 class="award-category">Längste Verspätung</h3>
                    <p class="award-winner-short"><?php echo $laengsteVerspaetungWinner; ?></p>
                    <p class="award-subtext"><?php echo $laengsteVerspaetungOverviev; ?></p>
                    <p class="find-out-more">Mehr erfahren</p>
                </div>
            </a>
            <a class="award-link" href="#">
                <div class="award-item" >
                    <h3 class="award-category">Unzuverlässigstes Verkehrsmittel</h3>
                    <p class="award-winner-short"><?php echo $unzuverlaessigstesVerkehrsmittelWinner; ?></p>
                    <p class="award-subtext"><?php echo $unzuverlaessigstesVerkehrsmittelOverviev; ?> Verspätungen</p>
                    <p class="find-out-more">Mehr erfahren</p>
                </div>
            </a>
            <a class="award-link" href="#">
                <div class="award-item" >
                    <h3 class="award-category">Ausfalllastigste Haltestelle</h3>
                    <p class="award-winner-long"><?php echo $ausfalllastigsteHaltestelleWinner; ?></p>
                    <p class="award-subtext"><?php echo $ausfalllastigsteHaltestelleOverviev; ?> Ausfälle</p>
                    <p class="find-out-more">Mehr erfahren</p>
                </div>
            </a>
            <a class="award-link" href="#">
                <div class="award-item" >
                    <h3 class="award-category">Schlimmste<br>Stunde</h3>
                    <p class="award-winner-short"><?php echo $schlimmsteStundeWinner; ?></p>
                    <p class="award-subtext"><?php echo $schlimmsteStundeOverviev; ?> Verspätungen</p>
                    <p class="find-out-more">Mehr erfahren</p>
                </div>
            </a>
            <a class="award-link" href="#">
                <div class="award-item" >
                    <h3 class="award-category">Verspätetste Haltestelle</h3>
                    <p class="award-winner-long"><?php echo $verspaetetsteHaltestelleWinner; ?></p>
                    <p class="award-subtext"><?php echo $verspaetetsteHaltestelleOverviev; ?> Verspätungen</p>
                    <p class="find-out-more">Mehr erfahren</p>
                </div>
            </a>
        </div>
    </section>

</html>