## XML-RPC for PHP EXTRAS version 1.0-beta2 - 2023/1/19

- improved integration of ServerDocumentor with recent jsxmlrpc lib versions

## XML-RPC for PHP EXTRAS version 1.0-beta1 - 2023/1/19

- bumped the minimum required version of the base phpxmlrpc library to 4.6
- ported the Ajax module to contemporary php, phpxmlrpc and jsxmlrpc. Replaced usage of javascript `alert` with `console.log`
- updated the self-documenting server to generate html-5 in utf8 (was using xhtml, iso-8859-1 previously)
- removed the WSDL schemas - they now reside at https://github.com/gggeek/xmlrpc-schemas and can be installed via Composer
  as package "phpxmlrpc/schemas"
- removed the ADOdb module - it now resides at https://github.com/gggeek/adodb-xmlrpc-proxy and can be installed via Composer
  as package "phpxmlrpc/adodb-xmlrpc-proxy"
- fixed the test container to include git, as it is required to make tests pass
- converted the remaining source/docs files to utf8
- moved from Travis to GitHub Actions for testing; test on all php versions from 5.4 to 8.2

## XML-RPC for PHP EXTRAS version 1.0-alpha - 2021/1/3

Major changes this time around!
- bumped the minimum required version of the base phpxmlrpc library to 4.5 / php to 5.3
- externalised two components (JSONRPC and EXTENSION-API) to their own, separate packages: phpxmlrpc/jsonrpc and phpxmlrpc/polyfill-xmlrpc
- introduction of php namespaces, class autoloading, Composer for dependency management
- moved the demo files into their own, separate directory
- introduced phpunit, docker and travis for testing
- moved away from Makefiles

As always with the phpxmlrpc libraries, the `.inc` files of old are still provided, insuring full compatibility
for developers who rely on the previous APIs (classes, functions, global variables)

Still to be ported to modern-day coding standards: adodb and ajax components, as well as the docs


## XML-RPC for PHP EXTRAS version 0.6.3 - 2020/12/23

* Fix an error when emulating xmlrpc_set_type


## XML-RPC for PHP EXTRAS version 0.6.2 - 2017/12/2

* Fix class autoloading when trying to install the package via Composer (thanks gmpf)


## ML-RPC for PHP EXTRAS version 0.6.1 - 2017/10/29

* Security fix: avoid one XSS in class documenting_xmlrpc_server


## XML-RPC for PHP EXTRAS version 0.6 - 2017/3/14

* Accept non-standard options in calls to xmlrpc_encode_request

* Reformat most php code and minor doc improvements


## XML-RPC for PHP EXTRAS version 0.5.2 - 2014/12/7

Only one change for this release - fix class autoloading for composer


## XML-RPC for PHP EXTRAS version 0.5.1 - 2014/12/6

Only one change for this release - but an important one:

* security fix for an XSS problem in docxmlrpcs.inc.
  All users of the documenting_xmlrpc_server class are urged to upgrade asap.


## XML-RPC for PHP EXTRAS version 0.5 - 2009/09/05

This is the first release of the library to only support PHP 5.
The corresponding version of the base library is 3.0.0 beta

Quite a few changes and bugfixes have been made in the are of json support:
* improved: catch exceptions thrown during execution of php functions exposed as methods by the jsonrpc server
* fixed: fix bad encoding if same object is encoded twice using php_jsonrpc_encode
* improved: json_extension_api.php: added json_last_error() function and the constants defined in php 5.3.0
* improved: allow 'mixed type' jsonrpc servers (see description in the xmlrpc server docs)
* improved: allow 'phpvals' jsonrpc servers to do type checking on incoming requests
* fixed: a missing 'new' call when building string vals in php_jsonrpc_encode
* fixed: encoding of UTF8 chars outside of the BMP
* fixed: encoding of '/' chars in jsonrpc when source is in UTF8


## XML-RPC for PHP EXTRAS version 0.4 - 2007/02/25

The fourth release of the phpxmlrpc extras package brings minor new features
and some bugfixes:

