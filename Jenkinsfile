Jenkinsfile (Declarative Pipeline)

/* Requires the Docker Pipeline plugin */
pipeline {
    agent { docker { image 'itvb23ows-starter-code-web' } }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
            }
        }
    }
}

