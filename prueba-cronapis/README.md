# Prueba backend CRONAPIS

_Pequeña api de prueba para el cargo de backend en la empresa CRONAPIS_

## Comenzando 🚀

_Esta api se realizo con Laravel 8, utilizando el paquete de Laravel Sanctum para la implementacion de una autenticacion simple por API TOKENS._

## Despliegue 📦

_Hacer fork de este repositorio, utilizar algun servidor local como XAMPP, ejecutar las migraciones y utilizar postman para realizar las peticiones_

### Rutas de la api 📋

_Estas son las rutas y sus respuestas, para interactuar con la api_


_Para registrarse en la api se requiere del campo ´email´ y ´password´_

```
POST /api/register
```

_Para obtener el token se requiere del campo ´email´ y ´password´_

```
POST /api/login
```

_Para hacer peticiones a todas estas rutas se requiere del token del usuario registrado_

```
GET /api/store - Obtener todos los registros
```
```
POST /api/store - Registrar una tienda/store campos (nombre,direccion,telefono,email,longitud,latitud, imagen)
```
```
GET /api/store/paginate - Obtener los registros paginados
```
```
GET /api/store/paginate?perp=x - Obtener los registros paginados segun el parametro 'perp'
``` 
```
GET /api/store/{id} - Obtener un registro en especifico por el campo 'id'
```
``` 
DELETE /api/store/{id} - Eliminar un registro en especifico por el campo 'id'
``` 

_Los campos 'longitud' y  'Latitud' son almacenados en formato json en la base de datos en el campo 'ubicacion', y estos pueden ser utilizados para alguna api de mapas como google maps_


## Construido con 🛠️


* [Laravel](https://laravel.com/docs/8.x/) - El mejor framework de PHP
