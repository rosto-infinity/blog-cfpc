<?php
session_start();
require_once 'database/database.php';


/**
 * Vérifie si l'ID de l'article est passé en GET, valide et existe dans la base de données.
 * Supprime l'article si toutes les vérifications sont réussies et redirige vers la page d'accueil.
 */

// 1. Vérification de l'ID passé en GET
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false) {
  //- Utilisation de la fonction header pour rediriger avec un message d'erreur
  header("Location: error.php?message=Id de l'article non valide.");
  exit();
}

// 2. -Vérification que l'article existe
$query = $pdo->prepare('SELECT COUNT(*) FROM articles WHERE id = :id');
$query->execute(['id' => $id]);

if ($query->fetchColumn() == 0) {
  header("Location: error.php?message=L'article $id n'existe pas, vous ne pouvez donc pas le supprimer !");
  exit();
}

// 3.- Suppression de l'article
$query = $pdo->prepare('DELETE FROM articles WHERE id = :id');
$query->execute(['id' => $id]);

// 4.- Redirection vers la page d'accueil
header("Location: admin.php");
exit();
