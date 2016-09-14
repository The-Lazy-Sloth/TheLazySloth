<?php
$mysqli = mysqli_connect("localhost", "root", "Istiolorf3", "tls");


if (mysqli_connect_errno($mysqli)) {
    echo "Echec lors de la connexion à MySQL : " . mysqli_connect_error();
}

$n = $_GET['n'];
$row;

if($res = $mysqli->query("SELECT * FROM tls_articles WHERE tls_articles.article_url_title='" . $mysqli->real_escape_string($n) . "'"))
{
    $row = $res->fetch_assoc();
    $res->free();
}

include("header-1.php");

echo '<meta name="description" content="' . $row['article_desc'] . '">';
echo '<title>' . $row['article_title'] . '</title>';
echo '<link href="/static/css/prism.css" rel="stylesheet" />';
include("header-2.php");
?>

<section>

  <div class="article">

      <?php

      echo '<h1 class="article_title">' . $row['article_title'] . '</h1>';

      echo '<div class="article_sub">' . $row['article_date'] . '<br/>';
      echo 'Catégorie(s) : ';

      $categories_query = "SELECT * FROM tls_categories JOIN tls_article_category JOIN tls_articles WHERE article_id = ac_article_id AND category_id = ac_category_id AND article_id = " . $row['article_id'];

      if($res_cat = $mysqli->query($categories_query))
      {
          while($row_cat = $res_cat->fetch_assoc())
              echo ' <a style="color: grey" href="/categorie/' . $row_cat['category_url']. '/0">' . $row_cat['category_name'] . '</a>';
      }

      $res_cat->free();
      
      echo '</div>';

      echo $row['article_content'];
      echo '<hr style="margin-top: 20px; margin-bottom: 10px;" />';

      $sources = explode(",", $row['article_sources']);
      
      echo '<div class="article_source">';
      echo '<h3>Sources</h3>';
      foreach($sources as $source)
          echo '<a href="' . $source . '">' . $source . '</a><br />';
      echo '</div>';

      $mysqli->close();
      ?>
        
    </div>

</section>
<script src="/static/js/prism.js"></script>
<?php include("footer.php"); ?>
