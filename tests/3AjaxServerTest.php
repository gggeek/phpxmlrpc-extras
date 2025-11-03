<?php

include_once __DIR__ . '/WebTestCase.php';

class AjaxServerTest extends ExtrasWebTestCase
{
    protected $defaultTarget = '?demo=server/ajaxdemo.php';

    public function testGet()
    {
        $page = $this->request();
    }

    public function testSumIntegers()
    {
        $response = $this->call('sumintegers', array(array(6, 7)));
        $this->assertEquals(13, $response);
    }
}
