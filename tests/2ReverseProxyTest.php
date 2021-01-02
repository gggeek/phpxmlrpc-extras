<?php

include_once __DIR__ . '/WebTestCase.php';

class ReverseProxyTest extends WebTestCase
{
    protected $defaultTarget = '/demo/server/proxyServer.php';

    public function testGet()
    {
        $page = $this->request();
    }

    public function testAddTwoCall()
    {
        $response = $this->call('examples.addtwo', array(6, 7));
        $this->assertEquals(13, $response);
    }
}
