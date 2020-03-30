pipeline {
  agent {
    docker {
      image 'composer:1.8'
      args 'reuseNode true'
    }

  }
  stages {
    stage('Test') {
      steps {
        echo 'Test'
      }
    }
  }
}