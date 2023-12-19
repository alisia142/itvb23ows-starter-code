pipeline {
    agent { docker { image 'php:8.3.0-alpine3.19' } }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
                sh '''
                    echo "Multiline shell steps work too"
                    ls -lah
                '''
            }
        }
    }
}
