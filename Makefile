# Makefile for phpxmlrpc extras
# $Id: Makefile,v 1.13 2008/03/07 16:40:06 ggiunta Exp $

### USER EDITABLE VARS ###

# mkdir is a thorny beast under windows: make sure we can not use the cmd version, running eg. "make MKDIR=mkdir.exe"
MKDIR=mkdir


#### DO NOT TOUCH FROM HERE ONWARDS ###

export VERSION=0.5
# better alternative: try to recover version number from code
#export VERSION=$(shell egrep "\$GLOBALS *\[ *'xmlrpcVersion' *\] *= *'" xmlrpc.inc | sed -r s/"(.*= *' *)([0-9a-zA-Z.-]+)(.*)"/\\2/g )

MAINFILES=ChangeLog Makefile NEWS README docxmlrpcs.*

EPIFILES=xmlrpc_extension_api.inc \
 testsuite.php

AJAXFILES=ajaxmlrpc.inc \
 ajaxdemo.php \
 ajaxdemo2.php \
 sonofajax.php

JSONFILES=jsonrpc.inc \
 benchmark.php \
 jsonrpcs.inc \
 server.php \
 json_extension_api.inc \
 testsuite.php

PROXYFILES=proxyxmlrpcs.inc \
 proxyserver.php

WSDLFILES=schema.rnc \
 schema.rng \
 xmlrpc.wsdl \
 xmlrpc.xsd

ADODBFILES=*.* lib/*.* server/*.* drivers/*.*

all: install

install:
	@echo Please install by hand the needed components, copying the files into the appropriate directory
	cd doc && $(MAKE) install

dist:
	@echo ---${VERSION}---
	rm -rf extras-${VERSION}
	${MKDIR} extras-${VERSION}
	${MKDIR} extras-${VERSION}/ajax
	${MKDIR} extras-${VERSION}/jsonrpc
	${MKDIR} extras-${VERSION}/proxy
	${MKDIR} extras-${VERSION}/wsdl
	${MKDIR} extras-${VERSION}/adodb
	${MKDIR} extras-${VERSION}/adodb/drivers
	${MKDIR} extras-${VERSION}/adodb/lib
	${MKDIR} extras-${VERSION}/adodb/server
	${MKDIR} extras-${VERSION}/xmlrpc_extension_api
	cd ajax && cp --parents ${AJAXFILES} ../extras-${VERSION}/ajax
	cd jsonrpc && cp --parents ${JSONFILES} ../extras-${VERSION}/jsonrpc
	cd proxy && cp --parents ${PROXYFILES} ../extras-${VERSION}/proxy
	cd wsdl && cp --parents ${WSDLFILES} ../extras-${VERSION}/wsdl
	cd adodb && cp --parents ${ADODBFILES} ../extras-${VERSION}/adodb
	cd xmlrpc_extension_api && cp --parents ${EPIFILES} ../extras-${VERSION}/xmlrpc_extension_api
	cp ${MAINFILES} extras-${VERSION}
	cd doc && $(MAKE)
	find extras-${VERSION} -type f -exec dos2unix {} \;
	tar -cvf xmlrpc-extras-${VERSION}.tar extras-${VERSION}
	gzip xmlrpc-extras-${VERSION}.tar
	zip -r xmlrpc-extras-${VERSION}.zip extras-${VERSION}

clean:
	rm -rf extras-${VERSION}
	cd doc && $(MAKE) clean
