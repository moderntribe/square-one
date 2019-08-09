## Configurations

*Production*

When using this on a production environment, be sure to add the following to your local-config.php:
    define( 'ENVIRONMENT', 'PRODUCTION' );

*Development*

GLOMAR is a plugin that blocks the frontend of the site from public access. If you would like to disable the plugin locally, add the following to your local-config.php:
    define( 'TRIBE_GLOMAR', false );
