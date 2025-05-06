<?php
session_start();
require_once "database/database.php";

if($_SESSION['role'] != 'admin')
{
  header('Location: index.php');
  exit();
}
//-Nettoyage des entrées

function clean_input($data)
{
  return htmlspecialchars(stripslashes(trim($data)));
}
function createSlug($title) {
  // ----Remplace les caractères accentués par leur équivalent sans accent
  $title = removeAccents($title);
  
  // --Remplace les espaces par des tirets
  $slug = strtolower(str_replace(' ', '-', $title));
  
  // -S---upprime les caractères non alphanumériques et les tirets
  $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
  
  // -Remplace les tirets multiples par un seul tiret
  $slug = preg_replace('/-+/', '-', $slug);
  
  // -Supprime les tirets en début et fin de chaîne
  $slug = trim($slug, '-');
  
  return $slug;
}
function removeAccents($string) {
  $accents = [
      'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
      'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
      'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
      'ñ' => 'n',
      'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o',
      'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
      'ý' => 'y', 'ÿ' => 'y',
      'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A',
      'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
      'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
      'Ñ' => 'N',
      'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O',
      'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U',
      'Ý' => 'Y'
  ];
  return strtr($string, $accents);
}

// -Récupere les données des entrées de l'user

if(isset($_POST['add-article']))
{
   $title =clean_input($_POST['title']);
   $slug = createSlug($title);
  //  var_dump($slug);
  //  die;
   $introduction =clean_input($_POST['introduction']);
   $content =clean_input($_POST['content']);
    
   // --Validation des données
     if (empty($title) || empty($slug) || empty($introduction) || empty($content)) {
      $error = "Veuillez remplir tous les champs du formulaire !";
    } 
      // --Insertion du nouvel article dans la base de données
      $query = $pdo->prepare('INSERT INTO articles (title, slug, introduction, content, created_at) VALUES (:title, :slug, :introduction, :content, NOW())');
      $query->execute(compact('title', 'slug', 'introduction', 'content'));
    
  }

// -Recuperation de tous les articles

$query ="SELECT * FROM articles ORDER BY created_at DESC ";
$resultats =$pdo->prepare($query);
$resultats->execute();
$allArticles =$resultats->fetchAll();

$pageTitle ='Page Admin'; 

// 2-Debut du tampon de la page de sortie
 
ob_start();

// 3-inclure le layout de la page d' accueil
require_once 'layouts/adminfghghhjfhf/admin_dashboardgfdgdqsfqqssqs_html.php';

//4-recuperation du contenu du tampon de la page d'accueil
$pageContent = ob_get_clean();

//5-Inclure le layout de la page de sortie
require_once 'layouts/layout_html.php';