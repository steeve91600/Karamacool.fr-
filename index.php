
<?php

// Chargement de la configuration
$envFile = __DIR__ . '/.env';

if (!is_readable($envFile)) {
    error_log('Fichier .env introuvable ou inaccessible.');
    http_response_code(500);
    exit('Configuration indisponible.');
}

$env = parse_ini_file($envFile, false, INI_SCANNER_RAW);

if ($env === false) {
    http_response_code(500);
    exit('Erreur de configuration.');
}

// Vérification des variables obligatoires
$required = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_NUM'];

foreach ($required as $key) {
    if (!array_key_exists($key, $env)) {
        error_log("Variable manquante : $key");
        http_response_code(500);
        exit('Configuration incomplète.');
    }
}

$dbHost = $env['DB_HOST'];
$dbName = $env['DB_NAME'];
$dbUser = $env['DB_USER'];
$dbPass = $env['DB_PASS'];
$dbNum  = $env['DB_NUM'];

try {
    $mysqlClient = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Erreur de connexion à la base de données.');
}

// Protection XSS
function e($string) {
    return htmlspecialchars(
        (string)($string ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Karmacool</title>
    <link rel="stylesheet" media="screen" type="text/css" href="erald10.css" />
    <style>
        body { text-align: center; }
        .titre-main { font-family: Helvetica, sans-serif; font-size: 5rem; color: orange; text-decoration: none; }
        .presentetchat2b { border: 2px solid transparent; transition: border-color 0.3s; margin: 0 auto; width: 800px; }
        .input-bg { font-size: 1.2rem; margin: 5px; }
        .table-chat { width: 1005px; margin: 20px auto; border-collapse: collapse; font-family: 'Courier New', monospace; font-size: 1.5rem; }
        .table-chat td, .table-chat th { border: 3px solid #4a0841; padding: 10px; }
        .fla { cursor: pointer; background-color: #f0f0f0; }
        .rep { display: none; }
    </style>
</head>
<body>

    <br/><br/>
    <h1 id="titre">
        <a id="yoo2" href="index.php" class="titre-main">Karmacool chat &#129655;</a>
    </h1>
    <p id="email"><h2>( contact@karmacool.fr )</h2></p>
    <br/>

    <div id="presentetchat2b" class="presentetchat2b">
        <p id="titre2">Leave a comment !</p>

        <form method="post" action="addi11.php">
            <p>
                <label for="titre1">Title :</label>
                <input type="text" name="titre" id="titre1" class="input-field" size="25" maxlength="50" />
            </p>    
            <p>
                <label for="name">Pseudo :</label>
                <input type="text" name="pseudo" id="name" class="input-field" size="25" maxlength="50" />
            </p>
            <p>
                <label for="thetext">Message :</label><br>
                <textarea name="lecontenue" id="thetext" class="input-field" rows="8" cols="33"></textarea>
            </p>
            <input value="Envoyer" type="submit" name="bttransmit" style="margin:5px; width:230px; height:80px; font-size:28px;" />
        </form>
    </div>

    <br/><br/>

    <?php
    // Récupération des commentaires principaux (avec leur ID pour lier les réponses)
    $sqlQuery = 'SELECT id, pseudo, datejour, contenue, LEFT(titre, 20) AS titre 
                 FROM commentaire 
                 WHERE contenue NOT LIKE "%???%" 
                 ORDER BY datejour DESC 
                 LIMIT 10';

    $recipesStatement = $mysqlClient->prepare($sqlQuery);
    $recipesStatement->execute();
    $recipes = $recipesStatement->fetchAll();
    ?>

    <table class="table-chat">
        <tr>
            <th>Pseudo</th>
            <th>Contenu</th>
        </tr>

        <?php foreach ($recipes as $recipe): ?>
            <tr>


   


                <td id="fuh1">

                    <?= e(mb_substr(trim($recipe['pseudo']), 0, 18, 'UTF-8')) ?>
                    
                </td>
                

                   <?php


if (str_ends_with( e($recipe['pseudo']) ,(string)$dbNum)) {


?>
<td id="adm">
<font size='95px' face='Courier New'  ><strong><?= e($recipe['titre']) ?></strong></font><br><br>
                <p><?= nl2br(e($recipe['contenue'])) ?></p><br>
                   <small id="bottom-right"><?= e($recipe['datejour']) ?></small>
        </td>
<?php




}  
else {
?>
<td id="fuh2">
<font size='95px' face='Courier New' ><strong><?= e($recipe['titre']) ?></strong></font><br><br>
                <p><?= nl2br(e($recipe['contenue'])) ?></p><br>
                   <small id="bottom-right"><?= e($recipe['datejour']) ?></small></td>
<?php

}
 ?>
                    
                </td>
            </tr>

            <!-- Bouton pour ouvrir la réponse -->
            <tr class="fla">
                <td id='ans' colspan="2">Click here to answer</td>
            </tr>

            <!-- Formulaire de réponse -->
            <tr class="rep">
                <td colspan="2">
                    <form method="post" action="addi22.php">
                        <input type="hidden" name="id_com" value="<?= e($recipe['id']) ?>" />
                           
                        <p>
                            <label>Pseudo :</label>
                            <input type="text" name="pseudo" class="input-field" size="25" maxlength="50" />
                        </p>
                        <p>
                            <label>Message :</label><br>
                            <textarea name="lecontenue" class="input-field" rows="8" cols="33"></textarea>
                        </p>
                        <input value="Envoyer" type="submit" name="bttransmit" style="margin:5px; width:230px; height:80px; font-size:28px;" />
                    </form>
                </td>
            </tr>

            <?php
            // Récupération dynamique des réponses liées AU COMMENTAIRE COURANT
            $sqlQuery2 = 'SELECT pseudo, contenue 
                          FROM reponse 
                          WHERE id_com = :id_com 
                          ORDER BY id ASC';
            
            $recipesStatement2 = $mysqlClient->prepare($sqlQuery2);
            $recipesStatement2->execute([':id_com' => $recipe['id']]);
            $replies = $recipesStatement2->fetchAll();
            ?>

            <?php foreach ($replies as $reply): ?>
                <tr id='fuh10' >
                    <td><em>Réponse de <?= e($reply['pseudo']) ?></em></td>
                    <td><?= nl2br(e($reply['contenue'])) ?></td>
                </tr>
            <?php endforeach; ?>

        <?php endforeach; ?>
    </table>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script> 
    $(document).ready(function(){
        // Utilisation de classes plutôt que d'IDs dupliqués
        $(document).on('focusin', '#name', function(){
            $(this).css('background-color','#69C');
            $('#presentetchat2b').css('border-color','yellow');
        }).on('focusout', '#name', function(){
            $(this).css('background-color','white');
        });    

        $(document).on('focusin', '#titre1', function(){
            $(this).css('background-color','#69C');
            $('#presentetchat2b').css('border-color','green');
        }).on('focusout', '#titre1', function(){
            $(this).css('background-color','white');
        });      

        $(document).on('focusin', '#thetext', function(){
            $(this).css('background-color','#69C');
            $('#presentetchat2b').css('border-color','red');
        }).on('focusout', '#thetext', function(){
            $(this).css('background-color','white');
        });  

        // Toggle pour afficher le formulaire de réponse
        $('.fla').click(function(){
            $(this).hide();
            $(this).next('.rep').show();
        });
    });
    </script>
</body>
</html>