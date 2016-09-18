  <h3>Commentaires</h3>
  <br />

  <?php

  $requete_commentaires = "SELECT * FROM tls_comments WHERE tls_comments.comment_article_id = " . $row['article_id'];

  if($res_coms = $mysqli->query($requete_commentaires))
  {
      while ($row_coms = $res_coms->fetch_assoc())
      {
          echo '<div class="commentaire">';
          echo '<div class="titre_commentaire">' . $row_coms['comment_author'] . ' - ' . $row_coms['comment_date'] . '</div>';
          echo '<div class="contenu_commentaire">' . $row_coms['comment_content'] . '</div>';
          echo '</div>';
          echo '<br />';
      }
      $res_coms->free();
  }
  ?>

  <br />
