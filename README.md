
```bash
cp api/.env.example api/.env
```

```bash
docker compose up -d --build
```

```bash
docker compose exec app composer install
```

```bash
docker compose exec app php artisan key:generate
```

Call POST localhost:8080/api/weather
