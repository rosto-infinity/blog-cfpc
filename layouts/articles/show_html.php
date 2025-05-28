<h1 class="article-title"><?= htmlspecialchars($article['title']) ?></h1>

<p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
<em>Posté le <?= htmlspecialchars($article['created_at']) ?></em><br>

<?php if (count($commentaires) === 0) : ?>
  <h2 class="comment-heading">
    Il n'y a pas encore de commentaires pour cet article... <strong>SOYEZ LE PREMIER ! :D</strong>
  </h2>
<?php else : ?>
  <h2 class="comment-heading">
    Il y a déjà <?= count($commentaires) ?> réaction<?= count($commentaires) > 1 ? 's' : '' ?> :
  </h2>

  <?php foreach ($commentaires as $commentaire) : ?>
    <div class="comment">
      <h3 class="comment-author">Commentaire de : <?= htmlspecialchars($commentaire['username']) ?></h3>
      <small class="comment-date"><?= htmlspecialchars($commentaire['created_at']) ?></small>
      <blockquote class="comment-content">
        <em><?= nl2br(htmlspecialchars($commentaire['content'])) ?></em>
      </blockquote>

      <?php if (isset($_SESSION['auth']) && $_SESSION['auth']['id'] === $commentaire['user_id']) : ?>
        <a 
          href="delete-comment.php?id=<?= $commentaire['id'] ?>&article_id=<?= $article_id ?>" 
          class="delete-comment-link"
          onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')"
        >
          Supprimer
        </a>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($_SESSION['auth'])) : ?>
  <!-- Formulaire de commentaire -->
  <form action="save-comment.php" method="POST" class="comment-form">
    <h3 class="comment-form-heading">Vous voulez réagir ? N'hésitez pas !</h3><br>

    <textarea name="content" cols="30" rows="10" placeholder="Votre commentaire..." class="comment-form-content"></textarea><br>
    <input type="hidden" name="article_id" value="<?= $article_id ?>">
    <input type="hidden" name="user_id" value="<?= $_SESSION['auth']['id'] ?>">
    <button class="comment-form-submit">COMMENTER !</button>
  </form>
<?php else : ?>
  <p>Veuillez vous connecter ou vous inscrire pour commenter.</p>
  <a href="register.php">S'inscrire</a> | <a href="login.php">Se connecter</a>
<?php endif; ?>

<p><a href="index.php">← Retour à l'accueil</a></p>
