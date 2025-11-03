<?php

namespace PhpXmlRpc\Extras;

trait SelfDocumentingServerTrait
{
    /** @var string default format for generated documentation: either wsdl or html */
    public $default_doctype = 'html';
    public $default_doclang = 'en';
    /** @var string[] */
    public $supported_langs = array('en');
    /** @var string[] */
    public $supported_doctypes = array('html', 'wsdl');
    /** @var string|null relative path to the visual xml-rpc editing dialog */
    public $editorpath = '';
    /** @var bool */
    public $execute_on_form_submit = true;

    protected $documentationGenerator;

    protected function handleNonRPCRequest($docType, $returnPayload)
    {
        if ($docType == '' || !in_array($docType, $this->supported_doctypes)) {
            $docType = $this->default_doctype;
        }
        // language decoding
        if (isset($_GET['lang']) && in_array(strtolower($_GET['lang']), $this->supported_langs)) {
            $lang = strtolower($_GET['lang']);
        } else {
            $lang = $this->default_doclang;
        }

        $docs = $this->generateDocs($docType, $lang, $this->editorpath, $this->execute_on_form_submit);
        if (!$returnPayload) {
            print $docs;
        }
        return $docs;
    }

    /**
     * @param string $doctype
     * @param string $lang
     * @param string $editorPath
     * @param bool $displayExecutionForm
     * @return string
     */
    protected function generateDocs($doctype = 'html', $lang = 'en', $editorPath = '', $displayExecutionForm = true)
    {
        if ($this->documentationGenerator == null) {
            $this->documentationGenerator = new ServerDocumentor(new XmlrpcSmartyTemplate(null));
        }
        return $this->documentationGenerator->generateDocs($this, $doctype, $lang, $editorPath, $displayExecutionForm);
    }
}
