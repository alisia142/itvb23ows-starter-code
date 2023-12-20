pipeline {
    agent { label '!windows' }
    stages {
        stage('SonarQube') {
            steps {
                script { 
                    scannerHome = tool name: 'SonarQube Scanner' 
                    withSonarQubeEnv('SonarQube') {
                        sh "${scannerHome}/bin/sonar-scanner -Dsonar.projectKey=SonarQube"
                    }
                }
            }
        }
    }
}