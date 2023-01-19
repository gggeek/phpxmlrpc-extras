PHPXMLRPC Extras
================

## DESCRIPTION
  A collection of server addons that might be of use for development of xml-rpc (and json-rpc) based applications

## REQUIREMENTS

  * PHP 5.3 or newer
  * phpxmlrpc/phpxmlrpc 4.6.0 or newer

## API DOCUMENTATION
  documentation can be found in the doc/ directory. _NB_ the docbook manual is quite outdated.

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

[![License](https://poser.pugx.org/phpxmlrpc/extras/license)](https://packagist.org/packages/phpxmlrpc/extras)
[![Latest Stable Version](https://poser.pugx.org/phpxmlrpc/extras/v/stable)](https://packagist.org/packages/phpxmlrpc/extras)
[![Total Downloads](https://poser.pugx.org/phpxmlrpc/extras/downloads)](https://packagist.org/packages/phpxmlrpc/extras)

[![Build Status](https://github.com/gggeek/phpxmlrpc-extras/actions/workflows/ci.yaml/badge.svg)](https://github.com/gggeek/phpxmlrpc-extras/actions/workflows/ci.yaml)
[![Code Coverage](https://codecov.io/gh/gggeek/phpxmlrpc-extras/branch/master/graph/badge.svg)](https://app.codecov.io/gh/gggeek/phpxmlrpc-extras)
