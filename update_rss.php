<?php

//On déclare la fonction Php :
function update_fluxRSS()
{
    /*  Nous allons générer notre fichier XML d'un seul coup. Pour cela, nous allons stocker tout notre
       fichier dans une variable php : $xml.
       On commence par déclarer le fichier XML puis la version du flux RSS 2.0.
       Puis, on ajoute les éléments d'information sur le channel. Notez que nous avons volontairement
       omit quelques balises :
     */

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<rss version="2.0">';
    $xml .= '<channel>';
    $xml .= ' <title>The Lazy Sloth RSS</title>';
    $xml .= ' <link>http://www.thelazysloth.fr</link>';
    $xml .= ' <description> Suivez le flux RSS du blog The Lazy Sloth pour avoir les derniers articles en temps réel !</description>';
    $xml .= ' <language>fr</language>';
    $xml .= ' <copyright>TheLazysloth</copyright>';
    $xml .= ' <generator>PHP/MySQL</generator>';
 
    
    /*  Maintenant, nous allons nous connecter à notre base de données afin d'aller chercher les
       items à insérer dans le flux RSS.
     */
    
    //on lit les 25 premiers éléments à partir du dernier ajouté dans la base de données
    $index_selection = 0;
    $limitation = 25;
    
    //On se connecte à notre base de données (pensez à mettre les bons logins)
    try {
        $mysqli = mysqli_connect('localhost', 'root', 'Istiolorf3', 'tls');
    }
    catch(Exception $e) {die('Erreur : '.$e->getMessage());}
    
    //On prépare la requête et on exécute celle-ci pour obtenir les informations souhaitées :
    $reponse = $mysqli->query('SELECT * FROM tls_rss JOIN tls_articles WHERE article_id = rss_article_id ORDER BY rss_guid DESC LIMIT ' . $index_selection . ', ' . $limitation) or die(print_r($bdd->errorInfo()));
    
    //Une fois les informations récupérées, on ajoute un à un les items à notre fichier
    while ($donnees = $reponse->fetch_assoc())
    {
        $xml .= '<item>';
        $xml .= '<title>'.stripcslashes($donnees['article_title']).'</title>';
        $xml .= '<link>http://www.thelazysloth.fr/article/'.$donnees['article_url_title'].'</link>';
        $xml .= '<guid isPermaLink="true">'.$donnees['rss_guid'].'</guid>';
        $xml .= '<pubDate>'.(date("D, d M Y H:i:s O", strtotime($donnees['rss_guid']))).'</pubDate>';
        $xml .= '<description>'.stripcslashes($donnees['article_desc']).'</description>';
        $xml .= '</item>';
    }
    
    //Puis on termine la requête
    $reponse->free();
    
    //Et on ferme le channel et le flux RSS.
    $xml .= '</channel>';
    $xml .= '</rss>';
    
    /*  Tout notre fichier RSS est maintenant contenu dans la variable $xml.
       Nous allons maintenant l'écrire dans notre fichier XML et ainsi mettre à jour notre flux.
       Pour cela, nous allons utiliser les fonctions de php File pour écrire dans le fichier.
       
       Notez que l'adresse URL du fichier doit être relative obligatoirement !
     */
    
    //On ouvre le fichier en mode écriture
    $fp = fopen('../static/rss/tls_rss.xml', 'w+');
    
    //On écrit notre flux RSS
    fputs($fp, $xml);
    
    //Puis on referme le fichier
    fclose($fp);
}
?>
