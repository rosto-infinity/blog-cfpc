<?php
session_start();

require_once "database/database.php";
/**
 * CE FICHIER DOIT ENREGISTRER UN NOUVEAU COMMENTAIRE EST REDIRIGER SUR L'ARTICLE !
 * 
 * On doit d'abord vérifier que toutes les informations ont été entrées dans le formulaire
 * Si ce n'est pas le cas : un message d'erreur
 * Sinon, on va sauver les informations
 * 
 * Pour sauvegarder les informations, ce serait bien qu'on soit sûr que l'article qu'on essaye de commenter existe
 * Il faudra donc faire une première requête pour s'assurer que l'article existe
 * Ensuite on pourra intégrer le commentaire
 * 
 * Et enfin on pourra rediriger l'utilisateur vers l'article en question
 */

/**
 * 1. On vérifie que les données ont bien été envoyées en POST
 * D'abord, on récupère les informations à partir du POST
 * Ensuite, on vérifie qu'elles ne sont pas nulles
 */
// 6var_dump($_SESSION['auth']['id']);
// die();
if (!isset($_SESSION['auth']['id'])) {
  header("Location: login.php");
  exit;
}


$user_auth = $_SESSION['auth']['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


  $content = htmlspecialchars($_POST['content'] ?? null);
  $article_id = $_POST['article_id'] ?? null;

  // Vérification de l'existence de l'article
  $query = $pdo->prepare('SELECT COUNT(*) FROM articles WHERE id = :article_id');
  $query->execute(['article_id' => $article_id]);
  $articleExists = $query->fetchColumn();


  //Insertion du commentaire

  $query = $pdo->prepare("INSERT INTO
   comments (content, article_id, user_id, created_at)
   VALUES(:content, :article_id, :user_auth , NOW())
   ");
  $query->execute(compact('content', 'article_id', 'user_auth'));


  //Rediriger vers la pages des articles apre l'ajout du commentaire

  header("Location: article.php?id=" . $article_id);
  exit;
}
