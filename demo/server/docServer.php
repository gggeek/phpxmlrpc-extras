<?php
/**
 * Demo of the Self-documenting PHP-XMLRPC server
 *
 * @author Gaetano Giunta
 * @copyright (c) 2005-2021 G. Giunta
 * @license code licensed under the BSD License: see license.txt
 **/

require_once __DIR__ . "/_prepend.php";

use PhpXmlRpc\Extras\SelfDocumentingServer;

/// @todo add more stuff, eg. by including the server from the phpxmlrpc core lib...
$server = new SelfDocumentingServer(null, false);
$server->setDebug(2);
$server->service();

require_once __DIR__ . "/_append.php";
