#Compliance Web app  
  
* Stage server: http://compliance.maketry.xyz/
  
## Installation   

**Clone the repository**   
```bash
$ git clone git@gitlab.lifelabs.xyz:compliance-app/webapp.git
```  
  
**Install Dependencies**  
 ```bash
 $ composer install
 ```  
 
  **Create .env file from example .env.example**  
 ```bash
 $ cp .env.example .env
 ```

**Create APP_KEY for .env**  
 ```bash
 $ php key:generate
 ``` 

**Change access rights**  

```bash
$ chmod -R 755 storage
$ chmod -R 755 bootstrap/cache
```

**Create the public link to storage**
```bash
php artisan storage:link
```

**Create database and write parameters in .env file**  

    DB_DATABASE=  
    DB_USERNAME=  
    DB_PASSWORD=  

**Create an account in mailtrap.io and write parameters in .env file**

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=465
    MAIL_USERNAME=
    MAIL_PASSWORD=
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=
    
    
**Run the command**  
```bash
$ php artisan migrate --seed
```   
 
**Demo User**
login: demo@gmail.com 
password: demo
 
