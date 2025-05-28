<?php
session_start();
require_once 'libraries/database.php';
require_once 'libraries/utils.php';


$error = [];

$article_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($article_id === NULL || $article_id === false) {
    $error['article_id'] = "Le parametre id  est invalide.";
}

$article = findArticle($article_id);

// echo "<pre>";
// print_r($article);
// echo "</pre>";

$commentaires  = findAllComments($article_id);
// / 1--On affiche le titre autre

$pageTitle = 'Accueil des articles';
render('articles/show',compact('article','commentaires','article_id'));

