<?php
session_start();
require_once 'libraries/database.php';

if (!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['auth']['id'];
$comment_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($comment_id === null || $comment_id === false) {
    die('ID de commentaire invalide.');
}

$pdo = getPdo();

// Vérifier si le commentaire appartient à l'utilisateur connecté
$query = $pdo->prepare('SELECT user_id FROM comments WHERE id = :comment_id');
$query->execute(['comment_id' => $comment_id]);
$comment = $query->fetch();

if (!$comment || $comment['user_id'] !== $user_id) {
    die('Vous ne pouvez pas supprimer ce commentaire.');
}

// Supprimer le commentaire
$query = $pdo->prepare('DELETE FROM comments WHERE id = :comment_id');
$query->execute(['comment_id' => $comment_id]);

header('Location: article.php?id=' . $_GET['article_id']);
exit;
