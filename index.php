<?php
// var_dump($_GET);
// pole se stránkami jsme přesunuli do ext. souboru
// a zde ho připojíme
require_once "data.php";

// pokud byla nejaka stranka vybrana
if (array_key_exists("stranka",$_GET)) {
    $stranka = $_GET["stranka"];
	// echo $stranka;
	// zkontrolujeme zda stranka existuje
	if (array_key_exists($stranka, $stranky) == false)
	{
        // pokud neexistuje, misto toho zobrazime
		$stranka = "404";
        // řekneme i prohlížeči, že stránka neexistuje
        http_response_code(404);
	}
} else {
    // pokud uzivatel prisel na index aniz by cokoliv
	// zvolil tak mu zobrazime vychozi stranku domu
	$stranka = array_keys($stranky)[0];
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "{$stranky[$stranka]->getTitulek()}" ?></title> <!-- platí při protected vlastnosti -->
    <!-- u public fce použijeme odkaz na vlastnost ->titulek -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Playfair+Display&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>

<body>

    <header>
        <div class="container">

            <div class="headerKontakty">
                <a class="tel" href="tel:+420606123456">(+420) 606 123 456</a>
                <div class="ikony">
                    <a href="#" target="_blank">
                        <i class="fab fa-facebook-square"></i></a>
                    <a href="#" target="_blank">
                        <i class="fab fa-instagram-square"></i></a>
                    <a href="#" target="_blank">
                        <i class="fab fa-twitter"></i></a>
                </div>
            </div>

            <a href="#" class="logo">Prima<br>Penzion</a>

            <div class="menu">
                <ul>
                    <?php
                    foreach ($stranky as $page => $detaily) {
                        // echo "<li><a href='?stranka=$page'>{$detaily['menu']}</a></li>"; // zobrazí v url ?stranka=
                        
                        // položku menu vypíšeme pouze pokud nebude obsahovat prázdný string
                        if ($detaily->getMenu () != "") {
                            echo "<li><a href='$page'>{$detaily->getMenu ()}</a></li>";
                        }
                    }
                    ?>
                    <!-- 
                    <li><a href="?stranka=domu">Domů</a></li>
                    <li><a href="?stranka=kontakt">Kontakt</a></li>
                    <li><a href="?stranka=rezervace">Rezervace</a></li>
                    <li><a href="?stranka=galerie">Galerie</a></li> -->
                </ul>
            </div>
            
        </div>

        <img src="img/<?php echo $stranky[$stranka]->getObrazek(); ?>" alt="penzion">

    </header>

    <section>
    <?php
        require_once "./vendor/shortcode-init.php";
        $zprocesovanyObsah = ShortcodeProcessor::process( $stranky[$stranka]->getObsah());
        //novy zpusob pomoci mysql
        echo $zprocesovanyObsah;

        //toto je stary zpusob kdy jsme pripojovali html
        //include "$stranka.html";
	?>


    </section>

    <footer>
        <div class="pata">
            <div class="container">

                <div class="menu">
                    <ul>
                    <?php
                    foreach ($stranky as $page => $detaily) {
                        // echo "<li><a href='?stranka=$page'>{$detaily['menu']}</a></li>"; // zobrazí v url ?stranka=

                        // položku menu vypíšeme pouze pokud nebude obsahovat prázdný string
                        if ($detaily->getMenu () != "") {
                            echo "<li><a href='$page'>{$detaily->getMenu ()}</a></li>";
                        }
                    }
                    ?>
                    </ul>
                </div>

                <a href="#" class="logo">Prima<br>Penzion</a>

                <div class="pataKontakty">
                    <div class="kontakty">
                        <p> 
                            <i class="fas fa-map-marked-alt"></i>
                            <a href="https://goo.gl/maps/B2wsSRKtJF1HcXcc8" target="_blank"><strong>PrimaPenzion</strong>, Jablonského 2, Praha 7</a>
                        </p>

                        <p>
                            <i class="fas fa-phone-alt fa-rotate-90"></i>
                            <a class="tel" href="tel:+420606123456">(+420) 606 123 456</a>
                        </p>
                        
                        <p class="mail">
                            <i class="fas fa-at fa-spin"></i>
                            <a href="#" href="mailto:info@primapenzion.cz">info@primapenzion.cz</a>
                        </p>
                    </div>

                    <div class="ikony">
                        <a href="#" target="_blank">
                            <i class="fab fa-facebook-square"></i></a>
                        <a href="#" target="_blank">
                            <i class="fab fa-instagram-square"></i></a>
                        <a href="#" target="_blank">
                            <i class="fab fa-twitter"></i></a>
                    </div>

                </div>

                <div class="copy">
                    &copy; Copywright 2021
                </div>

                <a href="#" class="btn">
                    <i class="fas fa-angle-double-up"></i>
                </a>
               
            </div>


        </div>
    </footer>
    <script src="./vendor/jquery-3.6.0.min.js"></script>
    <?php
        require_once "./vendor/photoswipe-init.php";
    ?>
    
</body>
</html>