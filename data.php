<?php
// připojení do databáze (nové, objektové)
$db = new PDO(
    "mysql:host=localhost;dbname=penzion;charset=utf8",
    "root",
    "",
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
);

class Stranka { // zde máme třídu, ze které budou vznikat nové instance
    // class je pouze šablona !!
    // zde definujeme sadu vlastností objektu
    protected $id;
    protected $titulek;
    protected $menu;
    protected $oldId;
    protected $obrazek;

    function __construct($argId, $argTitulek, $argMenu, $argObrazek)
    {
        $this->id = $argId;
        $this->titulek = $argTitulek;
        $this->menu = $argMenu;
        $this->oldId = "";
        $this->obrazek = $argObrazek;
    }


    //staticka funkce nepatri instanci, ale tride
    static public function aktualizujPoradiVsechStranek ($argPoleId){
        foreach ($argPoleId as $index => $id){
        $query = $GLOBALS["db"] -> prepare("UPDATE  stranka SET poradi=? WHERE id=?");
        $query->execute([$index, $id]);
        }
    }

    // GETTER
    public function getTitulek () {
        // zde před tím returnem můžeme mít nějakou logiku
        // napr. muzeme predtim nez returneme titulek udelat 
        // zaznam do databaze ze nekdo tuto vlastnot pouzil a jeho IP adresu
        // nebo můžeme titulek modifikovat
        return $this->titulek;
    }

    public function getMenu () {

        return $this->menu;
    }

    public function getId () {

        return $this->id;
    }

    public function getObrazek () {

        return $this->obrazek;
    }
    
    public function getObsah () {
        // provedeme SELECT a vytáhneme z databáze obsah této instance
        // $GLOBALS["db"] obsahuje naši proměnnou $db
        $query = $GLOBALS["db"]->prepare("SELECT * FROM stranka WHERE id=?");
        $query->execute([$this->id]); // instance
        $dbHledanaStranka = $query->fetch(); // pole

        // var_dump($dbHledanaStranka);

        // aby se nezobrazovala chyba při vytvoření nové stránky, která ještě není v databázi a fetch nemá co vytáhnout
        // je nutné pro PHP8; PHP7 to ještě neřešil
        if ($dbHledanaStranka == false) {
            return "";
        } else {
            return $dbHledanaStranka["obsah"];
        }
        
        /* 
        // file_get_contents prijima 1 parametr, a tim je cesta k souboru
        // protoze nase html soubory jsou ve stejne slozce jako data.php, 
        // tak nemusime uvadet cestu ale staci nazev souboru
        // nazev souboru jsme ziskali tim ze jste vzali id a prilepili k tomu .html
        $obsahHtmlSouboru = file_get_contents($this->id.".html");
        return $obsahHtmlSouboru;
        // toto byla funkce na čtení ze souboru
        */
    }
    
    // SETTER
    public function setObsah ($argNovyObsah) {
        // musíme spustit příkaz, který provede INSERT do databáze
        $query = $GLOBALS["db"]->prepare("UPDATE stranka SET obsah=? WHERE id=?");
        $query->execute([$argNovyObsah,$this->id]);

        // file_put_contents($this->id.".html", $argNovyObsah);
    }

    public function setId($argNovyId) {
        // originalni id si ulozime bokem abychom mohli udelat update ve funkci ulozitDoDatbaze

        $this->oldId = $this->id;
        $this->id = $argNovyId;
    }
    
    public function setTitulek($argNovyTitulek) {
        $this->titulek = $argNovyTitulek;
    
    }
    
    public function setMenu($argNovyMenu) {
        $this->menu = $argNovyMenu;
    }

    public function setObrazek($argNovyObrazek) {
        $this->obrazek = $argNovyObrazek;
    }

    // dodatečné funkce třídy

    public function ulozitDoDatabaze () {
        if ($this->oldId != "") {
            // uložení upravených údajů do databáze
            $query = $GLOBALS["db"]->prepare("UPDATE stranka SET id=?, titulek=?, menu=?, obrazek=? WHERE id=?");
            $query->execute([$this->id, $this->titulek, $this->menu, $this->obrazek, $this->oldId]);
        }else{
            // vložení nových údajů do databáze
            //toto je prikaz ktery zjisti nejvyssi hodnotu sloupecku poradi
            $query = $GLOBALS["db"]->prepare("SELECT MAX(poradi) as max_cislo FROM stranka");
            $query->execute();
            $vysledek = $query->fetch();
            $nejvyssiCisloVDb = $vysledek["max_cislo"];

            $query = $GLOBALS["db"]->prepare("INSERT INTO stranka SET id=?, titulek=?, menu=?, poradi=?, obrazek=?");
            $query->execute([$this->id, $this->titulek, $this->menu, $nejvyssiCisloVDb+1, $this->obrazek]);
        }
    }

    public function smazSe() {
        $query = $GLOBALS["db"]->prepare("DELETE FROM stranka WHERE id=?");
        $query->execute([$this->id]);
    }
}

// 3. verze - pole instanci - generovane z db =============================================
$query = $db->prepare("SELECT * FROM stranka ORDER BY poradi");
$query->execute([]);
$dbStranky = $query->fetchAll();

// var_dump($dbStranky);

$stranky = [];

foreach ($dbStranky as $dbStranka) {
    // pole instancí: pole $stranky, instance 'Stranka'
    $stranky[$dbStranka["id"]] = new Stranka($dbStranka["id"], $dbStranka["titulek"], $dbStranka["menu"], $dbStranka["obrazek"]);
}


// public (funkce) - můžeme zvenku měnit vlastnosti
// protected - vlastnost se nedá změnit zvenku; instamnce sama se může rozhodnout ke změně


// 2. verze - pole instanci (objektu) =============================================
// $stranky = [
//     "domu" => new Stranka("domu","Penzion a restaurace","Domů"),
//     "kontakt" => new Stranka("kontakt","Kontakt","Kontakt"),
//     "rezervace" => new Stranka("rezervace","Rezervace","Rezervace"),
//     "galerie" => new Stranka("galerie","Galerie","Galerie"),
//     "404" => new Stranka("404","Stránka neexistuje",""),
// ];


// 1. verze - pole poli =============================================
// $stranky = [
//     "domu" => [
//         "titulek" => "Penzion a restaurace",
//         "menu" => "domů",
//     ],
//     "kontakt" => [
//         "titulek" => "Kontakt",
//         "menu" => "kontakt",
//     ],
//     "rezervace" => [
//         "titulek" => "Rezervace",
//         "menu" => "rezervace",
//     ],
//     "galerie" => [
//         "titulek" => "Galerie",
//         "menu" => "galerie",
//     ],
//     "404" => [
//         "titulek" => "Stránka neexistuje",
//         "menu" => "",
//     ],
// ];