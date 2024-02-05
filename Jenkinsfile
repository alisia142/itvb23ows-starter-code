pipeline {
    agent { docker { image 'php:5.6-cli-alphine' } }
    stages {
        stage('SonarQube') {
            steps {
                script { 
                    scannerHome = tool 'SonarQube Scanner'
                    withSonarQubeEnv('SonarQube') {
                        sh "${scannerHome}/bin/sonar-scanner -Dsonar.projectKey=SonarQube"
                    }
                }
            }
        }
    }
}

// https://www.cloudbees.com/blog/how-to-install-and-run-jenkins-with-docker-compose
// https://docs.sonarsource.com/sonarqube/latest/setup-and-upgrade/install-the-server/installing-sonarqube-from-docker/
