WEB=/var/www/phpxmlrpc/doc

dist: xmlrpc_php_extras.xml
	-mkdir out
	@echo Jade cmd yet to be rebuilt, starting from xml file and putting output in ./out dir, e.g. jade -t xml -d custom.dsl xmlrpc_js.xml
	@echo convertdoc command for xmlmind xxe editor
	convertdoc docb.toHTML xmlrpc_php_extras.xml -u out
	mkdir -p ../extras-${VERSION}/doc
	-cp out/*.css ../extras-${VERSION}/doc
	-cp out/*.html ../extras-${VERSION}/doc
	-cp out/*.pdf ../extras-${VERSION}/doc
	cp xmlrpc_php_extras.xml ../extras-${VERSION}/doc
	cp Makefile ../extras-${VERSION}/doc

clean:
	-rm -f out/*.html
	-rm -f out/*.css

install:
	mkdir -p ${WEB}
	cp *.html ${WEB}
	cp *.css ${WEB}
