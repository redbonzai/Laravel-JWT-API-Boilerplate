### Setup domain

```bash
vim /etc/hosts
and enter : 
127.0.0.1 amazing.test
```
### Setup Docker or Vagrant, whatever you want. 

### Get the .env file
```bash
cp env-example .env
```
### Composer install
```bash
composer install
```

### You won't need it, but compile assets just in case
```bash
npm install
```

### Run Migrations
```bash
php artisan migrate
```

### Postman environment
[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/c06866384de9931ed73c#?env%5BAmazing%5D=W3sia2V5IjoiYmFzZVVybCIsInZhbHVlIjoiaHR0cDovL2FtYXppbmcudGVzdCIsImRlc2NyaXB0aW9uIjoiIiwidHlwZSI6InRleHQiLCJlbmFibGVkIjp0cnVlfV0=)
