
/* Requires the Docker Pipeline plugin */
pipeline {
    agent { docker { image 'myjenkins-blueocean:2.426.1-1' } }
    stages {
        stage('Example') {
            steps {
                echo "Running ${env.BUILD_ID} on ${env.JENKINS_URL}"
            }
        }
    }
}