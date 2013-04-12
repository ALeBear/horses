<?php

namespace horses;

class View
{
    /**
     * @var string
     */
    protected $__file;
    
    /**
     * @var string
     */
    protected $__layoutFile;
    

    /**
     * @param string $file
     * @param string $layoutFile
     */
    public function __construct($file, $layoutFile)
    {
        $this->__file = $file;
        $this->__layoutFile = $layoutFile;
    }
    
    /**
     * @return string
     */
    public function getFile()
    {
        return $this->__file;
    }
    
    /**
     * @return string
     */
    public function getLayoutFile()
    {
        return $this->__layoutFile;
    }
}