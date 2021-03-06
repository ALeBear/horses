<?php

namespace horses;

use \Symfony\Component\HttpFoundation\ParameterBag;

class ServerContext extends ParameterBag
{
    const DEFAULT_ENV = 'prod';
    const PROD_ENV = 'prod';

    const DIR_ROOT = 'DIR_ROOT';
    const DIR_SRC = 'DIR_LIB';
    const DIR_ACTIONS = 'DIR_ACTIONS';
    const DIR_CONFIG = 'DIR_CONFIG';
    const DIR_PUBLIC = 'DIR_PUBLIC';
    const DIR_TMP = 'DIR_TMP';

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->get('ENV', self::DEFAULT_ENV);
    }

    /**
     * @return string
     */
    public function getApplication()
    {
        return $this->get('APP');
    }

    /**
     * @param string $dir See 'DIR_*' class constants
     * @return string
     */
    public function getPath($dir)
    {
        return $this->get($dir);
    }

    /**
     * @return bool
     */
    public function isProductionEnvironment()
    {
        return $this->getEnvironment() == self::PROD_ENV;
    }
}
