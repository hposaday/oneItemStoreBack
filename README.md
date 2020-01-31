<h2>REQUERIMIENTOS </h2>

Requerimientos para correr el proyecto:

- MySql
- Laravel 6
- PHP >= 7.2.0
- composer

<h2>BASE DE DATOS </h2>

se debe crear un base de datos en local llamada "one_item_store" (para cambiar nombre de la base de datos ir al archivo .env y definir la nuev aconfiguracion de la base de datos).

luego desde la terminal ubicandondose en la raíz del proyecto ejecutar php artisan migrate, para crear en la base de datos las tablas neceserias para el funcionamiento del proyecto.

<h2>CORRIENDO SERVIDOR</h2>

primer correr el comando composer install en la terminal para instalar las dependencias del proyecto, luego para poner en funcionamiento el servidor se debe ejecutar desde la terminal el comando php artisan serve, estoy creara un servidor local corriendo en el puerto 8000

<h2>CORRIENDO PROYECTO FRONT END / CLIENTE</h2>

como parte de la prueba se realizó un projecto cliente para consumir los servicios que se exponen en esta API. a continuación se encuentra el link del repositorio con las instrucciones para su uso

PROYECTO FRONT: https://github.com/hposaday/currencyChangeFront

