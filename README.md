PHPXMLRPC Extras
================

## DESCRIPTION
  A collection of server addons that might be of use for development of xml-rpc (and json-rpc) based applications


## REQUIREMENTS

  * PHP 5.4 or newer
  * phpxmlrpc/phpxmlrpc 4.10.1 or newer


## API DOCUMENTATION
  documentation can be found in the doc/ directory. _NB_ the manual is quite outdated.


## COPYRIGHT:
  Use of this software is subject to the terms in [license.txt](license.txt)

## INCLUDED MODULES

### AJAX
  Demo of ajaxified version of the  lib: supports executing xml-rpc/json-rpc calls directly from the client browser
  after having defined them only once, in php.

### DOCXMLRPCSERVER:
  Subclass of the xml-rpc server that auto-generates HTML documentation of exposed services.
  Easy as a breeze to use, and extremely user-friendly.

### PROXY:
  Subclass of the xml-rpc server that can act as remote (transparent) xml-rpc proxy to forward calls to a remote server.
  Can either forward any received call or probe remote server first for existing methods.


## Running tests

The recommended way to run the library test suite is via the provided Docker containers.
A handy shell script is available that simplifies usage of Docker.

The full sequence of operations is:

    ./tests/ci/vm.sh build
    ./tests/ci/vm.sh start
    ./tests/ci/vm.sh runtests
    ./tests/ci/vm.sh stop

    # and, once you have finished all testing related work:
    ./tests/ci/vm.sh cleanup

By default, tests are run using php 7.4 in a Container based on Ubuntu 20 Focal.
You can change the version of PHP and Ubuntu in use by setting the environment variables PHP_VERSION and UBUNTU_VERSION
before building the Container.

To generate the code-coverage report, run `./tests/ci/vm.sh runcoverage`

[![License](https://poser.pugx.org/phpxmlrpc/extras/license)](https://packagist.org/packages/phpxmlrpc/extras)
[![Latest Stable Version](https://poser.pugx.org/phpxmlrpc/extras/v/stable)](https://packagist.org/packages/phpxmlrpc/extras)
[![Total Downloads](https://poser.pugx.org/phpxmlrpc/extras/downloads)](https://packagist.org/packages/phpxmlrpc/extras)

[![Build Status](https://github.com/gggeek/phpxmlrpc-extras/actions/workflows/ci.yaml/badge.svg)](https://github.com/gggeek/phpxmlrpc-extras/actions/workflows/ci.yaml)
[![Code Coverage](https://codecov.io/gh/gggeek/phpxmlrpc-extras/branch/master/graph/badge.svg)](https://app.codecov.io/gh/gggeek/phpxmlrpc-extras)
