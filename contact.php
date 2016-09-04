<?php include("header-1.php"); ?>

<meta name="description" content="Envie de me contacter directement ? De critiquer un article ? De boire une bière avec moi ? C'est ici que tout devient possible.">
<title>The Lazy Sloth - Contact</title>

<?php include("header-2.php"); ?>

<section>

  <?php
  /*
   ********************************************************************************************
     CONFIGURATION
   ********************************************************************************************
   */
  // destinataire est votre adresse mail. Pour envoyer à plusieurs à la fois, séparez-les par une virgule
  $destinataire = 'contact@thelazysloth.fr';

  // copie ? (envoie une copie au visiteur)
  $copie = 'oui';

  // Action du formulaire (si votre page a des paramètres dans l'URL)
  // si cette page est index.php?page=contact alors mettez index.php?page=contact
  // sinon, laissez vide
  $form_action = 'contact';

  // Messages de confirmation du mail
  $message_envoye = "Votre message à bien été envoyé ! Yaouu !";
  $message_non_envoye = "L'envoi du mail a échoué, veuillez réessayer !";

  // Message d'erreur du formulaire
  $message_formulaire_invalide = "Vérifiez que tous les champs soient bien remplis et que l'email soit sans erreur.";

  $message_final = "";
  
  /*
   ********************************************************************************************
     FIN DE LA CONFIGURATION
   ********************************************************************************************
   */

  /*
   * cette fonction sert à nettoyer et enregistrer un texte
   */
  function Rec($text)
  {
      $text = htmlspecialchars(trim($text), ENT_QUOTES);
      if (1 === get_magic_quotes_gpc())
      {
    		  $text = stripslashes($text);
      }
      
      $text = nl2br($text);
      return $text;
  };

  /*
   * Cette fonction sert à vérifier la syntaxe d'un email
   */
  function IsEmail($email)
  {
      $value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
      return (($value === 0) || ($value === false)) ? false : true;
  }

  // formulaire envoyé, on récupère tous les champs.
  $nom     = (isset($_POST['nom']))     ? Rec($_POST['nom'])     : '';
  $email   = (isset($_POST['email']))   ? Rec($_POST['email'])   : '';
  $objet   = (isset($_POST['objet']))   ? Rec($_POST['objet'])   : '';
  $message = (isset($_POST['message'])) ? Rec($_POST['message']) : '';

  // On va vérifier les variables et l'email ...
  $email = (IsEmail($email)) ? $email : ''; // soit l'email est vide si erroné, soit il vaut l'email entré
  $err = false; // sert pour remplir le formulaire en cas d'erreur si besoin

  if (isset($_POST['envoi']))
  {
      if (($nom != '') && ($email != '') && ($objet != '') && ($message != ''))
      {
    		  // les 4 variables sont remplies, on génère puis envoie le mail
    		  $headers  = 'From:'.$nom.' <'.$email.'>' . "\r\n";
    		  $headers .= 'Reply-To: '.$email. "\r\n" ;
    		  $headers .= 'X-Mailer:PHP/'.phpversion();
          
    		  // envoyer une copie au visiteur ?
    		  if ($copie == 'oui')
    		  {
    			    $cible = $destinataire.','.$email;
    		  }
    		  else
    		  {
    			    $cible = $destinataire;
    		  };
          
    		  // Remplacement de certains caractères spéciaux
    		  $message = str_replace("&#039;","'",$message);
    		  $message = str_replace("&#8217;","'",$message);
    		  $message = str_replace("&quot;",'"',$message);
    		  $message = str_replace('&lt;br&gt;','',$message);
    		  $message = str_replace('&lt;br /&gt;','',$message);
    		  $message = str_replace("&lt;","&lt;",$message);
    		  $message = str_replace("&gt;","&gt;",$message);
    		  $message = str_replace("&amp;","&",$message);
          
    		  // Envoi du mail
    		  $num_emails = 0;
    		  $tmp = explode(',', $cible);
    		  foreach($tmp as $email_destinataire)
    		  {
    			    if (mail($email_destinataire, $objet, $message, $headers))
    				      $num_emails++;
    		  }
          
    		  if ((($copie == 'oui') && ($num_emails == 2)) || (($copie == 'non') && ($num_emails == 1)))
    			    $message_final = $message_envoye;
    		  else
          {
              $err = true;
    			    $message_final = $message_non_envoye;
          }
      }
      else
      {
    		  // une des 3 variables (ou plus) est vide ...
    		  $message_final = $message_formulaire_invalide;
          $err = true;
      };
  }; // fin du if (!isset($_POST['envoi']))

?>
    <form id="contact" method="post" action="contact">
      <h2>
        Contact du paresseux
      </h2>
<?php
if(strlen($message_final) > 0)
{
    echo '<p class="';
    if($err)
        echo 'contact_err';
    else
        echo 'contact_ok';
    echo '">';
    echo $message_final;
    echo '</p>';
}
?>
      <p id="precontact">
        Vous pouvez me contacter avec le formulaire ci-dessous en précisant bien votre email, ça me permet d'avoir la possibilité de vous répondre (Eh oui, c'est pas bête !). Vous pouvez aussi me contacter directement à l'adresse suivante : contact(at)thelazysloth.fr
      </p>
      <p><label for="nom">Nom</label><br /><input type="text" id="nom" name="nom" tabindex="1" /></p>
      <p><label for="email">Email</label><br /><input type="text" id="email" name="email" tabindex="2" /></p>
      <p><label for="objet">Objet</label><br /><input type="text" id="objet" name="objet" tabindex="2" /></p>
      <p><label for="message">Message</label><br /><textarea id="message" name="message" tabindex="4" cols="50" rows="8"></textarea></p>
      
      <div style="text-align:center;"><input type="submit" name="envoi" value="Envoyer" /></div>
    </form> 

</section>   
<?php include("footer.php"); ?>
