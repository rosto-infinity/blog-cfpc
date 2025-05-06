<h1>Listes des articles</h1>

<hr>
<div>
  <?php foreach ($articles as $article): ?>
  
  <h2> <?= $article['title'] ?> </h2>

  <p> <?= $article['introduction']?> </p>

  <a href="article.php?id=<?= $article['id']?>">Voir plus</a>
  <?php endforeach; ?>
</div>