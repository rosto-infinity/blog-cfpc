<?php
session_start();
require_once "database/database.php";

// Vérifiez les autorisations d'accès à la page
if ($_SESSION['role'] !== 'admin') {
  header('Location: index.php');
  exit();
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

// Récupération des données des entrées de l'utilisateur
if (isset($_POST['add-article'])) {
  $title = clean_input($_POST['title']);
  $slug = createSlug($title);
  $introduction = clean_input($_POST['introduction']);
  $content = clean_input($_POST['content']);

  // Validation des données
  if (empty($title) || empty($slug) || empty($introduction) || empty($content)) {
    $error = "Veuillez remplir tous les champs du formulaire !";
  } else {
    // Vérification de l'unicité du slug
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM articles WHERE slug = :slug');
    $stmt->execute(['slug' => $slug]);

    if ($stmt->fetchColumn() > 0) {
      $error = "Le slug '$slug' existe déjà. Veuillez en choisir un autre.";
    } else {
      // Insertion du nouvel article dans la base de données
      $query = $pdo->prepare('INSERT INTO articles (title, slug, introduction, content, created_at) VALUES (:title, :slug, :introduction, :content, NOW())');
      $query->execute(compact('title', 'slug', 'introduction', 'content'));
    }
  }
}

// Récupération de tous les articles
$query = "SELECT * FROM articles ORDER BY created_at DESC";
$resultats = $pdo->prepare($query);
$resultats->execute();
$allArticles = $resultats->fetchAll();

$pageTitle = 'Page Admin';

// ----Début du tampon de la page de sortie
ob_start();

// -Inclure le layout de la page d'accueil
require_once 'layouts/adminfghghhjfhf/admin_dashboardgfdgdqsfqqssqs_html.php';

// -Récupération du contenu du tampon de la page d'accueil
$pageContent = ob_get_clean();

// Inclure le layout de la page de sortie
require_once 'layouts/layout_html.php';
