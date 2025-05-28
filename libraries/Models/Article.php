<?php
require_once "libraries/database.php";
class Article
{
  /**
 * Récupère le nombre total d'articles dans la base de données.
 *
 * @return int Nombre total d'articles.
 */
 public function countArticles(): int
{
  $pdo = getPdo(); // Assurez-vous que cette fonction retourne une instance valide de PDO

  $sql = "SELECT COUNT(*) FROM articles";
  $stmt = $pdo->query($sql);
  $total = $stmt->fetchColumn();
  return (int) $total;
}

/**
 * Récupère une liste paginée d'articles depuis la base de données.
 *
 * @param int $currentPage Numéro de la page actuelle (par défaut : 1).
 * @param int $itemsPerPage Nombre d'articles par page (par défaut : 3).
 * @return array Liste des articles pour la page spécifiée.
 */
public function findAllArticlesByPaginator(int $currentPage = 1, int $itemsPerPage = 3): array
{
  // Validation des paramètres
  $currentPage = max(1, $currentPage);
  $itemsPerPage = max(1, $itemsPerPage);

  // Calcul de l'offset
  $offset = ($currentPage - 1) * $itemsPerPage;

  // Connexion à la base de données
  $pdo = getPdo();

  // Requête SQL pour récupérer les articles avec le nombre de commentaires
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

  // Préparation et exécution de la requête
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();

  // Récupération des résultats
  $articlesByPaginator = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $articlesByPaginator;
}


public function findAllArticles()
{
  $pdo = getPdo();
  // Récupération de tous les articles avec gestion des images
  $query = "SELECT *
FROM articles ORDER BY created_at DESC";

  $resultats = $pdo->prepare($query);
  $resultats->execute();
  $articles = $resultats->fetchAll(PDO::FETCH_ASSOC);
  return $articles;
}
 public function findArticle(int $article_id)
{
  $pdo = getPdo();
  $sql = "SELECT * FROM articles WHERE id =:article_id";
  $query = $pdo->prepare($sql);
  $query->execute(["article_id" => $article_id]);
  $article = $query->fetch();
  return $article;
}

public function getArticleBySlug(string $slug)
{
  $pdo = getPdo();
  // Vérification de l'unicité du slug
  $stmt = $pdo->prepare('SELECT COUNT(*) FROM articles WHERE slug = :slug');
  $findArticleBySlug = $stmt->execute(['slug' => $slug]);
  return $findArticleBySlug;
}
}
