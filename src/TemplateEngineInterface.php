<?php

namespace PhpXmlRpc\Extras;

interface TemplateEngineInterface
{
    /**
     * @param string $templateName
     * @return TemplateInterface;
     */
    public function load($templateName);
}
