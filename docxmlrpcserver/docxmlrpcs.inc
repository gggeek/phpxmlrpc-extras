<?php
/******************************************************************************
 *
 * *** DEPRECATED ***
 *
 * This file is only used to insure backwards compatibility
 * with the previous API of the library
 *****************************************************************************/

include_once(__DIR__.'/../src/SelfDocumentingServer.php');

use PhpXmlRpc\Extras\SelfDocumentingServer;
use PhpXmlRpc\Extras\ServerDocumentor;
use PhpXmlRpc\Extras\XmlrpcSmartyTemplate;

class documenting_xmlrpc_server extends SelfDocumentingServer
{
}

function generateDocs($server, $doctype = 'html', $lang = 'en', $editorpath = '')
{
    $documentationGenerator = new ServerDocumentor(new XmlrpcSmartyTemplate(null));
    return $documentationGenerator->generateDocs($server, $doctype, $lang, $editorpath);
}

function xmlrpc_smarty($template, $params = array())
{
    foreach ($params as $key => $val) {
        $template = str_replace("{\$$key}", $val, $template);
    }
    return $template;
}

$GLOBALS['xmlrpcdocparts'] = array(
    'html' => ServerDocumentor::templates(),
    'wsdl' => array()
);
