# mailr.ru tz
Приложение выполняет две функции:

 - Забирает из сервиса Twitch все активные трансляции интересующих нас игр
 - Выдает информацию пользователю

## Сбор трансляций
За то, что бы достать трансляции из внешних сервисов отвечает команда `php artisan streams:retrieve`. Через ServiceProvider ей передается список Ретриверов (например: `app/Stream/TwitchRetriever`). Каждый Ретривер имплементирует интерфейс Retrieverinterface. Каждый знает как правильно достать Стримы из конкретного сервиса и возвращает универсальный StreamDTO объект(`app/DTO/StreamDTO.php`).

Для сохранения Стрима в БД используется класс StreamPersister который умеет правильно сохранять StreamDTO в БД.

## Выдача информации о стримах
Для выдачи информации о стримах есть два экшна в контроллере `app/Http/Controllers/StreamController.php`:

- index()
- byGames()

Первый выдает информацию о стримах, второй кол-во смотрящих игру (или список игр).
### Endpoints
`/oauth/token`

Для получения access token.

`/streams`

Отдает информацию о стримах. Принимает параметры `games` для фильтрации по игре(играм) и time для указания за какое время нужна статистика. Если опустить параметр `games` - статистика будет по всем играм. Если опустить `time` - статистика на текущий момент. Выдает 15 элементов и ссылки на следующую и предыдущую страницу.

`/streams/games`

Отдает кол-во стримов по игре (играм). Принимает параметры `games` для фильтрации по игре(играм) и time для указания за какое время нужна статистика. Если опустить параметр `games` - статистика будет по всем играм. Если опустить `time` - статистика на текущий момент. Выдает 15 элементов и ссылки на следующую и предыдущую страницу.

#### Примеры запросов:

Получить access token

```
curl -X POST \
  http://localhost:8000/oauth/token 
  -H 'content-type: multipart/form-data;' 
  -F grant_type=password 
  -F client_id=2 
  -F client_secret=D7OLiClUfIbIKjcgMeqsPw79vH6Bn3P6exEdKFhR 
  -F username=voff.web@gmail.com 
  -F password=123456 
  -F 'scope=*'
```
client_id и client_secret из таблицы `oauth_clients`

Результат
```
{
    "token_type": "Bearer",
    "expires_in": 31536000,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjYxYjFhZjExYzMxZmUzNmUyNjdjY2QxZGExYzdiNmNhODFhMDllYzViMDEyNWE0ODU1ZTJmYTk4OWU1MjViOTU3YzQ2ODViMmViYmUyMjIyIn0.eyJhdWQiOiIyIiwianRpIjoiNjFiMWFmMTFjMzFmZTM2ZTI2N2NjZDFkYTFjN2I2Y2E4MWEwOWVjNWIwMTI1YTQ4NTVlMmZhOTg5ZTUyNWI5NTdjNDY4NWIyZWJiZTIyMjIiLCJpYXQiOjE1MzM1MTA0MTgsIm5iZiI6MTUzMzUxMDQxOCwiZXhwIjoxNTY1MDQ2NDE4LCJzdWIiOiIxIiwic2NvcGVzIjpbIioiXX0.qWyMb6fbyNxB0JXifr4J0BXtd8EGwjIzqvYyB5WHWbwYIggy0rmLR1cCcXg0hfKtIB42ufXrGAAmO6Fs1MoQ09Uh6Bzu7L03IcDKDSPer55_ThmZewrTNYOh6568UVlNeyLjidN8h4ClylHPs_uAQYKYW3fLN6mJs1i6xSu1zaVc1B9h6jOlGK3L2S7Y4xelaKeyq1V7rRys5ExB4UiV4g2jiPtadkCxXfHFpNtj0KBZ7-TG1FQ_W2JjfNLtDwSw8_EOVGhOLHs6HS3bJ7HwAGJu7MLpN3pfVWdw37ieoDCI7i3UpztsXbwM6FXnk_wJPNm66_ZixjtHcOM025SVBg3VroGVPAW9gdGumeKHLiIf9DSL7KCcjvfTQF9itiHCH5bZjhniepcjAgvAOT42UmkAktGrfK_iz4uFJbOEXH1fNe70mQDzU4cT9T9Nd1ADHKfbtbeBASPv0G31SDtfoFZ-8vqVf8LzmDU5cbe7sLdwmR4BtUdfm8OyGvY9d6tY6CVsBu4hDe6hVXSC5X89RPcaxRRcXemIJ14SccsotQ74qnrEUDFAqTdER-pZFjMs8duB32EBvTG5M5obpZREBc4-Ae9PpejtzLSg9aQI906rrvnULywYLQOKDIaNANTuN5reS6zgNsfeaUV3mwuPU6E5LQABUmDXgKC8s_99ERw",
    "refresh_token": "def5020098f211dc48f4f1e36bc56518308b7d6b6b7f2fc305a478b2c22092e58a8d07a27febb1acef14318f2e1683d51b49d98523f95d21a9cee2d2e9c6d064702c7924c0bf0d9e5ae2fcc3f1bcf03d79485e350fe6b2b09b6b1f671dd4a20298aea3ca51d3051f6216b0175ccbdb0d7f2c8bc1f8a219ede255f617a7715d4a8ac099f38cb2b62f9671526ab9b61b30cde7295b37bb3089fa56cb774affbe2913bf2f8ef4cae239051579c1cafdf857b0dd486ee0693092ea6de1554cc717423cff62af3a448c057c9d5aa458b9ec70c194bf9987f5978437e74e8078240e8147a34a7b89e965ff7370a3d96fdbe69aacd41bf54d68c336f863d491e738ad07172ce1a7ceec7d02a743ac9de7180ba4f591fe5768d4e214f7b1592f27cabb6fdd126cec10dc02c2c7e783711d1cf92701c6c21a2535f91f8d4d27fed584d266e4657bb26cee178fa680241e6391ba71c5d2e747b9e5b601c8cbc4719f18cd77b6df4d31"
}
```



