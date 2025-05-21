<?php
session_start();
require_once 'libraries/database.php';
require_once 'libraries/utils.php';

$pdo = getPdo();
$error = "";
$success = "";
$article = []; // Initialisation de la variable article
$currentImage = null; // Initialisation explicite
// --tVérification et nettoyage des entrées
function clean_input($data)
{
  return htmlspecialchars(stripslashes(trim($data)));
}
/**
 * Éditer un article existant
 */

// Récupération des informations d'un article à modifier
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $articleId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $sql = "SELECT * FROM articles WHERE id = ?";
    $query = $pdo->prepare($sql);
    $query->execute([$articleId]);
    $article = $query->fetch(PDO::FETCH_ASSOC);
    
    // Récupération des données APRÈS la requête
    $title = $article['title'] ?? "";
    $slug = $article['slug'] ?? "";
    $introduction = $article['introduction'] ?? "";
    $content = $article['content'] ?? "";
    $currentImage = $article['image'] ?? null; // Utilisez 'image' ou 'a_image' selon votre BDD
}

// Traitement de la soumission du formulaire
if (isset($_POST['update'])) {
    // Récupération de l'ID et nettoyage
    $articleId = clean_input($_POST['id']);
    
    // ----Nettoyage des entrées
  $title = clean_input(filter_input(INPUT_POST, 'title', FILTER_DEFAULT));
  $slug = strtolower(str_replace(' ', '-', $title)); // Mise à jour du slug à partir du titre
  $introduction = clean_input(filter_input(INPUT_POST, 'introduction', FILTER_DEFAULT));
  $content = clean_input(filter_input(INPUT_POST, 'content', FILTER_DEFAULT));
  $articleId = clean_input(filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT));

    // Traitement de l'image uploadée
    if (isset($_FILES['a_image']) && $_FILES['a_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/articles/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = strtolower(pathinfo($_FILES['a_image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($extension, $allowedExtensions)) {
            // Suppression de l'ancienne image si elle existe
            if ($currentImage && file_exists($currentImage)) {
                unlink($currentImage);
            }
            
            $filename = uniqid('article_') . '.' . $extension;
            $destination = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['a_image']['tmp_name'], $destination)) {
                $currentImage = $destination;
            } else {
                $error = "Erreur lors du téléchargement de la nouvelle image";
            }
        } else {
            $error = "Format d'image non supporté. Utilisez JPG, PNG, GIF ou WEBP.";
        }
    
    }

    // Validation des données
    if (empty($title) || empty($slug) || empty($introduction) || empty($content)) {
        $error = $error ?: "Veuillez remplir tous les champs obligatoires du formulaire !";
    } else {
        // Mise à jour de l'article dans la base de données
        $query = $pdo->prepare('UPDATE articles SET 
            title = :title, 
            slug = :slug, 
            introduction = :introduction, 
            content = :content,
            image = :image,
            updated_at = NOW()
            WHERE id = :articleId');
            
        $query->execute([
            'title' => $title,
            'slug' => $slug,
            'introduction' => $introduction,
            'content' => $content,
            'image' => $currentImage, // Assurez-vous que le nom de colonne correspond à votre BDD
            'articleId' => $articleId
        ]);
        
        if ($query->rowCount() > 0) {
            $success = "Article mis à jour avec succès!";
            // Rafraîchir les données
            $query = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
            $query->execute([$articleId]);
            $article = $query->fetch(PDO::FETCH_ASSOC);
            $currentImage = $article['image'] ?? null;
        } else {
            $error = $error ?: "Aucune modification détectée ou erreur lors de la mise à jour";
        }
    }
     // --Redirection vers la page d'admin
     header("Location: admin.php");
     exit();
}

$pageTitle = 'Éditer un article';
render('articles/edit-article',compact('title', 'slug','pageTitle', 'articleId', 'introduction', 'content'));

