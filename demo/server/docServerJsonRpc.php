<?php
/**
 * Demo of the Self-documenting PHPJSONRPC server
 *
 * @author Gaetano Giunta
 * @copyright (c) 2005-2025 G. Giunta
 * @license code licensed under the BSD License: see license.txt
 **/

require_once __DIR__ . "/_prepend.php";

use PhpXmlRpc\Extras\SelfDocumentingJsonRpcServer;

// Import example webservice definitions from the demos found in the base PhpXmlrpc lib.
// Support being installed both as top-level project and as dependency
if (is_dir(__DIR__ . '/../../../../../vendor')) {
    $providersDir = __DIR__."/../../../../../vendor/phpxmlrpc/phpxmlrpc/demo/server/methodProviders/";
} else {
    $providersDir = __DIR__."/../../vendor/phpxmlrpc/phpxmlrpc/demo/server/methodProviders/";
}
$signatures1 = include($providersDir.'functions.php');
$signatures2 = include($providersDir.'interop.php');
$signatures3 = include($providersDir.'validator1.php');

$server = new SelfDocumentingJsonRpcServer(array_merge($signatures1, $signatures2, $signatures3), false);

// customize the rendering
$server->setTemplate('extrahtmlheader', '<link rel="icon" type="image/x-icon" href="https://gggeek.github.io/phpxmlrpc-jsonrpc/favicon.ico">');

// nb: setting debug level 2 breaks most json-parsers, as server output will include comments
$server->setDebug(1);

$server->service();
