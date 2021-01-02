<?php

namespace PhpXmlRpc\Extras;

interface TemplateInterface
{
    /**
     * @param mixed[] $params
     * @return string
     */
    public function render($params = array());
}
