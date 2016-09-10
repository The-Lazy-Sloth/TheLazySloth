<?php include("header-1.php"); ?>

<meta name="description" content="Blog pour les feignants avec pleins d'articles super cool vous permettant d'apprendre plein de choses. (enfin, j'espère). Parce que le savoir, c'est le pouvoir !">
<title>The Lazy Sloth - Accueil</title>

<?php include("header-2.php");

$mysqli = mysqli_connect("localhost", "root", "Istiolorf3", "tls");

if (mysqli_connect_errno($mysqli)) {
    echo "Echec lors de la connexion à MySQL : " . mysqli_connect_error();
}

// Selection d'une phrase aléatoire BADASS

$sentence = "Boh... J'ai pas trouvé de phrase aujourd'hui :-(...";
$author = "The Lazy Sloth";
$badass_res;
$min = 1;
$max = 1;
if($badass_res = $mysqli->query("SELECT COUNT(*) FROM tls_badass"))
{
    $max = $badass_res->fetch_assoc()["COUNT(*)"];
    $aleat = rand($min, $max);
    if($sentence_res = $mysqli->query("SELECT * FROM tls_badass WHERE badass_id = " . $aleat))
    {
        $row = $sentence_res->fetch_assoc();
        $sentence = $row["badass_sentence"];
        $author = $row["badass_author"];
        $sentence_res->free();
    }
    $badass_res->free();
}

?>

<section>
  <div class="side">
    <form>
      <input class="side_search" type="text" name="search" placeholder="Rechercher..." disabled="disabled">
    </form>
    <div class="side_media">
      <a href="https://www.facebook.com/The-Lazy-Sloth-528574410684335/"><img src="/static/img/facebook-icon.png" alt="Icone de facebook" title="Face de bouc" width="32" height="32"/></a>
      <a href="https://twitter.com/TheLazySlothFR"><img src="/static/img/twitter-icon.png" alt="Icone de twitter" title="Le pigeon bleue" width="32" height="32"/></a>
      <a href="https://github.com/The-Lazy-Sloth"><img src="/static/img/git-icon.png" alt="Icone de github" title="L'octocat de github" width="32" height="32"/></a>
      <a href="/feed"><img src="/static/img/rss-icon.png" alt="Icone de flux RSS" title="Pour me stalker !" width="32" height="32"/></a>
    </div>
    <hr/>
    <div class="side_categories">
      <h4>Catégories</h4>
      <ul>
        <a href="/home?c=informatique"><li>Geek zone (Informatique)</li></a>
        <a href="/home?c=ecologie"><li>Green life (Ecologie)</li></a>
        <a href="/home?c=societe"><li>Ô paisible monde (Société)</li></a>
      </ul>
    </div>
    <br />
    <hr />

    <div class="side_badass">
      <p><?php echo '"' . $sentence . '"'; ?></p>
      <p class="side_author"><?php echo $author; ?></p>
    </div>
    <hr />
    
  </div>
  
<?php
  
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
            
        echo '<div class="index_article_date ' . $date . '">' . $row['article_date'] . '</div>';
        
        echo '</div>';

        $pair++;
    }

    echo '</div>';
    $res->free();
}

$mysqli->close();

if(!$not_old)
    echo '<a href="/old/' . ($old+1) . '"><div class="index_article_old">Les anciens articles</div></a>';

?>

  <div class="bas"></div>
</section>

<?php include("footer.php"); ?>
