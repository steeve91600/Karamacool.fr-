
<html>
<body>
	coucou steeve 
	<br />
<!--  Welcome 5
SELECT * FROM `steeveone`
	-->



<!--

Ceci est la ligne n°1
Ceci est la ligne n°2
...
-->

YEs Yes NO final Town 45
<center>

<?php
$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    die('Configuration manquante.');
}

$env = parse_ini_file($envFile);

$dbHost = $env['DB_HOST'];
$dbName = $env['DB_NAME'];
$dbUser = $env['DB_USER'];
$dbPass = $env['DB_PASS'];
$dbNum = $env['DB_NUM'];

try {
    $mysqlClient = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    error_log($e->getMessage());
    die('Erreur de connexion à la base de données.');
}
// Si tout va bien, on peut continuer

// On récupère tout le contenu de la table recipes
$sqlQuery = 'INSERT INTO `reponse` (`id`, `pseudo`, `id_com`, `contenue`) VALUES (NULL, :pseudo,:id_com, :competence)';




$recipesStatement = $mysqlClient->prepare($sqlQuery);





$recipesStatement->execute([
     'pseudo' => $_POST['pseudo'],
    'competence' => $_POST['lecontenue'],
	'id_com' => $_POST['id_com'],
  
	 
	
	 
	
   
]);



$recipes = $recipesStatement->fetchAll();

// On affiche chaque recette une à une

echo "<font color='red' size='10' >";
echo "<br />";
echo " Vous vous appelez ";
echo "<br />";

echo $_POST['pseudo'];
echo " ";
echo "<br />";
echo " Vous vivez à  ";
echo " ";
echo $_POST['lecontenue'];

echo "<br />";



?>


<p id="titre2" ><B><font size="80"><a  id="yoo2" href="tchat4.php" style="text-decoration:none" > Retour</a></font></B>
	
	</p>

</center>
</body>
</html>





