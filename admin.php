<?php
session_start();
require_once "data.php";

// uživatel se chce (musí) přihlásit
if (array_key_exists("prihlaseni-submit",$_POST)) {
    // vytáhneme si data, která uživatel zadal do inputu
    $prihlasovaciJmeno = $_POST["prihlasovaci-jmeno"];
    $heslo = $_POST["heslo"];

    // kontrola správnosti zadaných údajů
    if ($prihlasovaciJmeno == "admin" && $heslo = "papousek123") {
        // uživatel zadal správné jméno a heslo, vytvoříme mu session
        $_SESSION["prihlasenyUzivatel"] = $prihlasovaciJmeno;
    }
}

// uživatel se chce odhlásit
if (array_key_exists("logout",$_GET)) {
    // smažeme session a tím uživatele odhlásíme
    unset($_SESSION["prihlasenyUzivatel"]);
    // vyčistíme url, aby tam už nebylo ?logout=true
    header("Location: ?");
}

// uživatel chce editovat
if (array_key_exists("edit",$_GET)) {
    // zjistíme id z GETu, co che uživatel editovat
    $idStrankyKEditaci = $_GET["edit"];
    // použijeme zjištěné id stránky, pole $stranky obsahuje vlastnosti aktivní instance
    $aktivniInstance = $stranky[$idStrankyKEditaci];

    // vyčistíme url, aby tam už nebylo ?edit=true
    // header("Location: ?");
}

//uzivatel chce vytvorit novou stranku
if (array_key_exists("add", $_GET)) {
    $aktivniInstance = new Stranka("","","","");
}

// uživatel chce aktualizovat web
if (array_key_exists("editace-submit",$_POST)) {
   
    if ($_POST["id-stranky"] != "") {
        $novyIdStranky = trim($_POST["id-stranky"]);
        $novyTitulekStranky = $_POST["titulek-stranky"];
        $novyMenuStranky = $_POST["menu-stranky"];
        $novyObrazekStranky = $_POST["obrazek-stranky"];

        // nasetovali jsme nové hodnoty do instance
        $aktivniInstance->setId($novyIdStranky);
        $aktivniInstance->setTitulek($novyTitulekStranky);
        $aktivniInstance->setMenu($novyMenuStranky);
        $aktivniInstance->setObrazek($novyObrazekStranky);

        //spustit zde funkce ktera vezme vlastnosti instance a prevede je do db
        $aktivniInstance->ulozitDoDatabaze();

        $novyObsahStranky = $_POST["obsah-stranky"];
        $aktivniInstance->setObsah($novyObsahStranky);

        // přesměrovat uživatele na edit se správným id
        header("Location:?edit=$novyIdStranky");
    }
}

//uzivatel chce smazat stranku
if (array_key_exists("delete", $_GET)) {
        //zjsitime id stranky kteoru cheme smazat
        $idStrankyKeSmazani = $_GET["delete"];

        //vytahneme si z pole $stranky jeji instanci
        $instanceKeSmazani = $stranky[$idStrankyKeSmazani];

        //zavolame na ni metodu smazSe()
        $instanceKeSmazani->smazSe();
        // po smazání se snaží PHP načíst a smazat stránku znovu
        // aby nám to neházelo chybu, odstraníme 'delete' atribut z url adresy
        header("Location: ?");
}

