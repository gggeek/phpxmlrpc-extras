<?php

class extrasArgParser
{
    /**
     * @todo actually grab the args from the environment
     * @return array
     */
    public static function getArgs()
    {
        /// @todo should we prefix all test parameters with TESTS_ ?
        $args = array(
            'DEBUG' => 0,
            'HTTPSERVER' => 'localhost',
            'HTTPPREFIX' => null,
            // now that we run tests in Docker by default, with a webserver set up for https, let's default to it
            'HTTPSSERVER' => 'localhost',
            'HTTPSPREFIX' => null,
            // example alternative:
            //'HTTPSSERVER' => 'gggeek.altervista.org',
            //'HTTPSPREFIX' => '/sw/phpxmlrpc/extras',
            'HTTPSIGNOREPEER' => false,
            'HTTPSVERIFYHOST' => 2,
            'SSLVERSION' => 0,
        );

        return $args;
    }
}
