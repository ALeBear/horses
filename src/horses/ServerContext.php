<?php

namespace horses;

use \Symfony\Component\HttpFoundation\ParameterBag;

class ServerContext extends ParameterBag
{
    const DEFAULT_ENV = 'prod';

    const DIR_ROOT = 'DIR_ROOT';
    const DIR_APPLICATION = 'DIR_APPLICATION';
    const DIR_LIB = 'DIR_LIB';
    const DIR_CONTROLLERS = 'DIR_CONTROLLERS';
    const DIR_CONFIG = 'DIR_CONFIG';
    const DIR_PUBLIC = 'DIR_PUBLIC';

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->get('ENV', self::DEFAULT_ENV);
    }

    /**
     * @param string $dir See 'DIR_*' class constants
     * @return string
     */
    public function getPath($dir)
    {
        return $this->get($dir);
    }
}