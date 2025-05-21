<?php
require_once 'libraries/database.php';
require_once 'libraries/utils.php';

$pdo = getPdo();
// Initialisation du Paginator
require_once 'vendor/autoload.php';

use JasonGrimes\Paginator;

// Requête comptant le total d'articles
$totalQuery = $pdo->query("SELECT COUNT(*) FROM articles");
$totalItems = $totalQuery->fetchColumn();

$itemsPerPage = 3; // Nombre d'articles par page
$currentPage = $_GET['page'] ?? 1; // Page actuelle

// Requête paginée (optimisée pour MySQL)
$offset = ($currentPage - 1) * $itemsPerPage;

$sql = '
    SELECT 
        articles.id, 
        articles.title, 
        articles.introduction, 
        articles.created_at, 
        articles.image, 
        (SELECT COUNT(*) FROM comments WHERE comments.article_id = articles.id) AS comment_count
    FROM articles
    ORDER BY articles.created_at DESC
    LIMIT :limit OFFSET :offset
';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll();

$paginator = new Paginator(
    $totalItems,
    $itemsPerPage,
    $currentPage,
    '?page=(:num)' // Format de l'URL
);

$pageTitle = 'Accueil du Blog';

render('articles/index', compact('pageTitle', 'articles', 'paginator'));
