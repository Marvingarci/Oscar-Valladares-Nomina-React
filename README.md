# Oscar Valladares Nomina React
 Software para la administracion de nomina y produccion en fabrica de Tabacco


#Install composer dependencies
composer install

#Install Node dependencies
npm install

#Configure .env config to connect to database

#Create database key
php artisan key:generate

#Run Migration

php artisan migrate:fresh --seed

#Run app
npm run watch 
and
php artisan serve
