pipeline {
  agent any
  stages {
    stage('Test') {
      agent {
        docker {
          image 'composer:1.8'
        }

      }
      steps {
        echo 'Test'
      }
    }
  }
}