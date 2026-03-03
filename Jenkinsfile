pipeline {
    agent any

    environment {
        AWS_REGION = 'us-east-1'
        ECR_REPO = '201186892936.dkr.ecr.us-east-1.amazonaws.com/bitcot-devops-app'
        IMAGE_TAG = 'latest'
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
                sh 'docker build -t bitcot-app .'
            }
        }

        stage('Tag Image') {
            steps {
                sh 'docker tag bitcot-app:latest $ECR_REPO:$IMAGE_TAG'
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

        stage('Push to ECR') {
            steps {
                sh 'docker push $ECR_REPO:$IMAGE_TAG'
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                docker stop bitcot-php || true
                docker rm bitcot-php || true
                docker pull $ECR_REPO:$IMAGE_TAG
                docker run -d -p 80:80 --name bitcot-php $ECR_REPO:$IMAGE_TAG
                '''
            }
        }
    }
}
