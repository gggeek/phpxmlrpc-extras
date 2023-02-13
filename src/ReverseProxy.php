<?php

namespace PhpXmlRpc\Extras;

use PhpXmlRpc\Encoder;
use PhpXmlRpc\PhpXmlRpc;
use PhpXmlRpc\Client;
use PhpXmlRpc\Request;
use PhpXmlRpc\Response;
use PhpXmlRpc\Server;
use PhpXmlRpc\Value;

class ReverseProxy extends Server
{
    /** @var Client $client */
    public $client = null;
    /** @var bool $execute_any_call */
    public $execute_any_call = true;

    /**
     * Override constructor: we always need a client object to be used for communicating with remote server, and have
     * no dispatch map of ours.
     *
     * @param Client $client
     * @param boolean $serviceNow
     */
    function __construct($client, $serviceNow = true)
    {
        // duplicate $client into $this->client, but use faster response decoding
        //if (version_compare(phpversion(), '5.0', 'ge'))
        //{
        //    $this->client = clone $client;
        //}
        //else
        //{
        $this->client = $client;
        //}
        $this->client->setOption(Client::OPT_RETURN_TYPE, 'xmlrpcvals');
        $this->client->setDebug(0);

        parent::__construct(null, $serviceNow);
    }

    public function doProxy($req)
    {
        $this->debugMsg('Forwarding method ' . $req->method() . ' to server ' . $this->client->server);
        return $this->client->send($req);
    }

    /**
     * Override execute function, to add support for both modes of operation:
     * 1 - proxy ANY call to remote server
     * 2 - proxy only selected methods to remote server
     *
     * @param mixed $req either a Request obj or a method name
     * @param array $params array with method parameters as php types (if m is method name only)
     * @param array $paramTypes array with xmlrpc types of method parameters (if m is method name only)
     * @return Response
     *
     * @throws \Exception
     */
    public function execute($req, $params = null, $paramTypes = null)
    {
        // this class of servers does not work in 'phpvals' mode
        if (!is_object($req))
            return new Response(0, PhpXmlRpc::$xmlrpcerr['server_error'],
                PhpXmlRpc::$xmlrpcstr['server_error'] . ': changing functions_parameters_type parameter of proxy server is not supported');

        if ($this->execute_any_call) {
            return $this->doProxy($req);
        } else {
            return parent::execute($req);
        }
    }

    /**
     * Add to dispatch map of this server some (or all) of the methods exposed by a remote server.
     *
     * @param string|string[] $methodList
     * @return int|false false on error, or the number of methods proxied
     *
     * @todo feature-creep allow to specify remote methods to proxy via a regexp
     */
    public function acquireServerMethods($methodList = null)
    {
        $encoder = new Encoder();

        if (is_string($methodList)) {
            $methodList = array($methodList);
        } else if ($methodList == null) {
            // retrieve complete list of methods from remote server
            $msg = new Request('system.listMethods', array());
            $methodList = $this->client->send($msg);
            if ($methodList->faultCode()) {
                return false;
            } else {
                $methodList = $encoder->decode($methodList->value());
            }
        }

        // for each remote method to be exposed, query the syntax
        $ok = 0;
        foreach ($methodList as $method) {
            // do not replicate remote system. methods
            if (strpos($method, 'system.') !== 0) {

                $msg = new Request('system.methodSignature', array(new Value($method)));
                $sig = $this->client->send($msg);
                if (!$sig->faultCode()) {
                    $sig = $encoder->decode($sig->value());
                    if (!is_array($sig)) // remote method sig: undefined
                    {
                        $sig = null;
                    }
                    // method sig OK, now query method desc
                    $msg = new Request('system.methodHelp', array(new Value($method)));
                    $desc = $this->client->send($msg);
                    if ($desc->faultCode()) {
                        $desc = '';
                    } else {
                        $desc = $desc->value();
                        $desc = $desc->scalarval();
                    }
                    $this->addToMap($method, array($this, 'doProxy'), $sig, $desc);
                    $ok++;
                }

            }
        }

        return $ok;
    }
}
