# Run Codeception tests

1. Go to the core-tests plugin.

	```
	cd wp-content/plugins/core-tests
	```

2. Run the desired test suite

	```
	vendor/bin/codecept run integration
	```

# Create new tests

1. Go to the core-tests plugin

	```
	cd wp-content/plugins/core-tests
	```
	
2. Generate the test class using the name of the target class

	```
	vendor/bin/codecept generate:wpunit integration "\Tribe\Project\Service_Providers\Post_Types\Post_Type_Service_Provider"
	```


##Table of Contents

* [Overview](/docs/tests/README.md)
* [Codeception](/docs/tests/codeception.md)
* [Jest](/docs/tests/jest.md)
