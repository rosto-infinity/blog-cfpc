<section class="main2">
  <header class="section-header2">
    <h1 class="section-title2">Liste des articles</h1>
    <hr class="section-divider2">
  </header>

  <div class="articles-container2">
    <?php foreach ($articlesByPaginator as $article): ?>
      <article class="card2">
        <?php if (!empty($article['image'])): ?>
          <div class="card-image-container2">
            <img src="<?= htmlspecialchars($article['image']) ?>" 
                 alt="<?= htmlspecialchars($article['title']) ?>" 
                 class="card-image2">
          </div>
        <?php endif; ?>
        <div class="card-header2">
          <h2 class="card-title2"><?= htmlspecialchars($article['title']) ?></h2>
        </div>
        <div class="card-body2">
          <p class="card-intro2"><?= htmlspecialchars($article['introduction']) ?></p>
        </div>
        <div class="card-footer2">
          <small class="card-meta2">
            Ecrit le <?= date('d/m/Y', strtotime($article['created_at'])) ?>
          </small>
          <small class="card-comments2">
            <span class="comment-icon">💬</span>
            <?= $article['comment_count'] ?> commentaire(s)
          </small>
          <a href="article.php?id=<?= $article['id'] ?>" class="btn btn-primary">Voir plus</a>
        </div>
      </article>
    <?php endforeach; ?>
</div>

  <!-- Pagination -->
  <nav class="pagination-wrapper">
    <?= $paginator ?>
  </nav>
</section>
