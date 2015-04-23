<?php
/** @var $this \horses\responder\view\html\Partial */
/** @var $article \stagecoach\journal\Article */
?>
<form method="post">
    <label for="codename">Codename :</label>
    <input type="text" id="codename" name="codename" value="<?= $this->escapeForAttribute($article->getCodename()) ?>"/><br/>
    <label for="title">Titre :</label>
    <input type="text" id="title" name="title" value="<?= $this->escapeForAttribute($article->getTitle()) ?>"/><br/>
    <label for="articleContent">Contenu :</label>
    <textarea id="articleContent" name="articleContent">
        <?= $this->escape($article->getContent()) ?>
    </textarea><br/>
    <input type="submit"/>
</form>
