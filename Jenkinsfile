
/* Requires the Docker Pipeline plugin */
pipeline {
    agent { docker { image 'node:20.10.0-alpine3.18' } }
    stages {
        stage('Test') {
            steps {
                sh 'node --version'
            }
        }
    }
}

