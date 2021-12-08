-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 07. pro 2021, 08:45
-- Verze serveru: 10.4.22-MariaDB
-- Verze PHP: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `penzion`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `stranka`
--

CREATE TABLE `stranka` (
  `id` varchar(255) NOT NULL,
  `titulek` varchar(255) DEFAULT NULL,
  `menu` varchar(255) DEFAULT NULL,
  `obsah` text DEFAULT NULL,
  `poradi` int(11) DEFAULT NULL,
  `obrazek` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `stranka`
--

INSERT INTO `stranka` (`id`, `titulek`, `menu`, `obsah`, `poradi`, `obrazek`) VALUES
('404', 'Stránka neexistuje', '', '<h1>Stránka neexistuje</h1>\r\n<p><img src=\"img/404-amazon (1).jpg\" alt=\"404\" /></p>', 9, 'primapenzion-main.jpg'),
('domu', 'Penzion a restaurace', 'Domů', '<div class=\"obsah\">\r\n<div class=\"container\">\r\n<h1>PENZION A RESTAURACE</h1>\r\n<h2>„Inspirováno krásnými věcmi.“</h2>\r\n<p>Hledáte ubytování v západních Čechách nebo dokonce klidný penzion v západních Čechách? Pak jste na správném místě. Rodinný penzion Žuhansta nabízí kromě příjemného a levného ubytování i celou řadu možností pro pořádání společenských akcí či výletů po okolní přírodě (oblast řeky Berounky), která je na seznamu UNESCO.</p>\r\n</div>\r\n</div>', 5, 'primapenzion-main.jpg'),
('galerie', 'Galerie', 'Galerie', '<div class=\"galerie\">\r\n<div class=\"container\">\r\n<h1>Galerie</h1>\r\n<div class=\"foto\"><img src=\"img/img1-min.jpeg\" alt=\"obrazek1\" /> <img src=\"img/img2-min.jpeg\" alt=\"obrazek2\" /> <img src=\"img/img3-min.jpeg\" alt=\"obrazek3\" /> <img src=\"img/img4-min.jpeg\" alt=\"obrazek4\" /> <img src=\"img/img5-min.jpeg\" alt=\"obrazek5\" /> <img src=\"img/img6-min.jpeg\" alt=\"obrazek6\" /> <img src=\"img/img7-min.jpeg\" alt=\"obrazek7\" /> <img src=\"img/img8-min.jpeg\" alt=\"obrazek8\" /></div>\r\n</div>\r\n</div>', 8, 'primapenzion-room2.jpeg'),
('kontakt', 'Kontakt', 'Kontakt', '<div class=\"kontakt\">\r\n<div class=\"container\">\r\n<div class=\"kontaktGrey\">\r\n<h1>Kontakt</h1>\r\n<div class=\"kontaktFlex\">\r\n<div class=\"kontaktBox\">\r\n<p><i class=\"fas fa-map-pin\"></i> <a href=\"https://goo.gl/maps/B2wsSRKtJF1HcXcc8\" target=\"_blank\" rel=\"noopener\"><strong>PrimaPenzion</strong>, Jablonského 2, Praha 7</a></p>\r\n<p><i class=\"fas fa-phone-alt\"></i> <a class=\"tel\" href=\"tel:+420606123456\">(+420) 606 123 456</a></p>\r\n<p><i class=\"far fa-envelope\"></i> <b>info@primapenzion.cz</b></p>\r\n</div>\r\n<div class=\"kontaktBox\">\r\n<p><strong>Po - Pa:</strong> 8 - 18h</p>\r\n<p><strong>So:</strong> 10 - 22h</p>\r\n<p><strong>Ne:</strong> Zavřeno</p>\r\n</div>\r\n</div>\r\n<p>Přijímáme platební karty.</p>\r\n<p class=\"kartyJakub\"><i class=\"fab fa-cc-visa\"></i> <i class=\"fab fa-cc-mastercard\"></i> <i class=\"fab fa-cc-paypal\"></i></p>\r\n</div>\r\n<iframe width=\"600\" height=\"450\" style=\"border: 0;\" src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2558.89802955696!2d14.438851716003878!3d50.10691557942946!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470b94b58406182b%3A0x1f35b827dff20972!2sJablonsk%C3%A9ho%202%2C%20170%2000%20Praha%207-Hole%C5%A1ovice!5e0!3m2!1scs!2scz!4v1636624213888!5m2!1scs!2scz\" allowfullscreen=\"allowfullscreen\" loading=\"lazy\"></iframe>\r\n<div class=\"kontaktGrey\">\r\n<h2>Napište nám</h2>\r\n<form action=\"#\" method=\"get\" class=\"napiste-nam\"><input type=\"text\" name=\"jmeno\" placeholder=\"Jméno\" /> <input type=\"text\" name=\"prijmeni\" placeholder=\"Příjmení\" /> <input type=\"email\" name=\"email\" placeholder=\"E-mail\" /> <textarea name=\"vzkaz\" placeholder=\"Napište nám ... \"></textarea> <!-- <textarea name=\"vzkaz\" id=\"\" cols=\"30\" rows=\"10\"></textarea> --> <input type=\"submit\" value=\"Odeslat\" /></form></div>\r\n</div>\r\n</div>', 6, 'primapenzion-pool-min.jpeg'),
('rezervace', 'Rezervace', 'Rezervace', '<div class=\"rezervace\">\r\n<div class=\"container\">\r\n<div class=\"kontaktGrey\">\r\n<h1>Rezervace</h1>\r\n<form action=\"#\" method=\"get\" class=\"rezervace\"><input type=\"text\" name=\"jmeno\" placeholder=\"Jméno\" /> <input type=\"text\" name=\"prijmeni\" placeholder=\"Příjmení\" /> <input type=\"email\" name=\"email\" placeholder=\"E-mail\" /> <input type=\"text\" name=\"mobil\" placeholder=\"Mobil\" /><select name=\"deti\" id=\"\">\r\n<option>Počet dětí</option>\r\n<option value=\"1\">1</option>\r\n<option value=\"2\">2</option>\r\n<option value=\"3\">3</option>\r\n<option value=\"4\">4</option>\r\n</select><select name=\"dospeli\" id=\"\">\r\n<option>Počet dospělých</option>\r\n<option value=\"1\">1</option>\r\n<option value=\"2\">2</option>\r\n<option value=\"3\">3</option>\r\n<option value=\"4\">4</option>\r\n</select><textarea name=\"vzkaz\" placeholder=\"Napište nám ... \"></textarea> <input type=\"submit\" value=\"Odeslat\" /></form></div>\r\n</div>\r\n</div>', 7, 'primapenzion-room.jpeg');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `stranka`
--
ALTER TABLE `stranka`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
