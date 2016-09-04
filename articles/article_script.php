<?php

require_once '../markdown-extended-master/src/bootstrap.php';
//On va chercher le fichier php qui contient le code pour mettre à jour le flux RSS
include_once("../update_rss.php");

if ($argc != 2)
{
    echo 'Error: less or more args';
    exit(1);
}

$file_name = $argv[1];

// SQL connect

$mysqli = new mysqli("localhost", "root", "Istiolorf3", "tls");

if ($mysqli->connect_errno)
{
    echo "Echec lors de la connexion à MySQL : " . $mysqli->connect_error();
    exit(1);
}

use \MarkdownExtended\MarkdownExtended;

$file_parsed = MarkdownExtended::parseSource($file_name);

$article_title = $file_parsed->getTitle();
$article_url_title = $file_parsed->getMetadata()['url-title'];
$article_desc = $file_parsed->getMetadata()['description'];
$article_author = $file_parsed->getMetadata()['author'];
$article_date = $file_parsed->getMetadata()['date'];
$article_image = $file_parsed->getMetadata()['image'];
$article_sources = $file_parsed->getMetadata()['sources'];
$article_body = $file_parsed->getBody();

$pos2 = strpos($file_parsed->getBody(), '<p>');
$pos = strpos($file_parsed->getBody(), '</p>');
$article_resume = substr($file_parsed->getBody(), $pos2, ($pos+3)-($pos2-1));
$article_resume = substr($article_resume, 0, 500);

if(strpos($article_resume, "</p>") === false)
    $article_resume = substr_replace($article_resume, "</p>", -4);

$article_insert = "INSERT INTO tls_articles(article_title, article_url_title, article_desc, article_author, article_date, article_image, article_resume, article_content, article_sources) VALUES ('" .
                  $mysqli->real_escape_string($article_title) . "', '" .
                  $mysqli->real_escape_string($article_url_title) . "', '" .
                  $mysqli->real_escape_string($article_desc) . "', '" .
                  $mysqli->real_escape_string($article_author) . "', '" .
                  $mysqli->real_escape_string($article_date) . "', '" .
                  $mysqli->real_escape_string($article_image) . "', '" .
                  $mysqli->real_escape_string($article_resume) . "', '" .
                  $mysqli->real_escape_string($article_body) . "', '" .
                  $mysqli->real_escape_string($article_sources) . "')";

// Insertion dans la base

$mysqli->query($article_insert);

$id_article = $mysqli->insert_id;

// Parsage pour catégorie

$article_category = $file_parsed->getMetadata()['categories'];
$categories = explode(",",$article_category);

$categories_prepare =  $mysqli->prepare("SELECT tls_categories.category_id FROM tls_categories WHERE tls_categories.category_name = ?"); 
$categories_prepare->bind_param('s', $cat_rdy);

// tab des id cat et id art

$tab_cat = array();

// supprime les spaces
foreach($categories as $cat)
{
    $cat_rdy = trim($cat);
    
    $categories_prepare->execute();

    $categories_prepare->bind_result($id);
    
    while($categories_prepare->fetch())
        $tab_cat[] = $id;
}

foreach($tab_cat as $id)
{
    $mysqli->query("INSERT INTO tls_article_category(ac_article_id, ac_category_id) VALUES(" . $id_article . ", " . $id . ")");
}

// Insertion du rss

// Définit le fuseau horaire par défaut à utiliser. Disponible depuis PHP 5.1
date_default_timezone_set('Europe/Paris');

$date_rfc = date("Y-m-d H:i:s");

$mysqli->query("INSERT INTO tls_rss(rss_article_id) VALUES(" . $id_article . ")");

//On appelle la fonction de mise à jour du fichier
update_fluxRSS();

?>