* DOCXMLRPCS gets a bugfix for php installs where magic_quotes_gpc is set and an
  option to take advantage of a visual editor for xmlrpc values (the editor being
  part of the jsxmlrpc package, it has to be downloaded separately)

* the usual lot of bugfixes for JSONRPC: slightly faster handling of data which
  is internally encoded as UTF-8; support booleans, strings, null as valid id
  in requests and responses; correctly add quotes when serializing datetimes;
  correct handling of charset declaration in http headers; switched the declared
  content-type from 'text/plain' to 'application/json' as per the proposed rfc;
  modify json_extension_api.inc to follow php 445/521 semantics

* added in AJAXMLRPC a new server class that takes advantage of ths jsxmlrpc lib
  for the generated javascript code (instead of jsolait)

* one bugfix in XMLRPC_EXTENSION_API


## XML-RPC for PHP EXTRAS version 0.3 - 2006/11/23

The third release of the  phpxmlrpc extras package brings the usual lot of
bugfixes and new capabilities:

Lots of bugfixes in the json code, leading to improved serialization and parsing.

A real manual. Still quite incomplete, but way better than naught.

JSON_EXTENSION_API - building on the jsonrpc classes, this new file provides
a drop-in replacement for the php native json extension. Applications that use
the php json extension can include this file to run unaltered on php installs where
the extension is missing


## XML-RPC for PHP EXTRAS version 0.2 - 2006/09/08

I'm pleased to announce the second release of the phpxmlrpc extras package.

Besides some bugfixing and improvements, two very interesting new packages have
been added:

XMLRPC_EXTENSION_API - a replacement for the php native xmlrpc extension, written
in 100% pure php. Typical use case: enabling a php application written to make
use of the php native xmlrpc extension to run also on webservers where the extension
is not / can not be installed.

ADODB - designed to provide a flexible and easy-to-use database-to-webservice
conversion mechanism. Based on the adodb library by John Lim, it includes conversion
functions, server component and meta-db-driver that allows transparent access to
remote databases over http.

Enhancements to existing packages include:

JSONRPC: better handling of fault response; always quote request/response member
names; fix php_jsonrpc_encode() of null values; fix serialize_jsonrpcval for
jsonrpcvals that have php_class set; add benchmark test file; added new function
php_jsonrpc_decode_json(); client properly decodes debug info sent by server;
fix reception of encoded unicode characters

DOCXMLRPCS: added capability to document single parameters of xmlrpc methods;
changed server::service() method to match modified base library

The php xmlrpc base library version 2.1 or later is needed for these package to
run.


## XML-RPC for PHP EXTRAS version 0.1 - 2006/04/24

I'm pleased to announce the initial release of the phpxmlrpc extras package.

The documentation is quite scarce (read: nonexistent), but the code is in pretty good shape.

Included packages are:

WSDL:
----------------------------------------
The completely UNOFFICIAL DTD and RELAX NG schemas to validate your xmlrpc against.
Might be useful in defining some wsdl file describing xmlrpc services (good luck!!!).
The DTD is not quite accurate, due to limitations in the definition language.
RELAX NG should be 100% precise and accurate.

DOCXMLRPCS:
----------------------------------------
Subclass of xmlrpc server that self-generates HTML documentation of exposed services.
Easy as a breeze to use, and extremely user-friendly.

PROXY:
----------------------------------------
Subclass of xmlrpc server that can act as remote (transparent) xmlrpc proxy to forward calls to a remote server.
Can either forward any received call or probe remote server first for existing methods.

JSONRPC:
----------------------------------------
NEW!!!
Support for this brand new trendy protocol, 100% buzzword-compliant and ajax-ready.
Client and server classes provided.
Makes it very easy to build a server that supports both protocols at the same time.
Original JSON parsing code from Michal Migurski (whose lib is now officially part of PEAR).

AJAX:
-----------------------------------------
Demo of ajaxified version of xmlrpc lib: supports executing xmlrpc/jsonrpc calls
directly from the client browser.
Needs the excellent jsolait lib from http://jsolait.net/ (thanks Jan Kollhof)
