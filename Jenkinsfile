pipeline {
  agent {
    docker {
      image 'node:12.13.1-alpine'
      args '-u root'
      reuseNode true
    }
  }
  parameters {
    gitParameter(branchFilter: 'origin/(.*)',
      defaultValue: "develop",
      name: 'BRANCH',
      type: 'PT_BRANCH',
      description: 'Which branch should be deployed ?')
    choice(name: 'DEPLOY_ENVIRONMENT',
      choices: 'null\ndev\nstaging\nproduction',
      description: 'To which environment should be deployed ?'
    )
    booleanParam(name: 'MANUAL_DEPLOY',
      defaultValue: false,
      description: 'Is this a Manual Deploy?'
    )
  }

  environment {
    APP_NAME = "square-one"
    SLACK_CHANNEL = "square-one"
    GIT_SSH_KEYS = "${env.APP_NAME}-ssh-key"
  }

  stages {

    stage('Slack message') {
      steps {
        script {
          env.REAL_BRANCH = "${params.MANUAL_DEPLOY == true ? params.BRANCH : env.BRANCH_NAME}"
          env.MSG_SLACK   = "${params.MANUAL_DEPLOY == true ? "to `${params.DEPLOY_ENVIRONMENT}` manual" : " "}"
        }
        // Debug
        echo "${params.DEPLOY_ENVIRONMENT} - ${params.BRANCH} - ${params.MANUAL_DEPLOY} - ${env.REAL_BRANCH} - ${env.MSG_SLACK}"

        slackSend(
          channel: "${SLACK_CHANNEL}",
          message: "`${APP_NAME}` deploy of branch `${env.REAL_BRANCH}` ${env.MSG_SLACK} started: (build: <${RUN_DISPLAY_URL}|#${BUILD_NUMBER}>)"
        )
      }
    }
    stage('Checkout code Github') {
      steps {
        checkout([$class: 'GitSCM',
            branches: [[name: "${env.REAL_BRANCH}" ]],
            extensions: [[$class: 'WipeWorkspace']],
            userRemoteConfigs: [[url: "${GIT_URL}", credentialsId: 'tr1b0t-github-api-token-as-user_password']]
        ])

        // Run the bootstrap before the parallel
        sh script: 'sh ./script/bootstrap', label: 'Running Bootstrap'
      }
    }

    stage('Build Processes') {
      parallel {
        stage('Composer') {
          steps {
            withCredentials([file(credentialsId: "square-one-compose-plugins-keys", variable: "ENV_FILE")]) {
                sh script: "cp $ENV_FILE .env", label: "Copy Composer .env to the root folder"
                sh script: './script/cibuild composer', label: 'Running CI Build composer'
            }
          }
        }

        stage('Node') {
          steps {
            sh script: './script/cibuild node', label: 'Running CI Build'
            // Jenkins as owner
            sh 'chown -R 110:117 .'
           }
        }
      }
    }

    stage('Deploy Manual') {
      when {
        expression { params.MANUAL_DEPLOY == true }
      }
      steps {
        sshagent (credentials: ["${GIT_SSH_KEYS}"]) {
          sh "./script/cideploy ${params.DEPLOY_ENVIRONMENT}"
        }
      }
    }

    stage('Deploy to Dev') {
      when {
        allOf {
          branch 'develop'
          expression { params.MANUAL_DEPLOY == false }
        }
      }
      steps {
        sshagent (credentials: ["${GIT_SSH_KEYS}"]) {
          sh script: './script/cideploy dev', label: 'Deploy to Dev'
        }
      }
    }

    stage('Deploy to Staging') {
      when {
        allOf {
          branch 'server/staging'
          expression { params.MANUAL_DEPLOY == false }
        }
      }
      steps {
        sshagent (credentials: ["${GIT_SSH_KEYS}"]) {
          sh script: './script/cideploy staging', label: 'Deploy to Staging'
        }
      }
    }

    stage('Deploy to Production') {
      when {
        allOf {
          branch 'server/production'
          expression { params.MANUAL_DEPLOY == false }
        }
      }
      steps {
        sshagent (credentials: ["${GIT_SSH_KEYS}"]) {
          sh script: './script/cideploy production', label: 'Deploy to Production'
        }
      }
    }
  }
  post {
    always {
      cleanWs()
    }
    failure {
      slackSend(channel: "${SLACK_CHANNEL}", color: 'danger', message: "`${APP_NAME}` deploy of branch `${env.REAL_BRANCH}` ${env.MSG_SLACK} failed: (build: <${RUN_DISPLAY_URL}|#${BUILD_NUMBER}>)")
    }
    success {
      slackSend(channel: "${SLACK_CHANNEL}", color: 'good', message: "`${APP_NAME}` deploy of branch `${env.REAL_BRANCH}` ${env.MSG_SLACK} was successful: (build: <${RUN_DISPLAY_URL}|#${BUILD_NUMBER}>)")
    }
  }
}
