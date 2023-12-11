
/* Requires the Docker Pipeline plugin */
pipeline {
    agent { docker { image 'myjenkins-blueocean:2.426-1-1' } }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
            }
        }
    }
}

