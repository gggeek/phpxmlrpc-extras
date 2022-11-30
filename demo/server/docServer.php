<?php
/**
 * Demo of the Self-documenting PHP-XMLRPC server
 *
 * @author Gaetano Giunta
 * @copyright (c) 2005-2022 G. Giunta
 * @license code licensed under the BSD License: see license.txt
 **/

require_once __DIR__ . "/_prepend.php";

use PhpXmlRpc\Extras\SelfDocumentingServer;

// Import example webservice definitions from the demos found in the base PhpXmlrpc lib
$providersDir = __DIR__."/../../vendor/phpxmlrpc/phpxmlrpc/demo/server/methodProviders/";
$signatures1 = include($providersDir.'functions.php');
$signatures2 = include($providersDir.'interop.php');
$signatures3 = include($providersDir.'validator1.php');

$server = new SelfDocumentingServer(array_merge($signatures1, $signatures2, $signatures3), false);
$server->setDebug(2);
$server->service();

require_once __DIR__ . "/_append.php";
