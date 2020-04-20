### === Deploys with Jenkins ===

The Jenkins deployment configurations are located in `/dev/deploy/`.

## Overview

Jenkins is a trusty CI/CD playform that can be used for all sorts of automation. 
When using a Git based Managed Host, we often configure SquareOne for deploys with Jenkins. 
The following instructions will show how to configure Jenkins for both a Multi-branch pipeline deploy
for a GitOps flow, or a more traditional manual deploy setup. 

We like to use a combination, manual for our Dev environment so we can deploy any branch for QA testing, 
and multi-branch pipeline for a strict GitOps flow on Staging and Production.

## Requirements

* Latest Jenkins with the following plugins
    * Blue Ocean
    * CVS
    * docker-build-step
    * Git Parameter
    * Pipeline
    * Slack Notification
    * SSH Agent
* WordPress Host that uses Git based deployments (WPEngine, Pantheon, Etc)
* Ansible vault - used to encrypt server configs and access

## General Configuration

1. Jenkins needs to be authenticated with your CVS provider.
1. Make a `.dev/.vaultpass` file with a random string in it. This will be the encryption key used by Ansible Vault.
1. Run the `dev/init.sh` script to generate the initial required configurations.
1. Goto the `dev/deploy/.host/config` folder and configure all the .cfg files according to your CVS and host.
1. Run `dev/encrypt.sh` and commit only the encrypted files to the repo.
1. Setup the Host with the generated `dev/deploy/.host/config/ansible_rsa.pub` for Git Deploys.

Now you are ready to setup the Pipelines. Choose your style below:

* [Setup Jenkins Multi-branch Pipeline Deploys](jenkins-multi-branch-pipeline-deploys.md)
* [Setup Jenkins Manual Deploys](jenkins-manual-deploys.md)
