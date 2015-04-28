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
        /** @var ArticleEditResponder $responder */
        $responder = $this->prepareResponder(new ArticleEditResponder());

        if ($request->isMethod(Request::METHOD_POST)) {
//            $responder->setArticle($article);
//            return $responder;
        }

        if ($request->getGetParam('id')) {
            $article = $this->entityManager->getRepository('stagecoach\journal\Article')->findOneBy(['id' => $request->getGetParam('id')]);
            if (!$article) {
                return new Redirect($router->getUrlFromAction(ArticleList::class), $this->translator->translate('article_not_found'));
            }
        } else {
            $article = new Article();
        }

        $responder->setArticle($article);
        return $responder;
    }
}
