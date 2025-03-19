Deploy:

- docker-compose up -d --build
- docker-compose exec php php yii migrate
- docker-compose exec php php yii books/load
- http://localhost:8080
