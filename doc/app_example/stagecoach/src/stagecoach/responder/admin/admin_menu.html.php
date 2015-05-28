<?php
/** @var $this \stagecoach\responder\admin\AdminMenuPartial */
/** @var $username string */
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?= $username ?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Articles <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="<?= $this->linkToAction('stagecoach\admin\ArticleList') ?>">Liste</a></li>
                    <li><a href="<?= $this->linkToAction('stagecoach\admin\ArticleEdit') ?>">Nouveau</a></li>
                </ul>
            </li>
        </ul>
        </div>
    </div>
</nav>

