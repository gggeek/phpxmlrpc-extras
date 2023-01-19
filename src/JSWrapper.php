<?php

namespace PhpXmlRpc\Extras;

class JSWrapper
{
    /**
     * Return html code to include all needed jsxmlrpc libs.
     *
     * @param string $jsLibsPath
     * @param string $jsLibsType either 'jsolait' or 'jsxmlrpc'
     * @return string
     */
    public function importLibs($jsLibsPath, $jsLibsType = 'jsxmlrpc')
    {
        if ($jsLibsPath == '') {
            $jsLibsPath = '.';
        }
        if ($jsLibsType == 'jsolait') {
            $out = '<script type="text/javascript" src="' . $jsLibsPath . '/init.js"></script>
';
        } else {
            $out = '<script type="text/javascript" src="' . $jsLibsPath . '/xmlrpc_lib.js"></script>
';
        }
        return $out;
    }

    /**
     * Return js code providing a function to call a method of an xmlrpc server.
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
			alert(e);
		}
	} catch (e) {
		alert(e);
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
			alert("ERROR: " + ws_resp.faultCode() + " - " + ws_resp.faultString());
		else
			return  ' . $protocol . '_decode(ws_resp.value());
	} catch (e) {
		alert(e);
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
        if (is_a($server, 'jsonrpc_server')) {
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
     * @param string $url url at which the xmlrpcserver responds
     * @param string $libUrl url of the jsxmlrpc lib (see ... docs for more info)
     * @param array $methodList list of methods to be wrapped into js (or null = wrap all)
     * @param string $protocol either 'xmlrpc' or 'jsonrpc'
     * @param string $jsLibsType either 'jsolait' or 'jsxmlrpc'
     * @return string the js code snippet defining js functions that wrap web services
     */
    public function wrapDispatchMap($dispatchMap, $url, $libUrl = '', $methodList = null, $protocol = 'xmlrpc', $jsLibsType = 'jsxmlrpc')
    {
        $out = '';
        $out .= $this->importLibs($libUrl, $jsLibsType);
        if ($methodList == null) {
            $methodList = array_keys($dispatchMap);
        } // be extra tolerant: a single method can be passed as string...
        else if (is_string($methodList)) {
            $methodList = array($methodList);
        }

        $out .= '<script type="text/javascript">
<!--
';
        foreach ($methodList as $method) {
            if (array_key_exists($method, $dispatchMap)) {
                $out .= $this->wrapXmlrpcMethod($method, $url, $libUrl, $protocol, $jsLibsType);
            }
        }
        $out .= '//-->
</script>
';
        return $out;
    }
}
