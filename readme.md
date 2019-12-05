<h1  align="center">Welcome to Blog-A 👋</h1>

<p>

</p>

  

> A simple blog. Made with Laravel, Vue and Vuetify.

  

## Install

 
1. Clone this repo and Enter the project directory
```
    git clone https://github.coenter code herem/gurillaz/blog-a.git && cd blog-a
```
2. Install Laravel dependencies
```
    composer install
```
3. Copy the .env file from .env.example
```
    cp .env.example .env
```
		Then modify the DB_* values in .env with your DB config. 
  
4. Generate Laravel application key
```
    php artisan key:generate
```
5. Generate JWT key
```
    php artisan jwt:secret
```
7. Create new folder with name "images" in 
```
    `storage\app\public\images`
```
9. Create a symbolic link from `public/storage` to `storage/app/public`.
```
    php artisan storage:link
```
8. Run migration and populate tables with fake data.
```
    php artisan migrate --seed
```
9. Install Node dependencies
```
    npm install
```
10. Run the following command
```
    npm run watch
```
11. If there are no errors, in another terminal run the following
```
    php artisan serve
```
#### Further instructions


Make sure the port 8000 is not in use. You can use another port by adding
```
    --port PORT_NUMBER
```
to the previous command. Your application should be running at: http://127.0.0.1:8000

##### Default account(admin):

    Email: admin@example.com
    Password: password 
```
```

## Author

  

👤 **Klevis Sahitaj**

  

* Website: gurillaz.github.io

* Github: [@gurillaz](https://github.com/gurillaz)
