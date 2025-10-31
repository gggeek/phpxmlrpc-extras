<?php

include_once __DIR__ . '/WebTestCase.php';

use \PhpXmlRpc\Request;

class SelfDocumentingServerTest extends ExtrasWebTestCase
{
    protected $defaultTarget = '?demo=server/docServer.php';

    public function testListMethods()
    {
        $page = $this->request();
        $this->assertStringContainsString('API index', $page);
        $this->assertStringContainsString('examples.addtwo', $page);
        $this->assertStringContainsString('Add two integers together and return the result', $page);
    }

    public function testAddTwoSignature()
    {
        $page = $this->request('&methodName=examples.addtwo');
        $this->assertStringContainsString('Add two integers together and return the result', $page);
        $this->assertStringContainsString('Signature 1', $page);
    }

    /*public function testAddTwoPostRequest()
    {
        $page = $this->request('', 'POST', '');
        $this->assertContains('', $page);
    }*/

    public function testAddTwoCall()
    {
        $response = $this->call('examples.addtwo', array(6, 7));
        $this->assertEquals(13, $response);
    }
}
