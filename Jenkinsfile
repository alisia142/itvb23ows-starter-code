pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                script {
                    // Git-checkout
                    checkout scm
                }
            }
        }

        stage('Build') {
            steps {
                script {
                    // Voer bouwstappen uit
                    sh 'mvn clean install'
                }
            }
        }

        stage('Test') {
            steps {
                script {
                    // Voer tests uit
                    sh 'mvn test'
                }
            }
        }

        stage('Deploy') {
            steps {
                script {
                    // Implementeer de applicatie
                    sh 'mvn deploy'
                }
            }
        }
    }
}
