<?php

include_once __DIR__ . '/WebTestCase.php';

class AjaxServerJsonRpcTest extends ExtrasWebTestCase
{
    protected $defaultTarget = '?demo=server/ajaxdemo.php&proto=jsonrpc';

    public function testGet()
    {
        $page = $this->request();
    }

    public function testSumIntegers()
    {
        $response = $this->call('sumintegers', array(array(6, 7)), false, true);
        $this->assertEquals(13, $response);
    }
}
