<?php
/**
 * Demo of an ajax json-rpc server + client in a single php script
 *
 * @author Gaetano Giunta
 * @copyright (c) 2006-2023 G. Giunta
 * @license code licensed under the BSD License: see license.txt
 */

require_once __DIR__ . "/../_prepend.php";

use PhpXmlRpc\Extras\JSWrapper;
use PhpXmlRpc\JsonRpc\Response;
use PhpXmlRpc\JsonRpc\Server;
use PhpXmlRpc\JsonRpc\Value;

// php functions to be exposed as webservices
function sumIntegers($msg)
{
    $v = $msg->getParam(0);
    $tot = 0;
    foreach ($v as $val) {
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

// execute webservices in case of POST requests
// (we could limit this by sniffing the content-type header)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $server = new Server($dmap);
    die();
}
?>
<html>
<head>
<?php
$wrapper = new JSWrapper();
// import all webservices from server into javascript namespace
echo $wrapper->wrapDispatchMap($dmap, 'sonofajax.php', '', null, 'jsonrpc');
?>
</head>
<body>
Click <a href="#" onclick="alert(sumintegers([10,11,12])); return false;">here</a> to execute a webservice call and
display results in a popup message...
</body>
</html>
