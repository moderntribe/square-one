pipeline {
    agent any

    environment {
        APP_NAME = "square-one"
        GIT_REPO = "moderntribe/${APP_NAME}.git"
        GITHUB_TOKEN = credentials('tr1b0t-github-api-token')
        HOSTED_SSH_KEYS = "${env.APP_NAME}-ssh-key"
        HOSTED_FOLDER = "./.HOSTED-SCM"
        SLACK_CHANNEL= "nicks-playground"
        DEPLOY_TO = deploy_to()
    }

    stages {
        stage('Build Processes') {
            parallel {
                stage('Composer') {
                    agent {
                        docker {
                            image 'composer:1.8'
                            args '-u root'
                            reuseNode true
                        }
                    }
                    steps {
                        echo "${env.BRANCH_NAME} - ${params.SLACK_CHANNEL}"
                        slackSend(channel: "${SLACK_CHANNEL}", message: "Pipeline: Deployment of `${APP_NAME}` to `${env.BRANCH_NAME}` STARTED: (build: <${RUN_DISPLAY_URL}|#${BUILD_NUMBER}>)")
                        withCredentials([file(credentialsId: "square-one-compose-plugins-keys", variable: "ENV_FILE")]) {
                            sh script: "cp $ENV_FILE .env", label: "Copy Composer .env to the root folder"
                            sh "composer config -g github-oauth.github.com ${GITHUB_TOKEN}"
                            sh script:  "composer install --ignore-platform-reqs --no-dev", label: "Composer install"
                            sh "rm .env"
                        }
                    }
                }

                stage('Node') {
                    agent {
                        docker {
                            image 'node:12.13.1-alpine'
                            args '-u root'
                            reuseNode true
                        }
                    }

                    steps {
                        // Install dependencies
                        sh 'apk add --no-cache git openssh'
                        sh 'npm install -g gulp-cli'

                        sh 'yarn install'
                        sh 'cp local-config-sample.json local-config.json'
                        sh 'gulp server_dist'

                        // Clean Up before packaging
                        sh 'rm -rf node_modules'

                        // Jenkins as owner
                        sh 'chown -R 110:117 .'
                    }
                }
            }
        }

        stage('Deploy') {
            when{
                branch: "epic/ff/s1/server"
                environment name: 'DEPLOY_TO', value: 'develop'
            }
        }
    }

    post {
        always {
            cleanWs()
        }
        failure {
            slackSend(channel: "${SLACK_CHANNEL}", color: 'danger', message: "Pipeline: Deploying `${APP_NAME}` branch `${env.BRANCH_NAME}` to `${env.DEPLOY_TO}` FAILED: (build: <${RUN_DISPLAY_URL}|#${BUILD_NUMBER}>)")
        }
        success {
            slackSend(channel: "${SLACK_CHANNEL}", color: 'good', message: "Pipeline: Deployment of `${APP_NAME}` branch `${env.BRANCH_NAME}` to `${env.DEPLOY_TO}` was SUCCESSFUL. (build: <${RUN_DISPLAY_URL}|#${BUILD_NUMBER}>)")
        }
    }
}

def deply_to(){

    string deploy_to_environment = 'develop'

    switch( BRANCH_NAME ) {
        case: 'server/staging':
            deploy_to_environment = 'staging'
            break;
        case: 'server/production'
            deploy_to_environment = 'production'
            break;
        default:
            deploy_to_environment = 'develop'
            break;
    }
    return deploy_to_environment
}
