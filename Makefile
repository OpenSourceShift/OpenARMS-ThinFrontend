build: overwrite-variables update-bootstrap
	@echo "Building the OpenARMS thin frontend."
	recess --compile ./less/main.less > ./css/main.css
	recess --compress ./less/main.less > ./css/main.min.css

update-bootstrap:
	cd bootstrap && rm -rf bootstrap/bootstrap && make bootstrap
	cp -f bootstrap/bootstrap/css/*.css ./css
	cp -f bootstrap/bootstrap/js/*.js ./js
	cp -f bootstrap/bootstrap/img/* ./img
	@echo "All done ... "

overwrite-variables:
	cp -f less/variables.less bootstrap/less/
