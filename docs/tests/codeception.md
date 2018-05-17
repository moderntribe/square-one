# Run Codeception tests

1. Go to the core-tests plugin.

	```
	cd wp-content/plugins/core-tests
	```
	
2. Generate composer requirements

    ```
    composer install --ignore-platform-reqs
    ```
    
3. Copy /tests-config-sample.php to /tests-config.php
 - You will want to create a blank tests database, usually projectname_tests
 - Adjust the database name in your tests-config.php file to the tests db

4. Run the desired test suite

	```
	vendor/bin/codecept run integration
	```

## Create new tests

1. Go to the core-tests plugin

	```
	cd wp-content/plugins/core-tests
	```
	
2. Generate the test class using the name of the target class

	```
	vendor/bin/codecept generate:wpunit integration "\Tribe\Project\Service_Providers\Post_Types\Post_Type_Service_Provider"
	```


## Table of Contents

* [Overview](/docs/tests/README.md)
* [Codeception](/docs/tests/codeception.md)
* [Jest](/docs/tests/jest.md)
