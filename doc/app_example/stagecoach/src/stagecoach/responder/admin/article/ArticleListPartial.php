<?php

namespace stagecoach\responder\admin\article;

use stagecoach\responder\AbstractPartial;

class ArticleListPartial extends AbstractPartial
{
    /**
     * @return string
     */
    protected function getTemplatePath()
    {
        return __DIR__ . '/article_list.html.php';
    }
}
