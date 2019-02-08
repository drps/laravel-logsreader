init:
	composer install
	chmod 777 -R ./storage
	php artisan migrate
	php artisan db:seed
	php artisan paginator:cache

seed:
	php artisan db:seed
	php artisan paginator:cache
