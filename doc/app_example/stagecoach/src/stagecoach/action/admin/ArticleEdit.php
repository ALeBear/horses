<?php

namespace stagecoach\action\admin;

use horses\Request;
use horses\responder\Redirect;
use horses\Router;
use stagecoach\journal\Article;
use stagecoach\responder\admin\ArticleEditResponder;

class ArticleEdit extends AbstractAction
{
    /** @inheritdoc */
    public function execute(Request $request, Router $router)
    {
        $responder = new ArticleEditResponder();
        $responder->setUsername($this->user->__toString());

        if ($request->isMethod(Request::METHOD_POST)) {
//            $responder->setArticle($article);
//            return $responder;
        }

        if ($request->getGetParam('id')) {
            $article = $this->entityManager->getRepository('stagecoach\journal\Article')->findOneBy(['id' => $request->getGetParam('id')]);
            if (!$article) {
                //TODO: Set temp message and redirect to articles list
                return new Redirect($router->getUrlFromAction(Index::class));
            }
        } else {
            $article = new Article();
        }

        $responder->setArticle($article);
        return $responder;
    }
}
