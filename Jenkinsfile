pipeline {
    agent { label '!windows' }
    stages(
        stage('Build') {
            steps {
                script { 
                    scannerHome = tool 'SonarQube Scanner' 
                    withSonarQubeEnv('SonarQube') {
                        sh "${scannerHome}/bin/sonar-scanner -Dsonar.projectKey=SonarQube"
                    }
                }
            }
        }
    )
}