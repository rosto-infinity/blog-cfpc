<?php
// 5-Connexion à la base de données avec PDO
$servername = "127.0.0.1";
$username = "valet"; // Utilisez 'root' si c'est votre utilisateur MySQL
$password = "valet"; // Utilisez le mot de passe correct
$database = "blog-cfpc";

try {
    // Établir la connexion à la base de données
    $pdo = new PDO("mysql:host=$servername;dbname=$database;charset=utf8", $username, $password);

    // 2-Configurer le mode d'erreur pour lancer des exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Message de succès
    echo "<div style='background-color:#3c763d; color:white;'>Connexion à la base de données réussie</div>";
} catch (PDOException $e) {
    // Gérer les erreurs de connexion
    echo "<div style='color:red;'>La connexion à la base de données a échoué :</div> " . $e->getMessage();
}
