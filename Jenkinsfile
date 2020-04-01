pipeline {
    agent any

    environment {
        APP_NAME = "square-one"
        GIT_REPO = "moderntribe/${APP_NAME}.git"
        HOSTED_FOLDER = "./.HOSTED-SCM"
        GITHUB_TOKEN = credentials('tr1b0t-github-api-token')
        JENKINS_VAULTPASS = "${env.APP_NAME}-vaultpass"
        ENVIRONMENT_CONFIG = "./dev/deploy/.host/config/"
        SLACK_CHANNEL = 'nicks-playground'
    }

    stages {
         // BUILD
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

                        sh "echo ${env.BRANCH_NAME} | awk -F'/' '{print \$2}'"
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
        // DEPLOYMENT
        stage('Checkout Host SCM') {
            steps {
                // Decrypt values
                withCredentials([string(credentialsId: "${JENKINS_VAULTPASS}", variable: 'vaultPass')]) {
                    sh script: "echo '${vaultPass}' > ./.vaultpass", label: "Write vaultpass to local folder"
                    sh script: "ansible-vault decrypt ${env.ENVIRONMENT_CONFIG}.vaulted --output=${env.ENVIRONMENT_CONFIG} --vault-password-file ./.vaultpass", label: "Decrypt config config file"
                    sh 'rm ./.vaultpass'
                }

                // Load WP Engine environment variables
                loadEnvironmentVariables("${env.ENVIRONMENT_CONFIG}")

                // checkout scm WPEngine
                sshagent (credentials: ["${GIT_SSH_KEYS}"]) {
                  sh script: """
                    git clone ${env.deploy_repo} ${HOSTED_FOLDER}
                  """, label: "Git checkout Host SCM"
                }
            }
        }
        stage('Deploy') {
             steps {
                //sh script: "./dev/deploy/deploy.sh dev", label: "Deploy to Dev"
                sh "echo \'${DEPLOY_ENVIRONMENT}\'"
            }
        }
    }
    // POST TASKS
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

void loadEnvironmentVariables(path){
    def props = readProperties  file: path
    keys = props.keySet()
    for(key in keys) {
        value = props["${key}"]
        env."${key}" = "${value}"
    }
}
