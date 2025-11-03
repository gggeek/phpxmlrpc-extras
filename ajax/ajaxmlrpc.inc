<?php
/******************************************************************************
 *
 * *** DEPRECATED ***
 *
 * This file is only used to insure backwards compatibility
 * with the previous API of the library
 *****************************************************************************/

include_once(__DIR__ . '/../src/JSRpcServer.php');
include_once(__DIR__.'/../src/JSWrapper.php');

class js_rpc_server extends PhpXmlRpc\Extras\JSRpcServer
{
}

/**
 * Return html code to include all needed jsolait / jsxmlrpc libs
 * @param string $jsLibsPath
 * @param string $jsLibsType either 'jsolait' or 'jsxmlrpc'
 * @return string
 */
function js_import_libs($jsLibsPath, $jsLibsType = 'jsolait')
{
    $w = new PhpXmlRpc\Extras\JSWrapper();
    return $w->importLibs($jsLibsPath, 'xmlrpc', $jsLibsType);
}

/**
 * Return js code providing a function to call a method of an xmlrpc server.
 * Note that method names should NOT contain dot characters, nor url contain double quotes
 */
function js_wrap_xmlrpc_method($method, $url, $liburl = '', $protocol = 'xmrlpc', $jsLibsType = 'jsolait')
{
    $w = new PhpXmlRpc\Extras\JSWrapper();
    return $w->wrapXmlrpcMethod($method, $url, $liburl, $protocol, $jsLibsType);
}

/**
 * Return js code providing functions to call all methods of an xmlrpc server.
 * Note that method names with a . will not work!
 * @param PhpXmlRpc\Server $server
 * @param array $methodlist list of methods to be wrapped into js (or null == expose all methods)
 * @param string $url url at which the xmlrpcserver responds
 * @param string $liburl url of the jsolait lib (see jsolait docs for more info)
 * @param string $jsLibsType either 'jsolait' or 'jsxmlrpc'
 * @return string the js code snippet defining js functions that wrap web services
 */
function js_wrap_xmlrpc_server($server, $url, $liburl = '', $methodlist = null, $jsLibsType = 'jsolait')
{
    $w = new PhpXmlRpc\Extras\JSWrapper();
    return $w->wrapXmlrpcServer($server, $url, $liburl, $methodlist, $jsLibsType);
}

/**
 * Same functionality as 'js_wrap_xmlrpc_server', but needs only the server dispatch map (and an optional server type),
 * not the server itself.
 * @param array $dmap an xmlrpc server dispatch map
 * @param array $methodlist list of methods to be wrapped into js (or null = wrap all)
 * @param string $url url at which the xmlrpcserver responds
 * @param string $liburl url of the jsolait lib (see jsolait docs for more info)
 * @param string $protocol either 'xmlrpc' or 'jsonrpc'
 * @param string $jsLibsType either 'jsolait' or 'jsxmlrpc'
 * @return string the js code snippet defining js functions that wrap web services
 */
function js_wrap_dispatch_map($dmap, $url, $liburl = '', $methodlist = null, $protocol = 'xmlrpc', $jsLibsType = 'jsolait')
{
    $w = new PhpXmlRpc\Extras\JSWrapper();
    return $w->wrapDispatchMap($dmap, $url, $liburl, $methodlist, $protocol, $jsLibsType);
}
