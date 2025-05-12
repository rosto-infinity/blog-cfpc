<section class="main2">
  <header class="section-header2">
    <h1 class="section-title2">Liste des articles</h1>
    <hr class="section-divider2">
  </header>

  <div class="articles-container2">
    <?php  ?>
    <?php foreach ($articles as $article): ?>
      <article class="card2">
        <h2 class="card-title2"><?= htmlspecialchars($article['title']) ?></h2>
        <p class="card-intro2"><?= htmlspecialchars($article['introduction']) ?></p>
        <small>Ecrit le <?= date('d/m/Y', strtotime($article['created_at'])) ?></small><br />
        <a href="article.php?id=<?= $article['id'] ?>" class="btn btn-primary">Voir plus</a>
      </article>
    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <nav class="pagination-wrapper">
    <?= $paginator ?>
  </nav>
</section>
