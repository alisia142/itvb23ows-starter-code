pipeline {
    agent any
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
        stage('Unit Tests') {
            agent { docker { image 'php:8.3-cli'} }
            steps {
                sh 'vendor/bin/phpunit'
                xunit([
                    thresholds: [
                        failed ( failureThreshold: "0" ),
                        skipped ( unstableThreshold: "0" )
                    ],
                    tools: [
                        PHPUnit(pattern: 'build/logs/junit.xml', stopProcessingIfError: true, failIfNotNew: true)
                    ]
                ])
            }
        }
    }
}

// https://www.cloudbees.com/blog/how-to-install-and-run-jenkins-with-docker-compose
// https://docs.sonarsource.com/sonarqube/latest/setup-and-upgrade/install-the-server/installing-sonarqube-from-docker/
