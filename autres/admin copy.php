<?php
session_start();
require_once "database/database.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

/**
 * Ajouter un nouvel article
 */

// Vérification et nettoyage des entrées
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Récupération des données du formulaire
if (isset($_POST['add-article'])) {
    // Nettoyage des entrées
    $title = clean_input(filter_input(INPUT_POST, 'title', FILTER_DEFAULT));
    $slug = strtolower(str_replace(' ', '-', $title)); // -Mise à jour du slug à partir du titre
    $introduction = clean_input(filter_input(INPUT_POST, 'introduction', FILTER_DEFAULT));
    $content = clean_input(filter_input(INPUT_POST, 'content', FILTER_DEFAULT));

    // Validation des données
    if (empty($title) || empty($slug) || empty($introduction) || empty($content)) {
        $error = "Veuillez remplir tous les champs du formulaire !";
    } else {
        // Vérification de l'unicité du slug
        $checkSlugQuery = $pdo->prepare('SELECT COUNT(*) FROM articles WHERE slug = ?');
        $checkSlugQuery->execute([$slug]);
        $slugExists = $checkSlugQuery->fetchColumn();

        if ($slugExists > 0) {
            // Générer un nouveau slug unique
            $baseSlug = $slug;
            $i = 1;
            while ($slugExists > 0) {
                $slug = $baseSlug . '-' . $i;
                $checkSlugQuery->execute([$slug]);
                $slugExists = $checkSlugQuery->fetchColumn();
                $i++;
            }
        }

        // Insertion du nouvel article dans la base de données
        $query = $pdo->prepare('INSERT INTO articles (title, slug, introduction, content, created_at) VALUES (?, ?, ?, ?, NOW())');
        $query->execute([$title, $slug, $introduction, $content]);
    }
}

// Récupération de tous les articles
$query = "SELECT * FROM articles ORDER BY created_at DESC";
$resultats = $pdo->prepare($query);
$resultats->execute();
$allArticles = $resultats->fetchAll();
// 1--On affiche le titre autre

$pageTitle ='Page Admin'; 

// 2-Debut du tampon de la page de sortie
 
ob_start();

// 3-inclure le layout de la page d' accueil
require_once 'layouts/adminfghghhjfhf/admin_dashboardgfdgdqsfqqssqs_html.php';

//4-recuperation du contenu du tampon de la page d'accueil
$pageContent = ob_get_clean();

//5-Inclure le layout de la page de sortie
require_once 'layouts/layout_html.php';