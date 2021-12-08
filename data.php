<?php
$db = new PDO(
    "mysql:host=localhost;dbname=penzion;charset=utf8",
    "root",
    "",
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
);

class Stranka { //zde mame tridu ze ktere budou vznikat nove instance
    //zde definuje sadu vlastnosti objektu
    protected $id;
    protected $titulek;
    protected $menu;
    protected $oldId;
    protected $obrazek;

    function __construct ($argId, $argTitulek, $argMenu, $argObrazek) {
        $this->id = $argId;
        $this->titulek = $argTitulek;
        $this->menu = $argMenu;
        $this->oldId = "";
        $this->obrazek = $argObrazek;
    }

    //staticka funkce nepatri instanci ale tride
    static public function aktualizujPoradiVsechStranek ($argPoleId) {
        foreach ($argPoleId as $index => $id) {
            $query = $GLOBALS["db"]->prepare("UPDATE stranka SET poradi=? WHERE id=?");
            $query->execute([$index, $id]);
        }
    }

    //GETTER
    public function getTitulek () {
        //zde pred tim returnem muzeme mit nejakou logiku
        //napr. muzeme predtim nez returneme titulek udelat zazanam do databaze ze nekdo tuto vlastnot pouzil a jeho IP adresu
        //nebo muzemete ten titule jeste nejak modifikovat nez ho returnete

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
        //provedeme select a vytahneme z databaze obsah teto instance
        $query = $GLOBALS["db"]->prepare("SELECT * FROM stranka WHERE id=?");
        $query->execute([$this->id]);
        $dbHledanaStranka = $query->fetch();

        //var_dump($dbHledanaStranka);

        if ($dbHledanaStranka == false) {
            return "";
        }else{
            return $dbHledanaStranka["obsah"];
        }
        
        

        /*
        //file_get_contents prijima 1 parametr, a tim je cesta k souboru
        //protoze nase html soubory jsou ve stejne slozce jako data.php, tak nemusime uvadet cestu ale staci nazev souboru
        //nazev souboru jsme ziskali tim ze jste vzali id a prilepili k tomu .html
        $obsahHtmlSouboru = file_get_contents($this->id.".html");

        return $obsahHtmlSouboru;
        */
    }

    //SETTER
    public function setObsah ($argNovyObsah) {
        //musime spusti prikaz ktery udela update do db
        $query = $GLOBALS["db"]->prepare("UPDATE stranka SET obsah=? WHERE id=?");
        $query->execute([$argNovyObsah, $this->id]);

        //file_put_contents($this->id.".html", $argNovyObsah);
    }

    public function setId ($argNovyId) {
        //to originalni id si ulozime bokem abychom mohli udelat update ve funkci ulozitDoDatbaze
        $this->oldId = $this->id; 
        $this->id = $argNovyId;
    }

    public function setTitulek ($argNovyTitulek) {
        $this->titulek = $argNovyTitulek;
    }

    public function setMenu ($argNovyMenu) {
        $this->menu = $argNovyMenu;
    }

    public function setObrazek ($argNovyObrazek) {
        $this->obrazek = $argNovyObrazek;
    }

    //dodatecne funkce tridy

    public function ulozitDoDatabaze () {
        if ($this->oldId != "") {
           $query = $GLOBALS["db"]->prepare("UPDATE stranka SET id=?, titulek=?, menu=?, obrazek=? WHERE id=?");
            $query->execute([$this->id, $this->titulek, $this->menu, $this->obrazek, $this->oldId]); 
        }else{
            //tot je prikaz ktery zjisti nejvyssi hodnotu sloupecku poradi
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
        header("Location: ?");
    }

}

//pole instanci - generovane z db
$query = $db->prepare("SELECT * FROM stranka ORDER BY poradi");
$query->execute([]);
$dbStranky = $query->fetchAll();

//var_dump($dbStranky);

$stranky = [];

foreach ($dbStranky as $dbStranka) {
    $stranky[$dbStranka["id"]] = new Stranka($dbStranka["id"], $dbStranka["titulek"], $dbStranka["menu"], $dbStranka["obrazek"]);
}


//pole instanci(objektu)
/*
$stranky = [
    "domu" => new Stranka("domu", "PrimaPenzion - O nás", "Domů"),
    "kontakt" => new Stranka("kontakt", "Jak nás kontaktujete", "Kontakt"),
    "rezervace" => new Stranka("rezervace", "Rezervace pokojů", "Rezervace"),
    "galerie" => new Stranka("galerie", "Fotky penzionu", "Galerie"),
    "404" => new Stranka("404", "Stránka neexistuje", ""),
];
*/


//pole poli
/*
$stranky = [
	"domu" => [
		"titulek" => "PrimaPenzion - O nás",
		"menu" => "Domů",
	],
	"kontakt" => [
		"titulek" => "Jak nás kontaktujete",
		"menu" => "Kontakt",
	],
	"rezervace" => [
		"titulek" => "Rezervace pokojů",
		"menu" => "Rezervace",
	],
	"galerie" => [
		"titulek" => "Fotky penzionu",
		"menu" => "Galerie",
	],
	"404" => [
		"titulek" => "Stránka neexistuje",
		"menu" => "",
	],
];
*/