<?php
session_start();
require_once 'libraries/database.php';
require_once 'libraries/utils.php';

$pdo = getPdo();

// Vérifiez les autorisations d'accès à la page
if ($_SESSION['role'] !== 'admin') {
//   header('Location: index.php');
//   exit();
  redirect('index.php');  
}

// Nettoyage des entrées
function clean_input($data)
{
  return htmlspecialchars(stripslashes(trim($data)));
}

function createSlug($title)
{
  $title = removeAccents($title);
  $slug = strtolower(str_replace(' ', '-', $title));
  $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
  $slug = preg_replace('/-+/', '-', $slug);
  return trim($slug, '-');
}

function removeAccents($string)
{
  $accents = [
    'à' => 'a',
    'á' => 'a',
    'â' => 'a',
    'ã' => 'a',
    'ä' => 'a',
    'å' => 'a',
    'ç' => 'c',
    'è' => 'e',
    'é' => 'e',
    'ê' => 'e',
    'ë' => 'e',
    'ì' => 'i',
    'í' => 'i',
    'î' => 'i',
    'ï' => 'i',
    'ñ' => 'n',
    'ò' => 'o',
    'ó' => 'o',
    'ô' => 'o',
    'õ' => 'o',
    'ö' => 'o',
    'ø' => 'o',
    'ù' => 'u',
    'ú' => 'u',
    'û' => 'u',
    'ü' => 'u',
    'ý' => 'y',
    'ÿ' => 'y',
    'À' => 'A',
    'Á' => 'A',
    'Â' => 'A',
    'Ã' => 'A',
    'Ä' => 'A',
    'Å' => 'A',
    'Ç' => 'C',
    'È' => 'E',
    'É' => 'E',
    'Ê' => 'E',
    'Ë' => 'E',
    'Ì' => 'I',
    'Í' => 'I',
    'Î' => 'I',
    'Ï' => 'I',
    'Ñ' => 'N',
    'Ò' => 'O',
    'Ó' => 'O',
    'Ô' => 'O',
    'Õ' => 'O',
    'Ö' => 'O',
    'Ø' => 'O',
    'Ù' => 'U',
    'Ú' => 'U',
    'Û' => 'U',
    'Ü' => 'U',
    'Ý' => 'Y'
  ];
  return strtr($string, $accents);
}
/**
 * 
 */
// Récupération des données des entrées de l'utilisateur
if (isset($_POST['add-article'])) {
  $title = clean_input($_POST['title']);
  $slug = createSlug($title);
  $introduction = clean_input($_POST['introduction']);
  $content = clean_input($_POST['content']);
  $imagePath = null;

  // Traitement de l'image
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $uploadDir = 'uploads/articles/';
      if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0755, true);
      }
      
      $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
      $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
      
      if (in_array($extension, $allowedExtensions)) {
          $filename = uniqid('article_') . '.' . $extension;
          $destination = $uploadDir . $filename;
          
          if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
              $imagePath = $destination;
          } else {
              $error = "Erreur lors du téléchargement de l'image";
          }
      } else {
          $error = "Format d'image non supporté. Utilisez JPG, PNG, GIF ou WEBP.";
      }
  }

  // Validation des données
  if (empty($title) || empty($slug) || empty($introduction) || empty($content)) {
      $error = "Veuillez remplir tous les champs obligatoires du formulaire !";
  } else {
      // Vérification de l'unicité du slug
      $stmt = $pdo->prepare('SELECT COUNT(*) FROM articles WHERE slug = :slug');
      $stmt->execute(['slug' => $slug]);

      if ($stmt->fetchColumn() > 0) {
          $error = "Le slug '$slug' existe déjà. Veuillez en choisir un autre.";
      } else {
          // Insertion du nouvel article dans la base de données
          $query = $pdo->prepare('INSERT INTO articles 
              (title, slug, introduction, content, image, created_at) 
              VALUES (:title, :slug, :introduction, :content, :image, NOW())');
              
          $query->execute([
              'title' => $title,
              'slug' => $slug,
              'introduction' => $introduction,
              'content' => $content,
              'image' => $imagePath
          ]);
          
          if ($query->rowCount() > 0) {
              $success = "Article créé avec succès!";
          } else {
              $error = "Erreur lors de la création de l'article";
          }
      }
  }
}

// Récupération de tous les articles avec gestion des images
$allArticles = findAllArticles();

$pageTitle = 'Tableau de bord Admin';


render('adminfghghhjfhf/admin_dashboardgfdgdqsfqqssqs',compact('allArticles','pageTitle'));
