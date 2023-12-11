
/* Requires the Docker Pipeline plugin */
pipeline {
    agent { docker { image 'jenkins-docker' } }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
            }
        }
    }
}