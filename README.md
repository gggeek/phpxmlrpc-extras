PHP-XMLRPC Extras
=================

## DESCRIPTION
  A collection of extensions, addons and other stuff that might be of use for development of xml-rpc (and jsonrpc, soap)
  based applications

## REQUIREMENTS

  * PHP 5.3 or newer
  * phpxmlrpc/phpxmlrpc 4.5.1 or newer

## API DOCUMENTATION
  HTML documentation can be found in the doc/ directory.

## COPYRIGHT:
  Use of this software is subject to the terms in [license.txt](license.txt) (except for the code in the adodb directory,
  which is dual licensed under BSD and LGPL)

## INCLUDED MODULES

### ADODB (WIP - not ported yet from old API):
  Provides an easy mean of connecting applications to remote databases using a web service protocol instead of a native
  database driver.

### AJAX (WIP - not ported yet from old API):
  Demo of ajaxified version of xmlrpc lib: supports executing xmlrpc/jsonrpc calls directly from the client browser.
  Needs the excellent jsolait lib from http://jsolait.net/ (thanks Jan Kollhof)

### DOCXMLRPCSERVER:
  Subclass of xmlrpc server that self-generates HTML documentation of exposed services.
  Easy as a breeze to use, and extremely user-friendly.

### PROXY:
  Subclass of xmlrpc server that can act as remote (transparent) xmlrpc proxy to forward calls to a remote server.
  Can either forward any received call or probe remote server first for existing methods.

[![License](https://poser.pugx.org/phpxmlrpc/extras/license)](https://packagist.org/packages/phpxmlrpc/extras)
[![Latest Stable Version](https://poser.pugx.org/phpxmlrpc/extras/v/stable)](https://packagist.org/packages/phpxmlrpc/extras)
[![Total Downloads](https://poser.pugx.org/phpxmlrpc/extras/downloads)](https://packagist.org/packages/phpxmlrpc/extras)

[![Build Status](https://github.com/gggeek/phpxmlrpc-extras/actions/workflows/ci.yaml/badge.svg)](https://github.com/gggeek/phpxmlrpc-extras/actions/workflows/ci.yaml)
[![Code Coverage](https://codecov.io/gh/gggeek/phpxmlrpc-extras/branch/master/graph/badge.svg)](https://app.codecov.io/gh/gggeek/phpxmlrpc-extras)
