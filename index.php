<?php 
require_once 'libraries/database.php';
require_once 'libraries/utils.php';
require_once 'vendor/autoload.php';

use JasonGrimes\Paginator;

// Récupération des paramètres de pagination depuis l'URL
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 6;

// Récupération des articles pour la page actuelle
$articlesByPaginator = findAllArticlesByPaginator($currentPage, $itemsPerPage);

// Calcul du nombre total d'articles
$totalItems = countArticles();

// Initialisation du paginator
$paginator = new Paginator(  $totalItems, $itemsPerPage,  $currentPage, '?page=(:num)'
);

// Titre de la page
$pageTitle = 'Accueil du Blog';

// Rendu de la vue
render('articles/index', compact('pageTitle', 'articlesByPaginator', 'paginator'));
