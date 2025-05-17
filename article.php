<?php
session_start();
require_once 'libraries/database.php';

// Initialiser la connexion PDO
$pdo = getPdo();
$error = [];

$article_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($article_id === NULL || $article_id === false) {
    $error['article_id'] = "Le parametre id  est invalide.";
}
$sql = "SELECT * FROM articles WHERE id =:article_id";
$query = $pdo->prepare($sql);
$query->execute(compact('article_id'));
$article = $query->fetch();

//echo "<pre>";
//var_dump($articles);
//echo "</pre>";


$sql = "SELECT comments.*, users.username 
FROM comments
JOIN users ON comments.user_id = users.id
WHERE article_id = :article_id";
$query = $pdo->prepare($sql);
$query->execute(compact('article_id'));
$commentaires  = $query->fetchAll();
// / 1--On affiche le titre autre

$pageTitle = 'Accueil des articles';

// 2-Debut du tampon de la page de sortie

ob_start();

// 3-inclure le layout de la page show
require_once 'layouts/articles/show_html.php';

//4-recuperation du contenu du tampon de la page show
$pageContent = ob_get_clean();

//5-Inclure le layout de la page de sortie
require_once 'layouts/layout_html.php';
