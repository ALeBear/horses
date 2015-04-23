<?php

namespace stagecoach\journal;

/**
 * @Entity
 */
class Article
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @param integer
     */
    protected $id;

    /**
     * @Column(type="string", length=30)
     * @var string
     */
    protected $codename;

    /**
     * @Column(type="string", length=255)
     * @var string
     */
    protected $title;

    /**
     * @Column(type="string", length=5000)
     * @var string
     */
    protected $content;

    /**
     * @param string $codename
     * @param string $title
     * @param string $content
     */
    public function __construct($codename = '', $title = '', $content = '')
    {
        $this->codename = $codename;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getCodename()
    {
        return $this->codename;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
