# Deployment

SquareOne has built in orchestration for deployments using GitHub Actions located in the `.github/workflows/` folder.

## Overview

GitHub Actions is a CI/CD platform that can be used for all sorts of automation. 
When using a Git based Managed Host, we often configure SquareOne for deploys with GitHub actions. 
The following instructions will show how to configure GitHub actions to support manual or GitOps flow deployments.
It will also show how to deploy to a standard WordPress Managed Host, or a Herokiush Host.

## Requirements

* A SquareOne based repository on GitHub.
* GitHub Account with GitHub Actions activated. For public repositories this is free, for private you require a 
  paid organization account.

## Managed Host Git Based Deployments

### IMPORTANT
* It's highly recommended setting up the Dev environment first and backing up prior to the first deploy.
* This deployment workflow assumes WordPress is in the root folder of the project.
* This deployment workflow assumes you have a host with Git Based deployments.

### General Configuration
1. Ensure Actions are enabled for your Org and for the repository
1. Create a branch for each environment. Recommended `server/*`, like `server/dev`, `server/prod`, etc. 
   Make sure each branches code matches what you want on the target environment.
1. Setup Repository Secrets:
    1. DEV_DEPLOY_REPO = The git repository address (in ssh format)
    1. COMPOSER_ENV = License Keys required for some premium plugins installed via composer.
    1. DEPLOY_PRIVATE_SSH_KEY = The Private SSH key configured on your target host.

### How to Manually Deploy

Make sure the workflow is configured for the `on.workflow_dispatch` trigger.

```yaml
on:
  workflow_dispatch:
```

1. Goto `https://github.com/{your-org}/{your-repo}}/actions/workflows/deploy-{env}.yml` for the desired environment. For example, if I want
   to deploy to the Dev server I would choose the `deploy-dev` workflow.
1. Click the `Run Workflow` dropdown, chose the correct server branch you wish to deploy to the selected workflow.  For example, if I want
   to deploy to the Dev server I would choose the `server/dev` branch.
1. Click the `Run Workflow` button. The SquareOne project will now build and deploy the artifact to your environment.

### How to GitOps Deploy

Make sure the workflow is configured for the `on.branches.push` trigger.

```yaml
on:
  push:
    branches:
      - server/dev
```

1. Merge or commit to the `server/dev` branch. A deployment will now run. Example: `git commit --allow-empty -m "Deploy Dev"`

## Dokku Based Deployments

Heroku is very popular Hosting PaaS provider. Many "App engines" have now modeled themselves on how Heroku has configured their systems,
hence the term Herokuish. We use Dokku (an open source alternative) in some cases, so we've built in full Herokuish support into SQ1. 

If you want to use a Herokuish setup, you are probably familiar with how it works, so we won't cover it in detail here. 
But in broad strokes you'll need the php and nodejs buildpacks and then reference our local install docs for any required config. 
From there, we bundle a Procfile and the needed Nginx and PHP config. If you want WP-CLI installed on your web service, add our custom [SquareOne buildpack](https://github.com/moderntribe/heroku-buildpack-sq1)
can do that for you. Because SQ1 is WordPress it requires you set up your own DB service and optional object caching service. Then all WP config
variables are wired to pull from Environment variables you can configure on your Herokuish provider.


