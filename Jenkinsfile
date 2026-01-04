pipeline {
    agent any

    environment {
        // --- KONFIGURASI DOCKER ---
        IMAGE_NAME = "kaslyn-app"
        DOCKER_HUB_USER = "naufal354"
        
        // --- KONFIGURASI LARAVEL (JENKINS ONLY) ---
        // Menggunakan SQLite hanya untuk proses test/build di Jenkins
        // agar tidak error database connection
        DB_CONNECTION = 'sqlite'
        DB_DATABASE = 'database/database.sqlite'
    }

    stages {
        stage('1. Checkout Code') {
            steps {
                // Pastikan branch ini sesuai dengan branch GitHub kamu
                git branch: '25-alternatif-login-lewat-google', url: 'https://github.com/Naufall152/kaslynproject.git'
            }
        }

        stage('2. Prepare Environment') {
            steps {
                script {
                    // Setup file .env
                    bat 'copy .env.example .env'
                    
                    // Setup Database SQLite Dummy
                    bat 'if not exist "database\\database.sqlite" type nul > database\\database.sqlite'
                    
                    // Generate Key & Install Dependencies
                    bat 'php artisan key:generate'
                    bat 'composer install --no-interaction --prefer-dist'
                    bat 'npm install'
                }
            }
        }

        stage('3. Build Assets') {
            steps {
                // Compile CSS/JS
                bat 'npm run build'
                
                // Migrasi dummy (untuk memastikan kodingan DB valid)
                // Pastikan driver sqlite di php.ini server Jenkins sudah aktif
                bat 'php artisan migrate --force' 
            }
        }

        stage('4. Build & Push Docker Image') {
            steps {
                script {
                    withCredentials([usernamePassword(credentialsId: 'dockerhub-credentials', 
                                     usernameVariable: 'DOCKER_USER', 
                                     passwordVariable: 'DOCKER_PASS')]) {
                        
                        // Hapus sesi lama & Login baru
                        bat 'docker logout'
                        bat 'echo %DOCKER_PASS% | docker login -u %DOCKER_USER% --password-stdin'
                        
                        // Build Image
                        bat "docker build -t %DOCKER_HUB_USER%/%IMAGE_NAME%:%BUILD_NUMBER% ."
                        bat "docker build -t %DOCKER_HUB_USER%/%IMAGE_NAME%:latest ."
                        
                        // Push Image
                        bat "docker push %DOCKER_HUB_USER%/%IMAGE_NAME%:%BUILD_NUMBER%"
                        bat "docker push %DOCKER_HUB_USER%/%IMAGE_NAME%:latest"
                    }
                }
            }
        }

        stage('5. Deploy to Azure') {
            steps {
                // Mengambil URL Webhook dari Credential Jenkins
                withCredentials([string(credentialsId: 'azure-webhook-url', variable: 'AZURE_WEBHOOK')]) {
                    script {
                        echo "Memberi sinyal ke Azure KaslynV2..."
                        // Mengirim request POST ke Webhook Azure
                        // Jika curl gagal di Windows, pastikan Git Bash terinstall atau gunakan PowerShell
                        bat 'curl -X POST %AZURE_WEBHOOK%'
                    }
                }
            }
        }
    }
    
    post {
        always {
            // Bersihkan sampah image di Jenkins
            bat "docker rmi %DOCKER_HUB_USER%/%IMAGE_NAME%:%BUILD_NUMBER%"
            bat "docker rmi %DOCKER_HUB_USER%/%IMAGE_NAME%:latest"
        }
        success {
            echo 'PIPELINE SUKSES: Website Kaslyn V2 sedang di-update oleh Azure!'
        }
    }
}
