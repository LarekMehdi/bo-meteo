# bo-meteo

adminer: http://localhost:8082/

docker: docker compose --env-file .env.local up

lexik:
mkdir -p config/jwt
php bin/console lexik:jwt:generate-keypair
symfony console lexik:jwt:generate-keypair
