## Mini project for video upload

##Requirement
- php 7.0
- mysql 5.7
- redis 3.2 (or another queue)
- ffmpeg

## Installation

- composer update
- npm install
- copy .env.example and adapt it for your env.
- run php artisan migrate
- run php artisan db:seed (we have not any register functional, so we can make users by default)
- run redis and php artisan queue:work

## Configuration
You can configure filesystem and change UPLOAD_PATH and VIDEO_URL

For API test, you can visit domain.com/swagger
Don't forget to run gulp first