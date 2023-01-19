<?php
/**
 * Demo of a simple ajax xmlrpc server + client in a single php script
 *
 * @author Gaetano Giunta
 * @copyright (c) 2006-2023 G. Giunta
 * @license code licensed under the BSD License: see license.txt
 */

require_once __DIR__ . "/_prepend.php";

use PhpXmlRpc\Extras\JSRPCServer;
use PhpXmlRpc\Response;
use PhpXmlRpc\Value;

// php functions to be exposed as webservices
function sumIntegers ($msg)
{
  $v = $msg->getParam(0);
  $tot = 0;
  foreach ($v as $val)
  {
    $tot = $tot + $val->scalarval();
  }

  return new Response(new Value($tot, 'int'));
}

// webservices signature
// NB: do not use dots in method names
$dmap = array(
    'sumintegers' => array(
        'function' => 'sumIntegers',
        'signature' => array(array('integer', 'array'))
    )
);

// create server object
$server = new JSRPCServer($dmap);

// nothing below here will be executed when an rpc call is being served

// here starts the front-end code
?><!DOCTYPE html>
<html lang="en">
<head>
<title>XMLRPC Extras Ajax demo</title>
<?php
    // import all webservices from server into javascript namespace
    $server->importMethods2JS();
?>
</head>
<body>
Click <a href="#" onclick="alert(sumintegers([10,11,12])); return false;">here</a> to execute a webservice call and
display results in a popup message...
</body>
</html>
