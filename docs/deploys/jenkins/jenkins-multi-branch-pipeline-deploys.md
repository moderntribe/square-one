# Jenkins Multi-branch pipeline Deploys

## Configuration

1. Login to Jenkins and create a new Multi-Branch Pipeline Deploy project
2. Setup you CVS to point at your project Repo.
3. Build strategies, include only server branches `server/*`
4. Build configuration, set Mode to `by Jenkinsfile` and Script Path to `dev/deploy/JenkinsfilePipeline`
5. Scan Repository Triggers, set to a reasonable interval. This is how often a deploy will trigger if 
changes to a server branch are detected.
6. Save.

## Deploys
After the initial repository scan is complete, you will see a list of the server 
branches that exist. You can now manually trigger a build by clicking the play button 
next to it, or simply push code to the branch.



