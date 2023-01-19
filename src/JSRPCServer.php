<?php
/**
 * AJAX extension to the PHP-XMLRPC lib (works with json-rpc, too).
 *
 * Makes use of the js-xmlrpc lib
 *
 * Original idea taken from the PHP-O-Lait library by Craig Mason-Jones
 *
 * @author Gaetano Giunta
 * @copyright (c) 2006-2023 G. Giunta
 * @license code licensed under the BSD License: see license.txt
 *
 * @todo add a js object wrapper for all web services if user prefers oop instead
 *       of plain function names (see php-o-lait for an example),
 *       or at least a name prefix for all created functions, to prevent js namespace pollution
 * @todo find a fix for xmlrpc methods whose name contain chars invalid in js function names (eg. '.')
 * @todo if we subclass jsonrpc server, we can use all this magic with json...
 * @todo find a better way of handling webservice errors than using a js alert()
 * @todo add support for json with jsxmlrpc (only jsolait supports it currently)
 */

namespace PhpXmlRpc\Extras;

use PhpXmlRpc\Server;

class JSRPCServer extends Server
{
    public $jsLibsPath = 'jsolait'; // default url of jsolait/jsxmlrpc lib: same dir as php script...
    public $selfUrl;
    /// Javascript lib in use: either 'jsolait' or 'jsxmlrpc'
    public $jsLibsType = 'jsxmlrpc';

    /**
     * Override base class creator, since we take care of either executing
     * or just preparing the server
     * (this means on creating class we should either execute-and-die or
     * basically do nothing. Use $servicenow=false only for debugging)
     */
    function __construct($dispatchMap = null, $serviceNow = true)
    {
        // Check if this page has been called via XML-RPC or not
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SERVER['CONTENT_TYPE']) &&
            strpos(strtolower($_SERVER['CONTENT_TYPE']), 'text/xml') === 0) {
            // xmlrpc call received: serve web service
            parent::__construct($dispatchMap, $serviceNow);
            if ($dispatchMap && $serviceNow) {
                die();
            }
        } else {
            // standard page request received: prepare self to emit javascript code that will call self
            $this->selfUrl = $_SERVER['REQUEST_URI'];
            parent::__construct($dispatchMap, false);
        }
    }

    /**
     * Echo js code that wraps calls to server.
     * Note that method names with a . will not work!
     * @param array $methodList list of methods to be wrapped. Null = all server methods (except system.XXX methods)
     * @return void
     */
    public function importMethods2JS($methodList = null)
    {
        $wrapper = new JSWrapper();
        echo $wrapper->wrapXmlrpcServer($this, $this->selfUrl, $this->jsLibsPath, $methodList, $this->jsLibsType);
    }
}
