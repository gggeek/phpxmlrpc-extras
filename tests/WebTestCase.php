<?php
/**
 * @author Gaetano Giunta
 * @copyright (c) 2020 G. Giunta
 * @license code licensed under the BSD License: see license.txt
 */

/**
 * NB: the testsuite is designed to be run with the native xmlrpc extension enabled.
 * It will _not_ fail if the extension is disabled, but it will of course not be validating API correspondence - just
 * that the API still works.
 */

include_once __DIR__ . '/parse_args.php';
include_once __DIR__ . '/../vendor/phpxmlrpc/phpxmlrpc/tests/WebTestCase.php';

use PhpXmlRpc\Client;
use PhpXmlRpc\Encoder;
use PhpXmlRpc\Request;

abstract class ExtrasWebTestCase extends PhpXmlRpc_WebTestCase
{
    protected $defaultTarget = '';
    /** @var Client $client */
    protected $client;
    protected $request_compression = null;
    protected $accepted_compression = '';

    public function set_up()
    {
        parent::set_up();

        $this->args = extrasArgParser::getArgs();

        // assumes HTTPURI to be in the form /tests/index.php?etc...
        $this->baseUrl = 'http://' . $this->args['HTTPSERVER'] . preg_replace('|\?.+|', '', $this->args['HTTPURI']) . $this->defaultTarget;
        $this->coverageScriptUrl = 'http://' . $this->args['HTTPSERVER'] . preg_replace('|/tests/index\.php(\?.*)?|', '/tests/phpunit_coverage.php', $this->args['HTTPURI']);

        $this->client = $this->newClient('');
    }

    /**
     * Sends an http request, checks that the response does not contain usual php errors
     * @param string $uri
     * @param string $method
     * @param string $payload
     * @param bool $emptyPageOk
     * @return bool|string
     *
     * @todo can we replace this with the parent call?
     */
    protected function request($uri = '', $method = 'GET', $payload = '', $emptyPageOk = false)
    {
        $url = $this->baseUrl . $uri;

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true
        ));
        if ($method == 'POST')
        {
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload
            ));
        }
        $cookie = 'PHPUNIT_RANDOM_TEST_ID=' . static::$randId;
        if ($this->collectCodeCoverageInformation)
        {
            $cookie .= '; PHPUNIT_SELENIUM_TEST_ID=' . $this->testId;
        }
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);

        if ($this->args['DEBUG'] > 0) {
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
        }
        $page = curl_exec($ch);
        $info = curl_getinfo($ch);
        if (PHP_MAJOR_VERSION < 8)
            curl_close($ch);

        $this->assertNotFalse($page, 'Curl request should not fail. Url: ' . @$info['url'] . ', Http code: ' . @$info['http_code']);
        if (!$emptyPageOk) {
            $this->assertNotEquals('', $page, 'Retrieved web page should not be empty');
        }
        $this->assertStringNotContainsStringIgnoringCase('Fatal error', $page, 'Retrieved web page should not contain a fatal error string');
        $this->assertStringNotContainsStringIgnoringCase('Notice:', $page, 'Retrieved web page should not contain a notice string');

        return $page;
    }

    protected function call($method, $params = array(), $faultOk = false, $useJsonRpc = false)
    {
        if ($useJsonRpc) {
            $encoder = new \PhpXmlRpc\JsonRpc\Encoder();
        } else {
            $encoder = new Encoder();
        }

        foreach($params as &$param) {
            $param = $encoder->encode($param);
        }
        if ($useJsonRpc) {
            $request = new \PhpXmlRpc\JsonRpc\Request($method, $params, 1);
            // work around the fact that the Parser might have been initialized by an xml-rpc request
            \PhpXmlRpc\JsonRpc\Request::setParser(new PhpXmlRpc\JsonRpc\Helper\Parser());
            $response = $this->newClient('', true)->send($request);
        } else {
            $request = new Request($method, $params);
            // work around the fact that the Parser might have been initialized by a json-rpc request
            Request::setParser(new PhpXmlRpc\Helper\XMLParser());
            $response = $this->client->send($request);
        }

        if (!$faultOk) {
            $this->assertEquals(0, $response->faultCode(), $response->faultString());
        }
        return $encoder->decode($response->value());
    }

    protected function newClient($path, $useJsonRpc = false)
    {
        if ($useJsonRpc) {
            $client = new \PhpXmlRpc\JsonRpc\Client($this->baseUrl . $path);
        } else {
            $client = new Client($this->baseUrl . $path);
        }

        $client->setCookie('PHPUNIT_RANDOM_TEST_ID', static::$randId);
        if ($this->collectCodeCoverageInformation) {
            $client->setCookie('PHPUNIT_SELENIUM_TEST_ID', $this->testId);
        }

        $client->setDebug(1);//$this->args['DEBUG']);
        $client->request_compression = $this->request_compression;
        $client->accepted_compression = $this->accepted_compression;

        return $client;
    }
}
