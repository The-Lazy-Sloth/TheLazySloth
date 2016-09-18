<?php

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
        $row_badass = $sentence_res->fetch_assoc();
        $sentence = $row_badass["badass_sentence"];
        $author = $row_badass["badass_author"];
        $sentence_res->free();
    }
    $badass_res->free();
}

?>

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
      <?php
      // Selection de toutes les catégories
      $categories_query = "SELECT * FROM tls_categories";

      if($categories_res = $mysqli->query($categories_query))
      {
          while($row_cat = $categories_res->fetch_assoc())
              echo '<a href="/categorie/' . $row_cat['category_url'] . '/0"><li>' . $row_cat['category_string'] . '</li></a>';
          $categories_res->free();
      }
      ?> 
    </ul>
  </div>
  <br />
  <hr />

  <div class="side_badass">
    <p><?php echo '"' . $sentence . '"'; ?></p>
    <p class="side_author"><?php echo $author; ?></p>
  </div>
</div>
