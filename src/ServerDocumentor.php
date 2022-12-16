<?php

namespace PhpXmlRpc\Extras;

use PhpXmlRpc\PhpXmlRpc;
use PhpXmlRpc\Server;

class ServerDocumentor
{
    /** @var TemplateEngineInterface */
    protected $templateEngine;

    /**
     * @param $templateEngine
     */
    function __construct($templateEngine)
    {
        $this->templateEngine = $templateEngine;
        if ($templateEngine instanceof XmlrpcSmartyTemplate) {
            XmlrpcSmartyTemplate::setTemplates(static::templates());
        }
    }

    /**
     * Generate the documentation about methods exposed by a given server.
     * Note that it will NOT html-escape the user provided documentation (ie. risky).
     * @param Server $server
     * @param string $doctype type of documentation to generate: html (default), wsdl, etc...
     * @param string $lang language for docs
     * @param string $editorPath
     * @return string
     *
     * @todo add support for i18n of generated user-readable docs (eg html)
     * @todo make css link customizeable, as well as the title
     * @todo add customizeable favicon to the template
     * @todo move template to utf8
     */
    function generateDocs($server, $doctype = 'html', $lang = 'en', $editorPath = '')
    {
        $payload = '';
        switch ($doctype) {
            case 'wsdl':
                break;
            case 'html':
                // in case we have to send custom http headers, do it
                // removed from here, since we only return the payload now...
                //foreach ($template['httpheaders'] as $header)
                //    header($header);

                // method name decoding: is user seeking info about a single method?
                if (isset($_GET['methodName'])) {
                    $opts = array('lang' => $lang, 'title' => 'Method ' . htmlspecialchars($_GET['methodName']));
                    if ($editorPath != '') {
                        $mstart = $this->render('xmlrpcmethodstart', array('method' => htmlspecialchars($_GET['methodName'])));
                        $mend = $this->render('xmlrpcmethodend', array());
                        $opts['extras'] = $this->render('editorheaders', array('baseurl' => $editorPath, 'methodcallstart' => $mstart, 'methodcallend' => $mend));
                    } else
                        $opts['extras'] = '';
                    $payload .= $this->render('docheader', $opts);
                    if ($server->allow_system_funcs) {
                        $methods = array_merge($server->getDispatchMap(), $server->getSystemDispatchMap());
                    } else {
                        $methods = $server->getDispatchMap();
                    }
                    if (!array_key_exists($_GET['methodName'], $methods)) {
                        $payload .= $this->render('methodheader', array('method' => htmlspecialchars($_GET['methodName']), 'desc' => ''));
                        $payload .= $this->render('methodnotfound', array('method' => htmlspecialchars($_GET['methodName'])));
                    } else {
                        $payload .= $this->render('methodheader', array('method' => htmlspecialchars($_GET['methodName']), 'desc' => @$methods[$_GET['methodName']]['docstring']));
                        //$payload .= $this->render('methodfound']);
                        $minParams = -1;
                        for ($i = 0; $i < count($methods[$_GET['methodName']]['signature']); $i++) {
                            $val = $methods[$_GET['methodName']]['signature'][$i];
                            // NEW: signature_docs array, MIGHT be present - or not...
                            $doc = @$methods[$_GET['methodName']]['signature_docs'][$i];
                            if (!is_array($doc) || !count($doc)) {
                                $doc = array_fill(0, count($val), '');
                            }
                            $payload .= $this->render('sigheader', array('signum' => $i + 1));
                            $out = array_shift($val);
                            $outdoc = array_shift($doc);
                            if (count($val) < $minParams || $minParams < 0) {
                                $minParams = count($val);
                            }
                            for ($j = 0; $j < count($val); $j++) {
                                $payload .= $this->render('sigparam', array('paramtype' => $val[$j], 'paramdesc' => @$doc[$j]));
                            }
                            $payload .= $this->render('sigfooter', array('outtype' => $out, 'outdesc' => $outdoc, 'method' => htmlspecialchars($_GET['methodName'])));
                        }
                        $formParams = '';
                        for ($j = 0; $j < $minParams; $j++) {
                            $formParams .= $this->render('formparam');
                        }
                        if ($editorPath) {
                            $payload .= $this->render('methodfooter', array('method' => htmlspecialchars($_GET['methodName']), 'params' => $formParams, 'extras' => $this->render('editorlink', array())));
                        } else {
                            $payload .= $this->render('methodfooter', array('method' => htmlspecialchars($_GET['methodName']), 'params' => $formParams, 'extras' => ''));
                        }
                    }
                } else {
                    // complete api info
                    $payload .= $this->render('docheader', array('lang' => $lang, 'title' => 'API Index', 'extras' => ''));
                    $payload .= $this->render('apiheader');
                    foreach ($server->getDispatchMap() as $key => $val) {
                        $payload .= $this->render('apimethod', array('method' => $key, 'desc' => @$val['docstring']));
                    }
                    if ($server->allow_system_funcs) {
                        foreach ($server->getSystemDispatchMap() as $key => $val) {
                            $payload .= $this->render('apimethod', array('method' => $key, 'desc' => @$val['docstring']));
                        }
                    }
                    $payload .= $this->render('apifooter');
                }

                $payload .= $this->render('docfooter');

        }
        return $payload;
    }

