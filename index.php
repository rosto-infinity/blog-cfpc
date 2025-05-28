<?php
require_once 'database/database.php';

// Initialisation du Paginator
require_once 'vendor/autoload.php';

use JasonGrimes\Paginator;
// Requête comptant le total d'articles
$totalQuery = $pdo->query("SELECT COUNT(*) FROM articles");
$totalItems = $totalQuery->fetchColumn();

$itemsPerPage = 3; // Nombre d'articles par page
$currentPage = $_GET['page'] ?? 1; // Page actuelle



// // --Requête paginée (optimisée pour MySQL)
$offset = ($currentPage - 1) * $itemsPerPage;

$sql  = 'SELECT * FROM articles 
ORDER BY created_at 
DESC 
LIMIT :limit OFFSET :offset';

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit',  $itemsPerPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset,       PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll();


$paginator = new Paginator(
    $totalItems,
    $itemsPerPage,
    $currentPage,
    '?page=(:num)' // Format de l'URL
);

// Suite de votre script existant...
$pageTitle = 'Accueil du Blog';
ob_start();
require_once 'layouts/articles/index_html.php';
$pageContent = ob_get_clean();
require_once 'layouts/layout_html.php';
