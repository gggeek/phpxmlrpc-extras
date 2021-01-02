<?php

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

abstract class PolyfillXmlRpc_PolyfillTestCase extends TestCase
{
    public function _run($result = null) {
        return parent::run($result);
    }

    public static function _fail() {}

    public function run(PHPUnit_Framework_TestResult $result = null) {
        return $this->_run($result);
    }

    public static function fail($message = '') {
        static::_fail($message);
        self::fail($message);
    }
}
