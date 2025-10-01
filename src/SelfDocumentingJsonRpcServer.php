<?php

namespace PhpXmlRpc\Extras;

use PhpXmlRpc\JsonRpc\Server;

/**
 * Extends the base jsonrpc server with the capability to generate documentation about the exposed jsonrpc methods.
 * It will take advantage of a new member in the dispatch map: signature_docs it is expected to be an array with the
 * same number of members as signature, but containing a short description for every parameter.
 *
 * @todo use some AJAX magic to implement jsonrpc calls to test/debug methods without feeding to user the raw json
 * @todo add some i18n support
 * @todo add a sane way to have a set of http headers to be sent along with every type of generated documentation
 *       (eg. content-type)
 */
class SelfDocumentingJsonRpcServer extends Server
{
    use SelfDocumentingServerTrait;

    /**
     * Override service method:
     *   in case of GET requests show docs about implemented methods;
     *   in case of POST received by a form, we use the methodCall input value as if it had been sent with a tex/xml mimetype
     * @param string $data request data to be parsed, null by default
     * @param bool $returnPayload when true the payload will be returned but not echoed to screen
     * @param string $docType type of documentation to generate: html, wsdl, etc... If empty, use class default
     * @return \PhpXmlRpc\Response|string
     * @throws \Exception
     */
    function service($data = null, $returnPayload = false, $docType = '')
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return $this->handleNonRPCRequest($docType, $returnPayload);
        } else {
            // We break the jsonrpc spec here, and answer to POST requests that have been sent via a standard html form,
            // such as the one that is part of self-generated docs.
            // The POST requests should have a single field: 'methodCall', with the complete json payload
            if ($this->execute_on_form_submit && isset($_SERVER['CONTENT_TYPE'])
                && $_SERVER['CONTENT_TYPE'] == 'application/x-www-form-urlencoded'
                && isset($_POST['methodCall'])
            ) {
                if (function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc()) {
                    $_POST['methodCall'] = stripslashes($_POST['methodCall']);
                }
                return parent::service($_POST['methodCall'], $returnPayload);
            } else {
                return parent::service($data, $returnPayload);
            }
        }
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
        $documentationGenerator = new ServerDocumentor(new XmlrpcSmartyTemplate(null));
        return $documentationGenerator->generateDocs($this, $doctype, $lang, $editorPath, $displayExecutionForm);
    }
}
