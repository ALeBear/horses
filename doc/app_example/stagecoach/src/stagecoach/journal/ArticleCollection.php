<?php

namespace stagecoach\journal;

use IteratorAggregate;
use ArrayIterator;
use Countable;
use stagecoach\display\EntityList;

class ArticleCollection implements IteratorAggregate, EntityList, Countable
{
    /** @var Article[] */
    protected $articles;

    /**
     * @param Article[] $articles
     */
    public function __construct(array $articles)
    {
        $this->articles = $articles;
    }

    /** @inheritdoc */
    public function getAccessibleFields()
    {
        return ['Codename', 'Title'];
    }

    /** @inheritdoc */
    public function getEntityCode()
    {
        return 'article';
    }

    /** @inheritdoc */
    public function getIterator()
    {
        return new ArrayIterator($this->articles);
    }

    /** @inheritdoc */
    public function count()
    {
        return count($this->articles);
    }
}
