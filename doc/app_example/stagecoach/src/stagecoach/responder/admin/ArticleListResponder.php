<?php

namespace stagecoach\responder\admin;

use horses\Router;
use stagecoach\journal\ArticleCollection;
use stagecoach\responder\admin\article\ArticleListPartial;

class ArticleListResponder extends AbstractResponder
{
    /** @var ArticleCollection */
    protected $articles;

    /**
     * @param ArticleCollection $articles
     * @return $this
     */
    public function setArticles(ArticleCollection $articles)
    {
        $this->articles = $articles;
        return $this;
    }

    /** @inheritdoc */
    public function output(Router $router)
    {
        $layout = $this->prepareLayout($router);
        $layout->addVariable('articles', $this->articles);
        $layout->addPart('content', $this->preparePartial(new ArticleListPartial($layout)));
        echo $layout->getRendering();
    }
}
