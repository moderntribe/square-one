# TravisCI

SquareOne includes a configuration file for continuous integration with TravisCI. Access the [TravisCI Dashboard](https://travis-ci.com/dashboard) to view your build progress.

## Setting up a new project

The first time you log into TCI, you'll need to use your personal GitHub account to authorize access. After doing so, you'll see a dashboard of projects across Tribe.

Setting up TravisCI for your project requires you to set three environmental variables: 

* `CI_USER_TOKEN`: A GitHub oAuth token. Please see [Creating an oAuth app](https://developer.github.com/apps/building-oauth-apps/creating-an-oauth-app/) for instructions on how to do this.
* `WP_PLUGIN_ACF_KEY`: The product key for ACF used by composer to install the plugin. Find this in 1Password.
* `WP_PLUGIN_GF_KEY`: The product key for GravityForms used by composer to install  the plugin. Find this in 1Password.

To set these up, navigate to your project's CI page (it should have a URL like this: https://travis-ci.com/moderntribe/your-amazing-project), and click on More Options-> Settings in the top right.

Finally, find the Environment Variables section and add these one-by-one. Be sure not to display these in the build log!

## Adding other environmental variables

You may find you need to use other env variables in your Docker containers. To add these, you will need to follow the steps in [the section above](#setting-up-a-new-project), and then make them available to Docker Compose.

To do that, find your project's `docker-compose.yml` file in `dev/docker/`, and add them to the relevant container's `environment` entry, as shown:

```yaml
...
environment:
    - SOME_CONST=${SOME_CONST:-''}
...
```

_Note: The syntax `:-''` tells Compose that if the variable is not set, use an empty string. Replace this with whatever default value you'd like._