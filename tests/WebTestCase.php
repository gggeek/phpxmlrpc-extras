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
include_once __DIR__ . '/PolyfillTestCase.php';

use PhpXmlRpc\Client;
use PhpXmlRpc\Encoder;
use PhpXmlRpc\Request;
use PHPUnit\Extensions\SeleniumCommon\RemoteCoverage;

abstract class WebTestCase extends PolyfillXmlRpc_PolyfillTestCase
{
    protected $args = array();
    protected $testId;
    /** @var boolean $collectCodeCoverageInformation */
    protected $collectCodeCoverageInformation;
    protected $coverageScriptUrl;
    protected $baseUrl;
    protected $defaultTarget = '';
    /** @var Client $client */
    protected $client;

    public function set_up()
    {
        $this->args = argParser::getArgs();

        $this->baseUrl = $this->args['HTTPSERVER'] . '/' . ltrim($this->args['HTTPPREFIX'], '/') . $this->defaultTarget;
        $this->coverageScriptUrl = 'http://' . $this->args['HTTPSERVER'] . '/' . $this->args['HTTPPREFIX'] . '/tests/phpunit_coverage.php';

        $this->client = new Client($this->args['HTTPPREFIX'] . $this->defaultTarget, $this->args['HTTPSERVER'], 80);
        $this->client->setDebug($this->args['DEBUG']);
    }

    /**
     * Reimplemented to allow us to collect code coverage info from the target server.
     * Code taken from PHPUnit_Extensions_Selenium2TestCase
     *
     * @param TestResult $result
     * @return TestResult
     * @throws Exception
     *
     * @todo instead of overriding run via _run, try to achieve this by implementing Yoast\PHPUnitPolyfills\TestListeners\TestListenerDefaultImplementation
     */
    public function _run($result = NULL)
    {
        $this->testId = get_class($this) . '__' . $this->getName();

        if ($result === NULL) {
            $result = $this->createResult();
        }

        $this->collectCodeCoverageInformation = $result->getCollectCodeCoverageInformation();

        parent::_run($result);

        if ($this->collectCodeCoverageInformation) {
            $coverage = new RemoteCoverage(
                $this->coverageScriptUrl,
                $this->testId
            );
            $result->getCodeCoverage()->append(
                $coverage->get(), $this
            );
        }

        // do not call this before to give the time to the Listeners to run
        //$this->getStrategy()->endOfTest($this->session);

        return $result;
    }

    /**
     * Sends an http request, checks that the response does not contain usual php errors
     * @param string $uri
     * @param string $method
     * @param string $payload
     * @param false $emptyPageOk
     * @return bool|string
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
        if ($this->collectCodeCoverageInformation)
        {
            curl_setopt($ch, CURLOPT_COOKIE, 'PHPUNIT_SELENIUM_TEST_ID='.$this->testId);
        }
        if ($this->args['DEBUG'] > 0) {
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
        }
        $page = curl_exec($ch);
        curl_close($ch);

        $this->assertNotFalse($page);
        if (!$emptyPageOk) {
            $this->assertNotEquals('', $page);
        }
        $this->assertStringNotContainsStringIgnoringCase('Fatal error', $page);
        $this->assertStringNotContainsStringIgnoringCase('Notice:', $page);

        return $page;
    }

    protected function call($method, $params = array(), $faultOk = false)
    {
        $encoder = new Encoder();
        foreach($params as &$param) {
            $param = $encoder->encode($param);
        }
        $request = new Request($method, $params);
        $response = $this->client->send($request);
        if (!$faultOk) {
            $this->assertEquals(0, $response->faultCode());
        }
        return $encoder->decode($response->value());
    }
}
