<?php

// Hackish code used to make the demos both viewable as source and runnable
if (isset($_GET['showSource']) && $_GET['showSource']) {
    $file = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['file'];
    highlight_file($file);
    die();
}

// support being installed both as top-level project and as dependency
if (file_exists(__DIR__ . '/../../../../../vendor/autoload.php')) {
    include_once __DIR__ . '/../../../../../vendor/autoload.php';
} else {
    include_once __DIR__ . '/../../vendor/autoload.php';
}

// Let unit tests run against localhost, 'plain' demos against a known public server
if (isset($_SERVER['HTTPSERVER'])) {
    define('XMLRPCSERVER', 'http://'.$_SERVER['HTTPSERVER'].'/demo/server/docServer.php');
} else {
    define('XMLRPCSERVER', 'http://gggeek.altervista.org/sw/xmlrpc/demo/server/server.php');
}
