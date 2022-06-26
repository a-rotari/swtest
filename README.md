# Junior Dev Test Assignment

27 Jun 2022, ver. 1

### Contents:
 1. [Project Overview](#project-overview)
 2. [Technologies](#technologies)
 3. [Deployment](#deploying-the-project-on-vps)
 4. [Details: Libraries](#libraries)
 5. [Details: Controllers](#controllers)
 6. [Details: Models](#models)
 7. [Details: Views](#views)

## Project overview
This is a simple web-app accessible by URL containing two pages for:
1. Product list page
2. Adding a product page

The product list page displays a list of products in stock and associated information. There are currently 3 types of products: furniture, books, DVDs. There are two buttons on the page: Add Product button and Mass Delete button. When clicked, the buttons perform their respective functions.

Add product page displays a form with several fields and 2 buttons: Save and Cancel. Save button submits the form to the database. Cancel button redirects back to the main page.

As of June 2022, the app is live at https://juniortest.arotari.com

## Technologies
The project has been developed using PHP 7.4, MySQL 8.0, HTML, CSS, JavaScript. No frameworks were used, although the project's back-end design follows MVC pattern, constituting a simplistic framework in and of itself. 

The project can be deployed on a virtual private server (VPS) running Ubuntu Linux, and requires customization of [web-server settings](#sample-server-templates) to ensure correct processing of parameters in requests.


## Deploying the project on VPS
Prepare the environment by installing Apache or NGINX, MySQL, and PHP. Click the links for more info about installing and configuring the so-called [LAMP](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-20-04) or [LEMP](https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-on-ubuntu-20-04) stacks. 

Once the environment has been prepared, create the  main app directory at the root of your web-server and copy the directories __app/__ and __public/__ into the main app directory.

Create the project's database in MySQL, then create a dedicated user that has a strong password and has been granted all privileges to work with the project's database. You then should run the SQL script __create.sql__ included in the root directory of this repository. The script will create two tables: __product__ and __attribute__. 

Edit database's name, username, user's password in  __app/config/config.php__ to match the actual settings of your MySQL configuration:

>define('DB_HOST', 'localhost');  
>define('DB_USER', '__DATABASE_USER__');  
>define('DB_PASS', '__USER_PASSWORD__');  
>define('DB_NAME', '__DATABASE_NAME__');

Edit your web-server's configuration to make sure that all incoming requests are routed to directory __public/__, and that if __public/__ does not contain any directory or file matching the request, the request is then routed to __public/index.php__ with the original URI passed as a value of the parameter __url__. For example, for the app to work properly, the original request __https[]()://example.com/delete-products/57/135__ must be rewritten as __https[]()://example.com/public/index.php?url=delete-products/57/135__.

You will need to configure your web-server of choice to work with your website. Here are the instructions for [Apache](https://www.digitalocean.com/community/tutorials/how-to-install-the-apache-web-server-on-ubuntu-20-04) and [NGINX](https://www.digitalocean.com/community/tutorials/how-to-install-nginx-on-ubuntu-20-04) covering basic configuration of your website.

 You then need to use the provided configuration templates to further set up the app.  

### Sample server templates

#### Apache
After having configured the *Virtual Hosts* file you will need to create 3 files named __.htaccess__ in 3 directories of the app: one in the *root* directory, another in directory __app/__, and the third one in directory __public/__. The contents of the files are shown below:

##### *.htaccess in root directory of the app (the one that contains directories __public/__ and __app/__):*
    <IfModule mod_rewrite.c>  
        RewriteEngine On  
        RewriteRule ^$ public/ [L]
        RewriteRule (.*) public/$1 [L]
    </IfModule>

##### *.htaccess in app/ directory of the app:*
    Options -Indexes

##### *.htaccess in public/ directory of the app:
    <IfModule mod_rewrite.c>  
        Options -Multiviews  
        RewriteEngine On  
        RewriteBase /public  
        RewriteCond %{REQUEST_FILENAME} !-d  
        RewriteCond %{REQUEST_FILENAME} !-f  
        RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
    </IfModule>
    
#### NGINX
After having created *server block* file for your website in directory */etc/nginx/sites-available/*, paste the following into it, replacing the capitalized parts with your path, then activate the *server block*, check the configuration and, if everything seems OK, reload NGINX. 

    server {
        root /PATH_TO_APP_ROOT/public;
        index index.html index.php;
        server_name juniortest.arotari.com www.juniortest.arotari.com;

        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        }

        location / {
            try_files $uri $uri/ @rules;
        }

        location @rules {
            rewrite ^/(.*)$ /index.php?url=$1 last;
        }

## The app's inner workings

###  Libraries
The __app/libraries/__ directory of the app contains three libraries: __Core.php__, __Controller.php__, __Database.php__.

#### Core.php
The core of the app. The first and, at the same time, the final step after the server passes  the data to __public/index.php__ is to instantiate the __Core__ class. The constructor method of the Core class facilitates the parsing of the data received from the URI and, based on the results of the parsing, calls a specific method of a specific controller, passing specific arguments. 

#### Controller.php
The parent class abstract class of controllers. Implements methods __model()__ that instantiates a specific model and __view()__ that displays a specific view.

#### Database .php
Class that instantiates PDO object and facilitates the interaction of models with database.

### Controllers

#### Products.php
This is the controller for Product model. When the Products class is instantiated it instantiates Product model as its property. The constructor method of the Core class then calls one of the methods of the Products controller based on the parsed request data, passing arguments, if the request URI contained any.

Products object has three methods:
__index()__: returns the view __app/views/index.php__ displaying the main page with a list of products.

__addproducts()__: returns the view __app/views/add-product.php__. Depending on the request method (*GET* or *POST*) the controller either displays the form for adding new products, or submits the form data to the server for insertion into database.

__deleteproducts()__: deletes the products whose IDs are passed as arguments, and then redirects to main page.

### Models

#### Product.php
The model is instantiated as a property of the controller and interacts with the database, performing *create*, *read*, *delete* operations via its methods __getProducts()__, __postProduct()__, __deleteProduct()__. 
The model's __validate...()__ methods perform validation of form fields' values before they are inserted into database.
The model's __format...()__ methods format the values output from database before they are displayed in a view.

### Views
The two views currently implemented are: __index.php__ for the main page and __add-product.php__ for the add product page. The views only cater to desktop browsing, since adaptive/responsive approaches were outside of the scope of this exercise. 
__add-product.php__ features a form some of whose fields are dynamically generated for added flexibility in choosing a product type.
Both views rely on JavaScript for correct operation.

### A note about arrays
A lot of the app's internal activity is focused on processing the products' attributes in the form of elements of arrays. Due to somewhat complex structure of these arrays, it would probably be useful to include their description here. This may be done at a later time.

## Acknowledgements
This is the first project I did with PHP and JavaScript, and, while I have no doubt that more will follow, it will always have a special place in my heart. 
This project would be impossible without the people who are happy to share their knowledge with the world and who, through perseverance and dedication, create coding courses that open a world of possibilities for those willing to dive in. 

<!--stackedit_data:
eyJoaXN0b3J5IjpbLTYxMzIxMDIwNiwtMTE3MDA1MzQzNiwtMT
IwMDc1OTExOCwxNzg2Mzk2OTU3LDQzODQ5NTIwNCwtMTA2MDY4
MzU3OSwtMTcwNTYzNzQyMl19
-->