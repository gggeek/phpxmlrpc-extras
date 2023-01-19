<?php

namespace PhpXmlRpc\Extras;

/**
 * AJAX extension to the PHP-XMLRPC lib (works with json-rpc, too).
 * Makes use of the js-xmlrpc lib.
 * Original idea taken from the PHP-O-Lait library by Craig Mason-Jones
 *
 * @todo add a js object wrapper for all web services if user prefers oop instead
 *       of plain function names (see php-o-lait for an example),
 *       or at least a name prefix for all created functions, to prevent js namespace pollution
 * @todo find a fix for xmlrpc methods whose name contain chars invalid in js function names (eg. '.')

 * @todo add support for json with jsxmlrpc (only jsolait supports it currently)
 * @todo drop support for jsolait, as it is unmaintained
 */
class JSWrapper
{
    /**
     * Return html code to load all needed jsxmlrpc libs.
     *
     * @param string $jsLibsPath
     * @param string $protocol either 'xmlrpc' or 'jsonrpc'
     * @param string $jsLibsType either 'jsolait' or 'jsxmlrpc'
     * @return string
     */
    public function importLibs($jsLibsPath, $protocol = 'xmrlpc', $jsLibsType = 'jsxmlrpc')
    {
        if ($jsLibsPath == '') {
            $jsLibsPath = '.';
        }
        if ($jsLibsType == 'jsolait') {
            $out = '<script type="text/javascript" src="' . $jsLibsPath . '/init.js"></script>
';
        } else {
            $out = '<script type="module">
    import {' . $protocol . '_client, ' . $protocol . 'msg, ' . $protocol . '_encode, ' . $protocol . '_decode} from "' . $jsLibsPath . '";
    window.' . $protocol . '_client = ' . $protocol . '_client;
    window.' . $protocol . 'msg = ' . $protocol . 'msg;
    window.' . $protocol . '_encode = ' . $protocol . '_encode;
    window.' . $protocol . '_decode = ' . $protocol . '_decode;
</script>
';
        }
        return $out;
    }

    /**
     * Return js code providing a function to call a method of an xml-rpc server.
     * Note that method names should NOT contain dot characters, nor url contain double quotes
     *
     * @todo catch run-time xmlrpc exceptions without a js alert
     */
    public function wrapXmlrpcMethod($method, $url, $libUrl = '', $protocol = 'xmrlpc', $jsLibsType = 'jsxmlrpc')
    {
        //$method = str_replace('.', '_', $method);
        if ($jsLibsType == 'jsolait') {
            $out = 'function ' . $method . ' (args) {
	var ' . $protocol . ' = null;
	var ' . $protocol . '_server = null;';
            /*if ($baseurl != '')
            {
                $out .= '
            jsolait.baseURL = '.$baseurl;
            }*/
            if ($libUrl != '') {
                $out .= '
	jsolait.libURL = "' . $libUrl . '"';
            }
            $out .= '
	try {
		' . $protocol . ' = importModule("' . $protocol . '");
		' . $protocol . '_server = new ' . $protocol . '.ServiceProxy("' . $url . '", ["' . $method . '"]);
		try {
			return ' . $protocol . '_server.' . $method . '(args);
		} catch (e) {
			console.log(e);
		}
	} catch (e) {
		console.log(e);
	}
}
';
        } else {
            $out = 'function ' . $method . ' () {
	var ws_client = new ' . $protocol . '_client("' . $url . '");
	var ws_args = [];
	for (var i = 0; i < arguments.length; i++)
	{
		ws_args[ws_args.length] = ' . $protocol . '_encode(arguments[i]);
	}
	var ws_msg = new ' . $protocol . 'msg("' . $method . '", ws_args);
	try {
		var ws_resp = ws_client.send(ws_msg);
		if  (ws_resp.faultCode())
			console.log("ERROR: " + ws_resp.faultCode() + " - " + ws_resp.faultString());
		else
			return  ' . $protocol . '_decode(ws_resp.value());
	} catch (e) {
		console.log(e);
	}
}
';
        }
        return $out;
    }

    /**
     * Return js code providing functions to call all methods of an xmlrpc server.
     * Note that method names with a . will not work!
     *
     * @param \PhpXmlRpc\Server $server
     * @param array $methodList list of methods to be wrapped into js (or null == expose all methods)
     * @param string $url url at which the xmlrpcserver responds
     * @param string $libUrl url of the jsolait lib (see jsolait docs for more info)
     * @param string $jsLibsType either 'jsolait' or 'jsxmlrpc'
     * @return string the js code snippet defining js functions that wrap web services
     */
    public function wrapXmlrpcServer($server, $url, $libUrl = '', $methodList = null, $jsLibsType = 'jsxmlrpc')
    {
        if (is_a($server, '\\PhpXmlRpc\\JsonRpc\\Server')) {
            $protocol = 'jsonrpc';
        } else {
            $protocol = 'xmlrpc';
        }
        return $this->wrapDispatchMap($server->getDispatchMap(), $url, $libUrl, $methodList, $protocol, $jsLibsType);
    }

    /**
     * Same functionality as 'js_wrap_xmlrpc_server', but needs only the server dispatch map (and an optional server
     * type) , not the server itself.
     *
     * @param array $dispatchMap an xmlrpc server dispatch map
     * @param string $url url at which the xml-rpc server responds
     * @param string $libUrl url of the jsxmlrpc lib (see ... docs for more info)
     * @param array $methodList list of methods to be wrapped into js (or null = wrap all)
     * @param string $protocol either 'xmlrpc' or 'jsonrpc'
     * @param string $jsLibsType either 'jsolait' or 'jsxmlrpc'
     * @return string the js code snippet defining js functions that wrap web services
     */
    public function wrapDispatchMap($dispatchMap, $url, $libUrl = '', $methodList = null, $protocol = 'xmlrpc', $jsLibsType = 'jsxmlrpc')
    {
        $out = $this->importLibs($libUrl, $protocol, $jsLibsType);
        if ($methodList == null) {
            $methodList = array_keys($dispatchMap);
        } else if (is_string($methodList)) {
            // be extra tolerant: a single method can be passed as string...
            $methodList = array($methodList);
        }

        $out .= "<script type=\"text/javascript\">\n<!--\n";
        foreach ($methodList as $method) {
            if (array_key_exists($method, $dispatchMap)) {
                $out .= $this->wrapXmlrpcMethod($method, $url, $libUrl, $protocol, $jsLibsType);
            }
        }
        $out .= "//-->\n</script>\n";
        return $out;
    }
}
