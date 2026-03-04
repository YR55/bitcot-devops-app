pipeline {
    agent any

    environment {
        AWS_REGION = 'us-east-1'
        ECR_REPO = '201186892936.dkr.ecr.us-east-1.amazonaws.com/bitcot-devops-app'
        IMAGE_TAG = "${BUILD_NUMBER}"
    }

    stages {

        stage('Checkout') {
            steps {
                git branch: 'main',
                url: 'https://github.com/YR55/bitcot-devops-app.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t bitcot-app:${IMAGE_TAG} .'
            }
        }

        stage('Tag Image') {
            steps {
                sh '''
                docker tag bitcot-app:${IMAGE_TAG} $ECR_REPO:${IMAGE_TAG}
                docker tag bitcot-app:${IMAGE_TAG} $ECR_REPO:latest
                '''
            }
        }

        stage('Login to ECR') {
            steps {
                withCredentials([[$class: 'AmazonWebServicesCredentialsBinding', credentialsId: 'aws-creds']]) {
                    sh '''
                    aws ecr get-login-password --region $AWS_REGION | \
                    docker login --username AWS --password-stdin 201186892936.dkr.ecr.us-east-1.amazonaws.com
                    '''
                }
            }
        }

        stage('Push Image') {
            steps {
                sh '''
                docker push $ECR_REPO:${IMAGE_TAG}
                docker push $ECR_REPO:latest
                '''
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                docker pull $ECR_REPO:latest

                docker stop bitcot-php || true
                docker rm bitcot-php || true

                docker run -d \
                --name bitcot-php \
                -p 8081:80 \
                --restart always \
                $ECR_REPO:latest
                '''
            }
        }
    }
}
