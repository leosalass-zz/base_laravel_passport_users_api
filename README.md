# Desciption

Implementing an access control system through the use of roles and permissions, using Laravel 5.6, Laravel passport, mpociot / laravel-apidoc-generator. This project aims to guarantee a correct access control to the applications, no matter how many different roles the same user may require, since the system is validated against the permissions available to the users according to their roles, and directly assigned or blocked permissions.

## Permissions
The permissions allow access to the users to the different modules of the application, for the system implemented in this project, each module will have some basic permissions described below:  
- modulo.create  
- modulo.read  
- modulo.read.mine  
- modulo.update  
- modulo.update.mine  
- modulo.delete  
- modulo.delete.mine   

### Available options  
- create Permissions
- remove permissions


# Descipción (SPANISH)

Implemantación de un sistema de control de acceso mediante el uso de roles y permisos, haciendo uso de Laravel 5.6, Laravel passport, mpociot/laravel-apidoc-generator. Con este proyecto se pretende garantizar un correcto control de acceso a las aplicaciones, sin importar cuantos diferentes roles pueda requrir un mismo usuario, ya que el sistema es validado contra los permisos disponibles de los usuarios segun sus roles, y permisos directamente asignados o bloqueados.   

## Permisos
Los permisos permiten acceso a los usuarios a los diferentes modulos de la aplicacion, para el sistema implementado en este proyecto, cada modulo tendra unos permisos básicos descritos a continuación:  
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
Through the creation of roles, specific permissions are defined for a type of user.

### Available options
- create roles
- list roles
- update roles
- eliminate roles
- assign and delete permissions in a role

## Users
all access accounts to the application.

### Available options:
- create users
- list users
- update users
- remove users
- list roles of a user
- assign roles of a specific user.
- Remove roles from a specific user.
- list permissions of a user
- Remove permissions from a specific user.
- assign permissions of a specific user.

## Seeders
the application is already parameterized with the minimum data required, check the seeders for more information.

## Instructions
Below are the basic instructions to start the project and connect from postman.

### env file
rename the .env.example file to .env

### console / terminal   
in the console go to the root of the project and execute the following commands (if you use vagrant for the commands that interact with the data dase, you must enter the machine by ssh, https://confluence.jetbrains.com/display/PhpStorm/Working+with+Advanced+Vagrant+features+in+PhpStorm):
- composer update  
- php artisan key:generate  
- opcional: vendor\bin\homestead make  
- opcional: vagrant up  
- php artisan migrate  
- php artisan passport:install -- force  
- php artisan passport:keys --force
- composer dump-autoload
- php artisan db:seed


## documentation of services and access to them

### Generate API route documentation
- php artisan api: gen --routePrefix = "settings / api / *"


### visualize the ducumentacion of API routes
- NAME_of_the_SITIO / docs / index.html

Documentation: https://github.com/mpociot/laravel-apidoc-generator/

## Get Access Token
For this documentation the default user will be used in the seeder, but each user must obtain their own access token

- POST: http://share-your-plate.app/oauth/token
- Body:
- grant_type: password
- client_id: CLIENT_ID_FROM_DB
- client_secret: SECRET_KEY_FROM_DB
- username: admin@admin.com
- password: secret

Note: CLIENT_ID_FROM_DB and SECRET_KEY_FROM_DB are both in the Table oauth_clients, you must register with the name "Laravel Password Grant Client". For new projects this ID is 2.

## Consume access tokens
### Headers
- Accept: application / json
- Authorization: Bearer YOUR_ACCESS_TOKEN

Note: YOUR_ACCESS_TOKEN is obtained in the response from the previous step.


## Configuration of new modules
The different permissions and roles can be created and assigned through the available routes of apis, to validate the access to a specific module, the ApplicationAccessControl middleware with its respective restrictions must be added to the desired route. Ex:
- Route :: get ('id / {id_user} / roles', 'UsersController @ roles')
-> middleware (['ApplicationAccessControl: user_has_user_roles.read', 'auth: api']);

## Special thanks:
- Laravel team: For this excellent framework
- mpociot: for its apis documentation system, https://github.com/mpociot/laravel-apidoc-generator/
- Stackoverflow: for its countless amount of information that helped me many of the questions that were presented to me.

# Contact and support
For personalized support you can contact me through the email: leosalass@gmail.com
