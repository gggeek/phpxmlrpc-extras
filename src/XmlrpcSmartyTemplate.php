<?php

namespace PhpXmlRpc\Extras;

/**
 * Dumb (dumb dumb) smarty-like template system
 *
 * @todo introduce support for multilanguage directly here
 * @todo introduce support for nested arrays, so we can coalesce templates
 */
class XmlrpcSmartyTemplate implements TemplateEngineInterface, TemplateInterface
{
    protected static $templates = array();
    protected $template = '';

    /**
     * @param string $template
     */
    public function __construct($template)
    {
        $this->template = $template;
    }

    /**
     * @param string $templateName
     * @return $this
     */
    public function load($templateName)
    {
        /// @todo throw if template is missing
        return new static(static::$templates[$templateName]);
    }

    /**
     * @param mixed[] $params
     * @return string
     */
    public function render($params = array())
    {
        $template =  $this->template;
        foreach ($params as $key => $val) {
            $template = str_replace("{\$$key}", $val, $template);
        }
        return $template;
    }

    public static function setTemplates($templateArray)
    {
        static::$templates = $templateArray;
    }
}
