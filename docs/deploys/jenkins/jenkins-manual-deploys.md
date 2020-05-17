# Jenkins Manual Deploys

The manual Jenkins pipeline deploys allow deployment of any branch to the dev environment.

## Configuration

1. Login to Jenkins and create a new Pipeline Deploy project
1. Setup you CVS to point at your project Repo.
1. Check "Do not allow concurrent builds"
1. Check "This project is parameterized"
1. Add a new Git Parameter with this info- Name: `BRANCH_NAME`, Description: `Which branch should be deployed ?`, Parameter Type: `branch`, Default Value: `server/dev`
1. Pipeline, add you repo info, set Mode to `Pipeline cript from SCM` and Script Path to `dev/deploy/JenkinsfileManual`. 
1. Save.

## Deploys
Simply click the play button to `Build with Parameters` and select the desired branch



