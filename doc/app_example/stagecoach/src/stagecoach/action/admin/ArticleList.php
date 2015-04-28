<?php

namespace stagecoach\action\admin;

use horses\Request;
use horses\Router;
use stagecoach\journal\ArticleCollection;
use stagecoach\responder\admin\ArticleListResponder;

class ArticleList extends AbstractAction
{
    /** @inheritdoc */
    public function execute(Request $request, Router $router)
    {
        /** @var ArticleListResponder $responder */
        $responder = $this->prepareResponder(new ArticleListResponder());
        $articles = new ArticleCollection($this->entityManager->getRepository('\stagecoach\journal\article')->findAll());
        $responder->setArticles($articles);

        return $responder;
    }
}
