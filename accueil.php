<?php include("header-1.php"); ?>

<meta name="description" content="Blog pour les feignants avec pleins d'articles super cool vous permettant d'apprendre plein de choses. (enfin, j'espère). Parce que le savoir, c'est le pouvoir !">
<title>The Lazy Sloth - Accueil</title>

<?php include("header-2.php");

$mysqli = mysqli_connect("localhost", "root", "Istiolorf3", "tls");

if (mysqli_connect_errno($mysqli)) {
    echo "Echec lors de la connexion à MySQL : " . mysqli_connect_error();
}
$mysqli->query("SET NAMES 'utf8'");
?>

<section>
<?php

include("nav.php");

$pair = 0;
$old = 0;
$not_old = false;

if(isset($_GET['o']))
    $old = $_GET['o'];

echo '<div class="col_article">';

$article_query = "";

if(isset($_GET['c']))
    $article_query = "SELECT * FROM tls_categories JOIN tls_article_category JOIN tls_articles WHERE tls_categories.category_id = tls_article_category.ac_category_id AND tls_articles.article_id = tls_article_category.ac_article_id AND tls_categories.category_name = '" . $_GET['c'] . "' ORDER BY tls_articles.article_id DESC LIMIT " . $old * 5 . ", 5";
else
    $article_query = "SELECT * FROM tls_articles ORDER BY article_id DESC LIMIT " . $old * 5 . ", 5";

if($res = $mysqli->query($article_query))
{
    if($res->num_rows == 0)
        $not_old = true;

    while ($row = $res->fetch_assoc())
    {
        if($row['article_id'] == 1)
            $not_old = true;

        $cats_query = "SELECT * FROM tls_categories JOIN tls_article_category WHERE tls_categories.category_id = tls_article_category.ac_category_id AND tls_article_category.ac_article_id = " . $row['article_id'];
        $comments_query = "SELECT * FROM tls_comments JOIN tls_articles WHERE tls_articles.article_id = " . $row['article_id'];

        echo '<div class="index_article">';
        $image;
        $date;
        
        if($pair%2)
        {
            $image = "index_article_image_right";
            $date = "index_article_date_left";
        }
        else
        {
            $image = "index_article_image_left";
            $date = "index_article_date_right";
        }

        echo '<a href="/article/' . $row['article_url_title'] . '"><h2 class="index_article_title">' . $row['article_title'] . '</h2></a>';
        echo '<a href="/article/' . $row['article_url_title'] . '"><img src="/static/img/'.$row['article_image'] .'" class="' . $image . '" /></a>';

        echo $row['article_resume'];
            
        echo '<div class="index_article_date ' . $date . '">' . $row['article_date'] . ' - ';
        if($res_cats = $mysqli->query($cats_query))
        {
            $b = true;
            while($row_cat = $res_cats->fetch_assoc())
            {
                if(!$b)
                    echo ' / ';
                echo '<a style="color: grey;" href="/categorie/' . $row_cat['category_url'] . '/0">' . $row_cat['category_name'] . '</a>';
                $b = false;
            }
            $res_cats->free();
        }
        echo ' - ';
        if($res_coms = $mysqli->query($comments_query))
            echo $res_coms->num_rows . ' commentaire(s)';
        echo '</div>';
        
        echo '</div>';

        $pair++;
    }

    if(!$not_old)
    {
        if(isset($_GET['c']))
            echo '<a href="/categorie/' . $_GET['c'] . '/' . ($old+1) . '"><div class="index_article_old">Les anciens articles</div></a>';
        else
            echo '<a href="/archive/' . ($old+1) . '"><div class="index_article_old">Les anciens articles</div></a>';
    }

    
    echo '</div>';
    $res->free();
}

$mysqli->close();

?>

  <div class="bas"></div>
</section>

<?php include("footer.php"); ?>
