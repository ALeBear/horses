<?php

namespace stagecoach\responder\admin;

use horses\Router;
use stagecoach\journal\Article;
use stagecoach\responder\admin\article\FormPartial;

class ArticleEditResponder extends AbstractResponder
{
    /** @var Article */
    protected $article;

    /**
     * @param Article $article
     * @return $this
     */
    public function setArticle(Article $article)
    {
        $this->article = $article;
        return $this;
    }

    /** @inheritdoc */
    public function output(Router $router)
    {
        $layout = $this->prepareLayout($router);
        $layout->addPart('content', new FormPartial($layout));
        $layout->addVariable('article', $this->article);
        echo $layout->getRendering();
    }
}
