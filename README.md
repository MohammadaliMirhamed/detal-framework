# Detal Framework -v1.0.0.
This is a micro framework expose ***REST-API*** endpoints. Application is following clean code mindset ***MVC*** , ***OOP***, ***SOILDS***, ***KISS***, ***DRY***, ***YANGI*** and same design patterns (***strategy*** pattern, ***adapter*** pattern, ***Template-Method*** pattern, auto ***DI*** from router to controller ).

## What's Run :
- **PHP**
- **MariaDB**
- **Docker**
- **Redis**

## Important Folders :
- ***app*** contains source code of system
- ***database*** contains sql files and ***migrations commands***
- ***routes*** it is obvious by the name :) 
- ***tests*** for testing application
- ***developer-thoughts*** for features/to-dos/ideas

## How to start :
- do a copy ***.env.example***  as  ***.env***
- Run ```docker-compose up -d```
- Run ```docker exec -it code_app_1 composer install```

## Migrations :
for importing ***sql*** file just run :
- ```docker exec -it code_app_1 php database/commands/migrate-all.php```

if you need just one sql or migration file run :
-  ```docker exec -it code_app_1 php database/commands/migrate-single.php '000001_users_table.sql'```

***Note*** : if you want to add your own migration, please keep an eye on number on the name of each sql file to run inorder
## Detal Command Line :
Detal framework; has its own dedicated command line system to controll application
For seeing the menu  run : ``` docker exec -it code_app_1 php detal ```

![alt text](https://mirhamedrooy.ir/wp-content/uploads/2022/11/Screenshot-from-2022-11-23-02-05-42.png)
For example if you want to add controller just run : 
```docker exec -it code_app_1 php detal make:controller PushController```

## Testing :
For runing the tests, just need to do ```docker exec -it code_app_1 ./vendor/bin/phpunit```

## CI/CD FILE :
For CI/CD check the ```.gitlab-ci.yml``` file.

## Postman :
in the project directory there is file with ```detal.postman_collection.json``` for postman testing.