//uzivatel chce aktualizovat poradi stranek 
if (array_key_exists("novePoradiId", $_POST)){
    $poleIdVNovemPoradi = $_POST["novePoradiId"];

    //zavolat metodu tridy srtanka aby se nam to aktualizovalo v DB
    //staticke funce se nevolaji hubenou sipkou, ale dvema dvojteckami 
    Stranka::aktualizujPoradiVsechStranek($poleIdVNovemPoradi);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin sekce</title>
</head>
<body>
    <form action="" method="post"></form>
    <?php
    if (array_key_exists("prihlasenyUzivatel",$_SESSION)) {
        echo "Jste přihlášen";
        // tento odkaz simuluje odeslání GET formuláře
            /*
            <form action="" method="get">
                <input type="submit" name="logout" value="true">
            </form>
            */
        echo "<a href='?logout=true'><br>Odhlásit se</a>"; // účinek stejný jako s form:input ...

        echo "<ul id='seznam-stranek'>";
        foreach ($stranky as $page => $detaily) {
            echo "<li id='{$detaily -> getId()}'>
                <a href='?edit={$page}'>{$detaily->getId()} </a>
                <a href='?delete={$page}'> [smazat]</a>
            </li>";
        }   
        echo "</ul>";

        echo "<a href='?add=true'>Vytvořit novou stránku</a><br><br>";

        // zjistíme, zda je nastavena aktivní instance, tedy je i nastaven edit
        if (isset($aktivniInstance)) {
            $jmenoObrazku = $aktivniInstance->getObrazek();
            
            ?>
            <form action="" method="post">
                <!-- vytáhneme z databáze aktuální atributy stránky-->
                <label for="zelva">ID</label>
                <input type="text" name="id-stranky" id="zelva" value="<?php echo $aktivniInstance->getId(); ?>">
                <label for="motyl">TITULEK</label>
                <input type="text" name="titulek-stranky" id="motyl" value="<?php echo $aktivniInstance->getTitulek(); ?>">
                <label for="potkan">MENU</label>
                <input type="text" name="menu-stranky" id="potkan" value="<?php echo $aktivniInstance->getMenu(); ?>">
                <label for="">OBRÁZEK</label>
                <select name="obrazek-stranky" id="">
                    <option value="primapenzion-main.jpg" <?php echo($jmenoObrazku=="primapenzion-main.jpg")? "selected": "" ?>>Výchozí obrázek</option>
                    <option value="primapenzion-room.jpeg" <?php echo($jmenoObrazku=="primapenzion-room.jpeg")? "selected": "" ?>>Obrázek rezervace</option>
                    <option value="primapenzion-pool-min.jpeg" <?php echo($jmenoObrazku=="primapenzion-pool-min.jpeg")? "selected": "" ?>>Obrázek bazénu</option>
                    <option value="primapenzion-room2.jpeg" <?php echo($jmenoObrazku=="primapenzion-room2.jpeg")? "selected": "" ?>>Obrázek galerie</option>
                </select>
                <br><br>
                <!-- vložení html stránky do textarey musíme obalit do funkce htmlspecialchars(), jinak se překříží případné tagy form apod a stránka se částečně rozbije -->
                <textarea name="obsah-stranky" id="web-editor" cols="30" rows="35"><?php echo htmlspecialchars($aktivniInstance->getObsah());?></textarea>
                <input type="submit" name="editace-submit" value="Aktualizovat web">
            </form>
            <script src="./vendor/tinymce/js/tinymce/tinymce.min.js"></script>
            <script>
                tinymce.init({
                            selector: "#web-editor", // zvolit id textarey
                            plugins: [
                                    "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                                    "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                                    "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
                            ],
                            toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                            toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                            image_advtab: true ,
                            external_filemanager_path:"vendor/filemanager/",
                            external_plugins: { "filemanager" : "plugins/responsivefilemanager/plugin.min.js"},
                            filemanager_title:"Responsive Filemanager",
                            entity_encoding:'raw',
                            verify_html: false,
                            content_css: './css/style.css' // řádku jsme vložili aby se použily CSS styly
                    });
            </script>
            <?php
        }
    } else {
        ?>

    <!-- form:post>label+input:text+label+input:password+input:submit -->
    <!-- jde o funkci Emmet Abbreviation -->
        <form action="" method="post">
            <label for="">Uživatelské jméno</label>
            <input type="text" name="prihlasovaci-jmeno" id="">
            <label for="">Heslo</label>
            <input type="password" name="heslo" id="">
            <input type="submit" name="prihlaseni-submit" value="Přihlásit se">
        </form>
    <?php
        }
    ?>

    <script src="./vendor/jquery-3.6.0.min.js"></script>
    <script src="./vendor/jquery-ui-1.13.0/jquery-ui.min.js"></script>
    <script src="./js/admin.js"></script>
</body>
</html> 


