# Deployment

The Jenkins deployment configurations are located in `/dev/deploy/`.

## Overview

Jenkins is a trusty CI/CD playform that can be used for all sorts of automation. 
When using a Git based Managed Host, we often configure SquareOne for deploys with Jenkins. 
The following instructions will show how to configure Jenkins for both a Multi-branch pipeline deploy
for a GitOps flow, or a more traditional manual deploy setup. 

We like to use a combination, manual for our Dev environment so we can deploy any branch for QA testing, 
and multi-branch pipeline for a strict GitOps flow on Staging and Production.

### Requirements

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

### General Configuration

1. Jenkins needs to be authenticated with your CVS provider.
1. Make a `.dev/.vaultpass` file with a random string in it. This will be the encryption key used by Ansible Vault.
1. Run the `dev/init.sh` script to generate the initial required configurations.
1. Goto the `dev/deploy/.host/config` folder and configure all the .cfg files according to your CVS and host.
1. Run `dev/encrypt.sh` and commit only the encrypted files to the repo.
1. Setup the Host with the generated `dev/deploy/.host/config/ansible_rsa.pub` for Git Deploys.

Now you are ready to setup the Pipelines. Choose your style below:


## Jenkins Manual Deploys

The manual Jenkins pipeline deploys allow deployment of any branch to the dev environment.

### Configuration

1. Login to Jenkins and create a new Pipeline Deploy project
1. Setup you CVS to point at your project Repo.
1. Check "Do not allow concurrent builds"
1. Check "This project is parameterized"
1. Add a new Git Parameter with this info- Name: `BRANCH_NAME`, Description: `Which branch should be deployed ?`, Parameter Type: `branch`, Default Value: `server/dev`
1. Pipeline, add you repo info, set Mode to `Pipeline cript from SCM` and Script Path to `dev/deploy/JenkinsfileManual`. 
1. Save.

### Deploys
Simply click the play button to `Build with Parameters` and select the desired branch


## Jenkins Multi-branch pipeline Deploys

### Configuration

1. Login to Jenkins and create a new Multi-Branch Pipeline Deploy project
2. Setup you CVS to point at your project Repo.
3. Build strategies, include only server branches `server/*`
4. Build configuration, set Mode to `by Jenkinsfile` and Script Path to `dev/deploy/JenkinsfilePipeline`
5. Scan Repository Triggers, set to a reasonable interval. This is how often a deploy will trigger if 
changes to a server branch are detected.
6. Save.

### Deploys
After the initial repository scan is complete, you will see a list of the server 
branches that exist. You can now manually trigger a build by clicking the play button 
next to it, or simply push code to the branch.



