# Prueba Ingreso Aicoll (MicroService)

## Autor
**Jose Alejandro Calderon Rico**
📧 alejandronba98@gmail.com  
📱 +57 315 470 9447

Hola a todos, Esta es la prueba del microservice para empresas. Dejare un listado descriptivo donde indique lo que se utilizo para realizarlo

- Lenguaje: PHP
- Framework: Laravel
- Documentacion: Swagger
- Control de Versiones: GIT
- DB: Mysql


# Herramientas:
Para continuar con la pruebas del backend, hay ciertas pasos a tener en cuenta que deberas realizar

* composer install
* php artisan key:generate


# Acontinuacion dejare los algunos comandos Que pueden ser utiles

1.  Ir al repositorio en GitHub: **https://github.com/alejophotoart/micro-service-company-aicoll.git**
2.  Descargar el codigo o hacer:
 ```bash 
 git clone ${link del repositorio}
 ```
3.  instalar composer
```bash 
composer install
```
4.  Generar la key para que funcione el api
```bash 
php artisan key:generate
```

5.  **IMPORTANTE** ejecutar los siguientes comandos si se desea realizar pruebas unitarias
```bash 
php artisan migrate --env=testing
```
```bash 
php artisan test --filter=CompanyControllerTest
```    
6.  Existe un comando para limpiar los Logs como una tarea programada:
```bash 
php artisan schedule:work
```
```bash 
php artisan app:logs-delete
``` 
7. Adicional hay una documentacion en Swagger en la siguiente ruta **api/documentation#/**

Muchas gracias,
Espero pueda volver a saber muy pronto de ustedes y gracias por tomarse el tiempo de llegar hasta aca

Saludos!