    /**
     * @param string $templateName
     * @param mixed[] $params
     * @return string
     */
    protected function render($templateName, $params = array())
    {
        return $this->templateEngine->load($templateName)->render($params);
    }

    public static function templates()
    {
        return array(

//'httpheaders' => array(),

            'docheader' => '<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang}" lang="{$lang}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta http-equiv="Content-Script-Type" content="text-javascript" />
<meta name="generator" content="' . PhpXmlRpc::$xmlrpcName . '" />
<link rel="stylesheet" type="text/css" href="docxmlrpcs.css" />
{$extras}
<title>{$title}</title>
</head>
<body>',

            'docfooter' => '
<div class="footer">Generated using PHP-XMLRPC ' . PhpXmlRpc::$xmlrpcVersion . '</div>
</body></html>',

            'apiheader' => '
<h1>API index</h1>
<p>This server defines the following API specification:</p>
<table class="apilist">
<tr><th>Method</th><th>Description</th></tr>',

            'apimethod' => '
<tr><td><a href="?methodName={$method}">{$method}</a></td><td>{$desc}</td></tr>',

            'apifooter' => '
</table>',

            'methodheader' => '
<h1>Method <em>{$method}</em></h1>
<div>{$desc}</div>',

            'methodnotfound' => '
<h3>The method {$method} is not part of the API of this server</h3>
',

            'sigheader' => '
<h2>Signature {$signum}</h2>
<blockquote>
<h3>Input parameters</h3>
<table class="inputparameters">
<tr><th>Type</th><th>Description</th></tr>',

            'sigparam' => '
<tr><td>{$paramtype}</td><td>{$paramdesc}</td></tr>',

            'sigfooter' => '
</table>
<h3>Output parameter</h3>
<table class="inputparameters">
<tr><th>Type</th><th>Description</th></tr>
<tr><td>{$outtype}</td><td>{$outdesc}</td></tr>
</table>
</blockquote>',

            'formparam' => '&lt;param&gt;&lt;value&gt;&lt;/value&gt;&lt;/param&gt;
',

            'methodfooter' => '
<h2>Test method call</h2>
<p>Complete by hand the form below inserting the needed parameters to call this method.<br/>
For a string param use e.g. <pre>&lt;param&gt;&lt;value&gt;&lt;string&gt;Hello&lt;/string&gt;&lt;/value&gt;&lt;/param&gt;</pre></p>
<form action="" method="post"><p>
<textarea id="methodCall" name="methodCall" rows="5" cols="80">
&lt;methodCall&gt;&lt;methodName&gt;{$method}&lt;/methodName&gt;
&lt;params&gt;
{$params}&lt;/params&gt;
&lt;/methodCall&gt;
</textarea><br/>
{$extras}
<input type="submit" value="Test"/>
</p></form>',

            'editorheaders' => '<script type="text/javascript" src="{$baseurl}xmlrpc_lib.js"></script>
<!--<script type="text/javascript" src="{$baseurl}jsonrpc_lib.js"></script>-->
<script type="text/javascript">
<!--
function runeditor()
{
  //var url = "{$baseurl}visualeditor.html?params={$param_payload}";
  var url = "{$baseurl}visualeditor.html";
  //if (document.frmaction.wstype.value == "1")
  //  url += "&type=jsonrpc";
  var wnd = window.open(url, "_blank", "width=750, height=400, location=0, resizable=1, menubar=0, scrollbars=1");
}
// if javascript version of the lib is found, allow it to send us params
function buildparams(base64data)
{
  if (typeof base64_decode == "function")
  {
    if (base64data == "0") // workaround for bug in base64_encode...
      document.getElementById("methodCall").value = "{$methodcallstart}{$methodcallend}";
    else
      document.getElementById("methodCall").value = "{$methodcallstart}"+base64_decode(base64data)+"{$methodcallend}";
  }
}
//-->
</script>
',

            'editorlink' => '<input type="submit" value="Edit" onclick="runeditor(); return false;"/>',

            'xmlrpcmethodstart' => '<methodCall><methodName>{$method}</methodName>\n<params>\n',

            'xmlrpcmethodend' => '</params>\n</methodCall>',
        );
    }
}
