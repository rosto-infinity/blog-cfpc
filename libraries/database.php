<?php
 /**
  *Retourne la connxion de la DBase
    *
    *@return PDO
  */
function getPdo(): PDO
{
  // Définir les constantes pour la connexion à la base de données
  if (!defined('DB_SERVERNAME')) {
    define('DB_SERVERNAME', '127.0.0.1');
  }

  if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'valet'); // Utilisez 'root' si c'est votre utilisateur MySQL
  }

  if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', 'valet'); // Utilisez le mot de passe correct
  }

  if (!defined('DB_DATABASE')) {
    define('DB_DATABASE', 'blog-cfpc');
  }
  try {
    // Établir la connexion à la base de données
    $pdo = new PDO("mysql:host=" . DB_SERVERNAME . ";dbname=" . DB_DATABASE . ";charset=utf8", DB_USERNAME, DB_PASSWORD);
    // Configurer le mode d'erreur pour lancer des exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Message de succès
    echo "<div style='background-color:#3c763d; color:white;'>Connexion à la base de données réussie</div>";
  } catch (PDOException $e) {
    // Gérer les erreurs de connexion
    echo "<div style='color:red;'>La connexion à la base de données a échoué :</div> " . $e->getMessage();
  }
  return $pdo;
}



function getCommentUserId (int $comment_id): ?int {
  $pdo =getPdo();
  $query = $pdo->prepare('SELECT user_id FROM comments WHERE id = :comment_id');
  $query->execute(['comment_id' => $comment_id]);
  $comment = $query->fetch(PDO::FETCH_ASSOC);
  return $comment ? (int)$comment['user_id'] : null;
}

function deleteArticles(int $id)
{
  $pdo = getPdo();
  // 3.- Suppression de l'article
  $query = $pdo->prepare('DELETE FROM articles WHERE id = :id');
  $query->execute(['id' => $id]);
}

/**
 * Met à jour un article dans la base de données.
 *
 * @param int $articleId L'identifiant de l'article à mettre à jour.
 * @param string $title Le nouveau titre de l'article.
 * @param string $slug Le nouveau slug de l'article.
 * @param string $introduction La nouvelle introduction de l'article.
 * @param string $content Le nouveau contenu de l'article.
 * @param string $image Le nom du fichier image associé à l'article.
 * @return bool Retourne true si la mise à jour a réussi, false sinon.
 */
function updateArticle(int $articleId, string $title, string $slug, string $introduction, string $content, string $currentImage): bool
{
    // Récupérer l'instance PDO
    $pdo = getPdo();

    // Préparer la requête SQL de mise à jour
    $sql = 'UPDATE articles SET 
                title = :title, 
                slug = :slug, 
                introduction = :introduction, 
                content = :content,
                image = :image,
                updated_at = NOW()
            WHERE id = :articleId';

    // Préparer la déclaration
    $stmt = $pdo->prepare($sql);

    // Exécuter la requête avec les paramètres fournis
    $updateArticle = $stmt->execute([
      'title' => $title,
      'slug' => $slug,
      'introduction' => $introduction,
      'content' => $content,
      'image' => $currentImage,
      'articleId' => $articleId
  ]);
    return $updateArticle;
}


function findAllComments(int $article_id)
{
      $pdo = getPdo();
      $sql = "SELECT comments.*, users.username 
    FROM comments
    JOIN users ON comments.user_id = users.id
    WHERE article_id = :article_id";
      $query = $pdo->prepare($sql);
      $query->execute(["article_id" => $article_id]);
      $commentaires  = $query->fetchAll();
      return $commentaires;
};

/**
 * Récupère l'identifiant de l'utilisateur ayant posté un commentaire.
 *
 * @param int $comment_id L'identifiant du commentaire.
 * @return int|null L'identifiant de l'utilisateur ou null si non trouvé.
 */
function getCommentAuthorId(int $comment_id): ?int
{
    $pdo = getPdo();
    $sql = 'SELECT user_id FROM comments WHERE id = :comment_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['comment_id' => $comment_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? (int)$result['user_id'] : null;
}

function deleteComment(int $comment_id)
{
  $pdo = getPdo();
  // -Supprimer le commentaire
  $query = $pdo->prepare('DELETE FROM comments WHERE id = :comment_id');
  $query->execute(['comment_id' => $comment_id]);
}


function getUserByEmailOrUsername(string $emailOrUsername): ?array {
  $pdo = getPdo();
  $query = $pdo->prepare("SELECT * FROM users WHERE email = :identifier OR username = :identifier");
  $query->execute(['identifier' => $emailOrUsername]);
  $user = $query->fetch(PDO::FETCH_ASSOC);
  return $user ?: null;
}
