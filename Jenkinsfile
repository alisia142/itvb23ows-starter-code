pipeline {
    agent {
        docker {
            image 'myjenkins-blueocean:2.426.1-1'
        }
    }
    stages {
        stage('Build') {
            steps {
                script {
                    sh 'php --version'
                }
            }
        }
    }
}
