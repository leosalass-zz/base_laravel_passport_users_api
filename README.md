# Descipción (SPANISH)

Implemantación de un sistema de control de acceso mediante el uso de roles y permisos, haciendo uso de Laravel 5.6, Laravel passport, mpociot/laravel-apidoc-generator. Con este proyecto se pretende garantizar un correcto control de acceso a las aplicaciones, sin importar cuantos diferentes roles pueda requrir un mismo usuario, ya que el sistema es validado contra los permisos disponibles de los usuarios segun sus roles, y permisos directamente asignados o bloqueados.   

## Permisos
Los persmisos permiten acceso a los usuarios a los diferentes modulos de la aplicacion, para el sistema implementado en este proyecto, cada modulo tendra unos permisos básicos descritos a continuación:  
- modulo.create  
- modulo.read  
- modulo.read.mine  
- modulo.update  
- modulo.update.mine  
- modulo.delete  
- modulo.delete.mine   

### Opciones disponibles  
- crear Permisos  
- eliminar permisos  

## Roles
mediante la cración de roles, se definen permisos especificos para un tipo de usuario.  

### opciones disponibles  
- crear roles  
- listar roles  
- actualizar roles  
- eliminar roles    
- asignar y eliminar permisos en un rol

## Usuarios  
todos las cuentas de acceso a la aplicacion.

### opciones disponibles:  
- crear usuarios    
- listar usuarios  
- actualizar usuarios  
- eliminar usuarios        
- listar roles de un usuario  
- asignar roles de un usuario específico.  
- retirar roles de un usuario específico.  
- listar permisos de un usuario  
- retirar permisos de un usuario específico.  
- asignar permisos de un usuario específico.    

## Seeders
la aplicacion ya viene parametrizada con los datos minimos requeridos, revisar los seeders para mayor información.    

## Instrucciones  
a continuación se describen las intrucciones básicas para poner en marcha el proyecto y conectarse desde postman.  

### env file  
renombrar el archivo .env.example a .env

### consola / terminal   
en la consola ir a root del proyecto y ejecutar los siguientes comandos (si usa vagrant para los comandos que interactuen con la dase de datos, debe ingresar a la maquina por ssh, https://confluence.jetbrains.com/display/PhpStorm/Working+with+Advanced+Vagrant+features+in+PhpStorm):
- composer update  
- php artisan key:generate  
- opcional: vendor\bin\homestead make  
- opcional: vagrant up  
- php artisan migrate  
- php artisan passport:install -- force  
- php artisan passport:keys --force
- composer dump-autoload
- php artisan db:seed

## documentacion de servicios y accesos a los mismos  

### Generar la documentacion de la rutas API  
- php artisan api:gen --routePrefix="settings/api/*"  

### visualizar la ducumentacion de las rutas API
- NOMBRE_DEL_SITIO/docs/index.html  

documentacion: https://github.com/mpociot/laravel-apidoc-generator/

## Obtener Token de Acceso  
Para esta documentación se usará el usuario generadp por defecto en el seeder, pero cada usuario deberà obtener su propio token de acceso  

- POST: http://share-your-plate.app/oauth/token  
- Body:  
-- grant_type: password  
-- client_id: CLIENT_ID_FROM_DB   
-- client_secret: SECRET_KEY_FROM_DB  
-- username: admin@admin.com  
-- password: secret  

Nota: CLIENT_ID_FROM_DB y SECRET_KEY_FROM_DB ambos estan en la Tabla oauth_clients, debe tomar el registro con el nombre "Laravel Password Grant Client". Para proyectos nuevos este ID es 2.    

## Consumir los tokens de acceso  
### Headers  
- Accept: application/json  
- Authorization: Bearer YOUR_ACCESS_TOKEN  

Nota: YOUR_ACCESS_TOKEN se obtiene en la respuesta de el paso anterior.  


## Configuracion de nuevos modulos   
Los diferentes permisos y roles pueden ser creados y asiganados mediante las rutas de apis disponibles, para validar el acceso a un modulo especifico, se debe agregar el middleware ApplicationAccessControl con sus respectivas restricciones a la ruta deseada. Ej:  
- Route::get('id/{id_user}/roles', 'UsersController@roles')  
->middleware(['ApplicationAccessControl:user_has_user_roles.read','auth:api']);   

## Agradecimientos especiales:
- Laravel team: Por este excelente framework   
- mpociot: por su sistema de documentacion de apis, https://github.com/mpociot/laravel-apidoc-generator/  
- stackoverflow: por su incontable cantidad de informacion que me sirvio de ayuda muchos de las dudas que se me presentaron.  


# Descipción (ENGLISH ... comming soon)


# Contacto y soporte  
Para soporte personalizado me pueden contactar a través del correo: leosalass@gmail.com
