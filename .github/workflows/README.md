# GitHub Workflows

## `phpcs.yml`

Runs the PHPCS sniffs from the root of your projects. Define your standards in the `phpcs.yml` file.

### Usage

In order to leverage this code-sniffing workflow, this project will need:

1. A `GH_TOKEN` secret added to the repo under the project's Settings > Secrets page. This should be an active GitHub oAuth token.
1. The following secrets are also needed: `WP_PLUGIN_ACF_KEY`, `WP_PLUGIN_GF_KEY`, `WP_PLUGIN_GF_TOKEN`. These should be in the Square1 .env file in 1password.

## `eslint.yml`

This GitHub Workflow grants tr1b0t the ability to sniff and inline comment on PRs where the changed JS code differs from the agreed upon standards.

### Usage

Great news! Since we have an `.eslintrc` file, this action is already enabled.

In order to leverage this code-sniffing workflow, this project will need:

1. A `GH_BOT_TOKEN` secret added to the repo under the project's Settings > Secrets page. The secret can be found in 1Password in the General Systems vault (search for `GH_BOT_TOKEN`).
1. A `.eslintrc` file in the root of the project that declares the standards that should be used.


## `deploy-{environment}.yml`

Runs a build and deployment of the application to the appropriate environment to a target Git Repository, in this case WPE. Each 
 environment is setup with pipeline deploys that will trigger automatically deploy when merged to the matching branch. `development`, `server/staging`, `server/production`. 
 Each deploy can also be triggered manually and have ANY branch deployed using the GitHub `workflow_dispatch` event. 

### Prerequisites

Multiple Secrets must be configured:
1. `DEV_DEPLOY_REPO` - the WPE git repository for the target environment.
2. `DEPLOY_PRIVATE_SSH_KEY` - an authorized SSH Private Key (the pub key is entered into the WPE control panel) with the target git repo
3. `GH_TOKEN` - This is an authorized github token for Composer installing packages from private/public repositories.
4. `COMPOSER_ENV` - These are required environment variables needed for Composer.

### Usage

To use:

* Auto deploy: Merge to the appropriate branch `development`, `server/staging`, `server/production`
* Manual deploy: 
## Login to GIthub, goto project repo, click "actions".
## Select the enviroment workflow you want to deploy.
## Click "run workflow" and select the desired branch you wish to deploy. 

 

