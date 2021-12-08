# Deployment

SquareOne has built in orchestration for deployments using GitHub Actions located in the `.github/workflows/` folder.

> Note: How deployments work will depend on server environments, but the process to deploy will be the same.

## Overview

GitHub Actions is a CI/CD platform that can be used for all sorts of automation. 
When using a Git based Managed Host, we often configure SquareOne for deploys with GitHub actions. 
The following instructions will show how to configure GitHub actions to support manual or GitOps flow deployments.
It will also show how to deploy to a standard WordPress Managed Host, or a Herokiush Host.

## Requirements

* A SquareOne based repository on GitHub.
* GitHub Account with GitHub Actions activated. For public repositories this is free, for private you require a 
  paid organization account.

## Git Based Deployments

> IMPORTANT - Always back up critical environments prior to a deploy

### General Configuration
1. Ensure Actions are enabled for your Org and for the repository
1. Make sure any required secrets are configured. Refer to the Workflows.

### How to Deploy

1. Goto the GitHib Actions for the desired project. Then selece the desired workflow. For example, if I want
   to deploy to the Dev server I would choose the `deploy-dev` workflow.
1. Click the `Run Workflow` dropdown, chose the correct server branch you wish to deploy to the selected workflow.  For example, if I want
   to deploy to the Dev server I could choose the `server/dev` branch or any other desired feature branch.
1. Click the `Run Workflow` button. The project will now build and deploy the artifact to your selected workflow environment.
