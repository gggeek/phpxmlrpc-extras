<?php
/******************************************************************************
 *
 * *** DEPRECATED ***
 *
 * This file is only used to insure backwards compatibility
 * with the previous API of the library
 *****************************************************************************/

include_once(__DIR__.'/../src/ReverseProxy.php');

use PhpXmlRpc\Extras\ReverseProxy;

class proxy_xmlrpc_server extends ReverseProxy
{
    function proxy_xmlrpc_server($client, $serviceNow = true)
    {
        parent::__construct($client, $serviceNow);
    }

    function do_proxy($m)
    {
        return $this->doProxy($m);
    }

    function acquire_server_methods($methodlist = null)
    {
        return $this->acquireServerMethods($methodlist);
    }
}
