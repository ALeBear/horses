<?php

namespace horses\plugin\locale;

use Locale as SystemLocale;

class Locale
{
    const DEFAULT_FILE = 'common-dict';
    
    /**
     * @var string
     */
    protected $lang;
    
    /**
     * @var string[]
     */
    protected $paths = array();

    /**
     * @var string[][]
     */
    protected $dictionaries;
    
    /**
     * @var string[]
     */
    protected $filenames = array();
    
    
    /**
     * @param string $path
     * @return \horses\plugin\locale\Locale
     */
    public function injectPath($path)
    {
        $this->paths[] = $path;
        return $this;
    }
    
    /**
     * @param string $filename
     * @return \horses\plugin\locale\Locale
     */
    public function addDictionaryFilename($filename)
    {
        $this->filenames[] = $filename;
        return $this;
    }
    
    /**
     * @param string $lang
     * @return \horses\plugin\locale\Locale
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        SystemLocale::setDefault($lang);
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }
    
    /**
     * Translates a string. You can pass more parameters, they will be sprintf'ed
     * @param string $token
     * @return string
     */
    public function _($token)
    {
        if (!is_array($this->dictionaries)) {
            $this->init();
        }
        $params = func_get_args();
        array_shift($params);
        foreach ($this->dictionaries as $dictionary) {
            if (array_key_exists($token, $dictionary)) {
                return vsprintf($dictionary[$token], $params);
            }
        }
        
        return '';
    }
    
    /**
     * @return \horses\plugin\locale\Locale
     */
    protected function init()
    {
        if (is_array($this->dictionaries)) {
            return $this;
        }

        $this->dictionaries = array();
        foreach ($this->paths as $path) {
            foreach (array_merge(array(self::DEFAULT_FILE), $this->filenames) as $filename) {
                $filepath = sprintf('%s/%s.%s.ini', $path, $filename, $this->lang);
                if (file_exists($filepath)) {
                    $this->dictionaries[] = parse_ini_file($filepath);
                }
            }
        }
        
        return $this;
    }
}