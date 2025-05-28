<?php
session_start();
require_once 'libraries/database.php';
require_once 'libraries/utils.php';

if (!isset($_SESSION['auth'])) {
    // header('Location: login.php');
    // exit;
    redirect('login.php');
   
}

$user_id = $_SESSION['auth']['id'];
$comment_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($comment_id === null || $comment_id === false) {
    die('ID de commentaire invalide.');
}



// Vérifier si le commentaire appartient à l'utilisateur connecté

$commentAuthorId = getCommentAuthorId($comment_id);

// echo"<pre>";
// print_r($commentUserId );
// echo"<pre>";
// die();

// --Si les informations de connexion sont correctes, on crée une session et on redirige vers la page d'accueil de l'admin ou l'utilisateur

if (isset($_SESSION['auth']) && $_SESSION['auth']['id'] === $commentAuthorId) {
    // L'utilisateur est autorisé à supprimer ce commentaire
    // -Supprimer le commentaire
    // Code de suppression ici
    deleteComment($comment_id);
}else {
    // L'utilisateur n'est pas autorisé à supprimer ce commentaire
    // Afficher un message d'erreur ou rediriger
    die('Vous ne pouvez pas supprimer ce commentaire.');
}

// header('Location: article.php?id=' . $_GET['article_id']);
// exit;
redirect("Location: article.php?id=" . $_GET['article_id']);

