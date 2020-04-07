pipeline {
    agent any

    environment {
        APP_NAME = "square-one"
        GIT_REPO = "moderntribe/${APP_NAME}.git"
        BUILD_FOLDER = "./build"
        DEPLOY_FOLDER = "./deploy"
        GITHUB_TOKEN = credentials('tr1b0t-github-api-token')
        JENKINS_VAULTPASS = "${env.APP_NAME}-vaultpass"
        HOST_SSH_KEYS = "${env.APP_NAME}-ssh-key"
        SLACK_CHANNEL = 'squareone'
        ENVIRONMENT = environment()
    }

    stages {
        // SCM
        stage('Build SCM'){
           steps {
                echo "${env.BRANCH_NAME} - ${env.SLACK_CHANNEL} - ${env.ENVIRONMENT}"
                slackSend(channel: "${SLACK_CHANNEL}", message: "Pipeline: (<${RUN_DISPLAY_URL}|#${BUILD_NUMBER}>)\nDeployment of `${APP_NAME}` branch `${env.BRANCH_NAME}` to `${env.ENVIRONMENT}` STARTED.")
               // checkout scm
                checkout([$class: 'GitSCM',
                    branches: [[name: "${env.BRANCH_NAME}" ]],
                    extensions: [[$class: 'WipeWorkspace'], [$class: 'RelativeTargetDirectory',  relativeTargetDir: BUILD_FOLDER]],
                    userRemoteConfigs: [[url: "git@github.com:${env.GIT_REPO}", credentialsId: "jenkins-ssh-key"]]
                ])
            }
        }
        stage('Host SCM') {
            steps {
                // Decrypt values
                withCredentials([string(credentialsId: "${JENKINS_VAULTPASS}", variable: 'vaultPass')]) {
                    sh script: "echo '${vaultPass}' > ./.vaultpass", label: "Write vaultpass to local folder"
                    sh script: "ansible-vault decrypt ${env.BUILD_FOLDER}/dev/deploy/.host/config/${env.ENVIRONMENT}.cfg.vaulted --output=${env.BUILD_FOLDER}/dev/deploy/.host/config/${env.ENVIRONMENT}.cfg --vault-password-file ./.vaultpass", label: "Decrypt config config file"
                    sh 'rm ./.vaultpass'
                }

                // Load Host environment variables
                loadEnvironmentVariables("${env.BUILD_FOLDER}/dev/deploy/.host/config/${env.ENVIRONMENT}.cfg")

                // checkout Host SCM
                sshagent (credentials: ["${HOST_SSH_KEYS}"]) {
                  sh script: """
                    git clone ${env.deploy_repo} ${DEPLOY_FOLDER}
                  """, label: "Git checkout Host SCM"
                }
            }
        }

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
                        withCredentials([file(credentialsId: "square-one-compose-plugins-keys", variable: "ENV_FILE")]) {
                            dir(BUILD_FOLDER){
                                sh script: "cp $ENV_FILE .env", label: "Copy Composer .env to the root folder"
                                sh "composer config -g github-oauth.github.com ${GITHUB_TOKEN}"
                                sh script:  "composer install --ignore-platform-reqs --no-dev", label: "Composer install"
                                sh "rm .env"
                            }
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
                        dir(BUILD_FOLDER){
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
        }
        // DEPLOYMENT
        stage('Deploy') {
             steps {
                sh script: """
                  rsync -rpv --delete ${env.BUILD_FOLDER}/wp/ ${env.DEPLOY_FOLDER} \
                    --exclude=.git \
                    --exclude=.gitmodules \
                    --exclude=.gitignore \
                    --exclude=.htaccess \
                    --exclude=wp-config.php \
                    --exclude=wp-content

                  rsync -rpv --delete ${env.BUILD_FOLDER}/wp-content ${env.DEPLOY_FOLDER} \
                    --exclude=.git \
                    --exclude=.gitmodules \
                    --exclude=.gitignore \
                    --exclude=.htaccess \
                    --exclude=.babelrc \
                    --exclude=.editorconfig \
                    --exclude=.eslintrc \
                    --exclude=dev \
                    --exclude=dev_components \
                    --exclude=docs \
                    --exclude=gulp_tasks \
                    --exclude=node_modules \
                    --exclude=wp-content/object-cache.php \
                    --exclude=wp-content/plugins/core/assets/templates/cli

                  rsync -rpv ${env.BUILD_FOLDER}/ ${env.DEPLOY_FOLDER} \
                    --include=build-process.php \
                    --include=vendor/*** \
                    --exclude=*
                """, label: "Sync files to Deploy git directory"

                  sshagent (credentials: ["${HOST_SSH_KEYS}"]) {
                    // Host Git deploy
                    dir(DEPLOY_FOLDER){
                        sh script: """
                          git add -Av
                          git commit --allow-empty -m 'Deploying ${env.BRANCH_NAME} to ${env.ENVIRONMENT}'
                          git push origin master
                        """, label: "Host Git Deploy"
                    }
                  }
            }
        }
    }
    // POST TASKS
    post {
        always {
            cleanWs()
        }
        failure {
            slackSend(channel: "${SLACK_CHANNEL}", color: 'danger', message: "Pipeline: (<${RUN_DISPLAY_URL}|#${BUILD_NUMBER}> | ${env.domain})\nDeploying `${APP_NAME}` branch `${env.BRANCH_NAME}` to `${env.ENVIRONMENT}` FAILED.")
        }
        success {
            slackSend(channel: "${SLACK_CHANNEL}", color: 'good', message: "Pipeline: (<${RUN_DISPLAY_URL}|#${BUILD_NUMBER}> | ${env.domain})\nDeployment of `${APP_NAME}` branch `${env.BRANCH_NAME}` to `${env.ENVIRONMENT}` was SUCCESSFUL.")
        }
    }
    options {
        skipDefaultCheckout()
        disableConcurrentBuilds()
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

def environment(){
    final afterLastSlash = env.BRANCH_NAME.substring(env.BRANCH_NAME.lastIndexOf('/') + 1, env.BRANCH_NAME.length())
    return afterLastSlash
}
