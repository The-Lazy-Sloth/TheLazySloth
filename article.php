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
echo '<link rel="stylesheet" href="cdnjs.cloudflare.com/ajax/libs/highlight.js/9.6.0/styles/default.min.css">';

include("header-2.php");
?>

<section>

  <div class="article">

      <?php

      echo '<h1 class="article_title">' . $row['article_title'] . '</h1>';

      echo '<div class="article_sub">' . $row['article_date'] . '<br/>';
      echo 'Catégorie(s) : ';

      $categories_query = "SELECT category_name FROM tls_categories JOIN tls_article_category JOIN tls_articles WHERE article_id = ac_article_id AND category_id = ac_category_id AND article_id = " . $row['article_id'];

      if($res_cat = $mysqli->query($categories_query))
      {
          while($row_cat = $res_cat->fetch_assoc())
              echo ' ' . $row_cat['category_name'];
      }

      $res_cat->free();
      
      echo '</div>';

      echo $row['article_content'];

      echo '<div class="article_source">';
      echo $row['article_sources'];
      echo '</div>';

      $mysqli->close();
      ?>
        
    </div>

</section>

<script src="cdnjs.cloudflare.com/ajax/libs/highlight.js/9.6.0/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>

<?php include("footer.php"); ?>
