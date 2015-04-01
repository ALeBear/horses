<?php

namespace horses;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{
    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getGetParam($name, $default = null)
    {
        return $this->query->get($name, $default);
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getPostParam($name, $default = null)
    {
        return $this->request->get($name, $default);
    }

    /**
     * @param string $name
     * @param Validator $validator
     * @return bool
     */
    public function isGetParamValid($name, Validator $validator)
    {
        return $validator->isValid($this->query->get($name));
    }
    /**
     * @param string $name
     * @param Validator $validator
     * @return bool
     */
    public function isPostParamValid($name, Validator $validator)
    {
        return $validator->isValid($this->request->get($name));
    }
}
