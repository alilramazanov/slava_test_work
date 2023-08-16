
install packages:
	composer install

up project:
	./vendor/bin/sail up -d

load migrations:
	./vendor/bin/sail php artisan migrate --seed

up queue:
	./vendor/bin/sail php artisan horizon

create storage link:
	./vendor/bin/sail php artisan storage:link