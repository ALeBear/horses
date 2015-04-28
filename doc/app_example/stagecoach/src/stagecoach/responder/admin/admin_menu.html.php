<?php
/** @var $this \stagecoach\responder\admin\AdminMenuPartial */
/** @var $username string */
?>
<?= $username ?>
<ul>
    <li>
        Articles
        <ul>
            <li><a href="<?= $this->linkToAction('stagecoach\admin\ArticleList') ?>">Liste</a></li>
            <li><a href="<?= $this->linkToAction('stagecoach\admin\ArticleEdit') ?>">Nouveau</a></li>
        </ul>
    </li>
</ul>

