<?php
/**
 * Very simple xmlrpc reverse proxy server. Forwards all requests to the gggeek.altervista.org server
 *
 * @author Gaetano Giunta
 * @copyright (c) 2006-2021 G. Giunta
 * @license code licensed under the BSD License: see license.txt
 */

require_once __DIR__ . "/_prepend.php";

use PhpXmlRpc\Extras\ReverseProxy;
use PhpXmlRpc\Client;

$server = new ReverseProxy(new Client('http://gggeek.altervista.org/sw/xmlrpc/demo/server/server.php'), false);
$server->setDebug(2);
$server->service();

require_once __DIR__ . "/_append.php";
