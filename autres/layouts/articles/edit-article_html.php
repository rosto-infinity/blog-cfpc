<h1>Éditer un article</h1>


<form method="POST" action="edit-article.php?id=<?= $articleId ?>" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $articleId ?>">
    
    <div class="form-group">
        <label for="title">Titre :</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($title ?? '') ?>" required>
    </div>
    
    <div class="form-group">
        <label for="introduction">Introduction :</label>
        <textarea name="introduction" id="introduction" required><?= htmlspecialchars($introduction ?? '') ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="content">Contenu :</label>
        <textarea name="content" id="content" required><?= htmlspecialchars($content ?? '') ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="a_image">Image de l'article :</label>
        <input type="file" name="a_image" id="a_image" accept="image/*">
        
        <?php if (!empty($currentImage)): ?>
            <div class="current-image">
                <img src="<?= $currentImage ?>" alt="Image actuelle" style="max-width: 200px;">
                <label>
                    <input type="checkbox" name="delete_image" value="1">
                    Supprimer cette image
                </label>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="form-actions">
        <input type="submit" name="update" value="Mettre à jour" class="btn primary">
        <a href="admin.php" class="btn secondary">Retour à l'administration</a>
    </div>
</form>
