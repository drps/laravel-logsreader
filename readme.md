# Logs reader

### Чтобы запустить проект нужно выполнить 2 команды

* `docker-compose up -d --build`
* `docker-compose exec php-cli make init`
* http://localhost:8080

Команда `init` установит все зависимости из composer.lock, 
выставит разрешения на директории для записи логов, 
применит миграции к проекту, 
заполнит созданную таблицу тестовыми записями (2 000 000 строк) и подготовит кэш

Добавить ещё 2 000 000 строк можно командой 

`docker-compose exec php-cli make seed`

Перегенерировать кэш можно командой `docker-compose exec php-cli php artisan paginator:cache`

## Описание алгоритма

Обычный способ постраничной навигации генерирует 2 SQL запроса вида

* `SELECT count(*) FROM table` 
* `SELECT * FROM table LIMIT 10 OFFSET 200`.
 
Для больших смещений это работает плохо.

Logs Reader кэширует для каждой 100 000-ой записи время записи и страницу на которой эта запись последняя.
Это позволяет генерировать вместо вышеприведенных запросов запросы следующего вида

* `SELECT count(*) FROM table`
* `select * from "logs" where "dt" > '2019-06-07 06:39:19' order by "dt" asc limit 100 offset 25100`

Данное решение также позволяет строить подобные вопросы и для обратного порядка сортировки, при этом запросы получаются вида

* `select count(*) as aggregate from "logs"`
* `select * from "logs" where "dt" <= '2019-03-18 19:17:09' order by "dt" desc limit 100 offset 7104`

## Возможные трудности

Время выполнения запроса на выборку данных можно 
регулировать изменяя размер "окна" для кэширования которое по умолчанию равно 100 000 строк. 
Его можно менять в конфигурации приложения в файле `config/app.php` параметр `cache.chunkSize`

Результат запроса `select * from logs` сейчас не кэшируется, но в зависимости от способа добавления записей можно кэшировать и его
тогда время генерации страницы можно уменьшить ещё