Все трансляции по всем играм (из БД) на текущий момент
```
curl -X GET \
  http://localhost:8000/streams 
  -H 'accept: application/json' 
  -H 'authorization: Bearer ACCESS_TOKEN' 
```
Тоже самое для игр с id `1` и `3` (id в нашей БД)
```
curl -X GET \
  'http://localhost:8000/streams?games[]=1&games[]=3' 
  -H 'accept: application/json' 
  -H 'authorization: Bearer ACCESS_TOKEN' 
```
Тоже самое на 3 августа 12 часов дня
```
curl -X GET \
  'http://localhost:8000/streams?games[]=1&games[]=3&time=2018-08-05 12:00:00' 
  -H 'accept: application/json' 
  -H 'authorization: Bearer ACCESS_TOKEN' 
```

Результат
```
{
    "data": [
        {
            "id": 249631,
            "game_id": 1,
            "title": "по просьбам подписчиков.... ",
            "streamer_id": "187754877",
            "stream_id": "29799984048",
            "service_name": "twitch",
            "language": "ru",
            "viewers_count": 8,
            "period_from": "2018-08-06 15:22:03",
            "period_to": "2018-08-06 15:24:01"
        },
        {
            "id": 249632,
            "game_id": 1,
            "title": "Warface para distrair!!",
            "streamer_id": "164977098",
            "stream_id": "29800111264",
            "service_name": "twitch",
            "language": "pt",
            "viewers_count": 4,
            "period_from": "2018-08-06 15:22:03",
            "period_to": "2018-08-06 15:24:01"
        },
        ...
    ],
    "links": {
        "first": "http://localhost:8000/streams?games[]=1&games[]=3&time=2018-08-03 12:00:00&page=1",
        "last": "http://localhost:8000/streams?games[]=1&games[]=3&time=2018-08-03 12:00:00&page=39",
        "prev": null,
        "next": "http://localhost:8000/streams?games[]=1&games[]=3&time=2018-08-03 12:00:00&page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 39,
        "path": "http://localhost:8000/streams",
        "per_page": 15,
        "to": 15,
        "total": 576
    }
}
```
Кол-во трансляций по каждой игре на текущий момент
```
curl -X GET \
  http://localhost:8000/streams/games 
  -H 'accept: application/json' 
  -H 'authorization: Bearer ACCESS_TOKEN' 
```
Тоже самое для игр с id `1` и `3` (id в нашей БД)
```
curl -X GET \
  'http://localhost:8000/streams/games?games[]=1&games[]=3' 
  -H 'accept: application/json' 
  -H 'authorization: Bearer ACCESS_TOKEN' 
```
Тоже самое на 3 августа 12 часов дня
```
curl -X GET \
  'http://localhost:8000/streams/games?games[]=1&games[]=3&time=2018-08-05 12:00:00' 
  -H 'accept: application/json' 
  -H 'authorization: Bearer ACCESS_TOKEN' 
```
Результат
```
{
    "data": [
        {
            "viewers_count": "714",
            "game_id": 1
        },
        {
            "viewers_count": "67027",
            "game_id": 3
        }
    ],
    "links": {
        "first": "http://localhost:8000/streams/games?games[]=1&games[]=3&time=2018-08-05 12:00:00&page=1",
        "last": "http://localhost:8000/streams/games?games[]=1&games[]=3&time=2018-08-05 12:00:00&page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "http://localhost:8000/streams/games",
        "per_page": 15,
        "to": 2,
        "total": 2
    }
}
```
## Тесты
Запускаются командой `vendor/bin/phpunit tests/Feature/`

## Инструменты

- PHP 7.1
- MySql 5.7.21
- Laravel Framework 5.6.29
- https://github.com/guzzle/guzzle (HTTP requests)
- https://laravel.com/docs/5.6/passport (API Authentication)
- https://github.com/skydiver/laravel-route-blocker (Block routes by IP) 

## Что можно улучшить
- Добавить кэширование SELECT'ов в Redis
- Отдавать ошибки в очередь (сейчас только в лог)
- CRUD для списка игр
