<p align="center">
  <img src="screenshots/logo.png" alt="Logo" width="80" height="80">

  <h3 align="center">Laravel LaPosta</h3>

  <p align="center">
   social media scheduler
  </p>
</p>

## About The Project
demo project built with laravel to schedule social media posts for later publish .. project focuses on using laravel framework components , architecture, patterns and tests.


## prerequisite
try to make twitter or facebook application with publish permissions
and update tokens in .env file

## Usage

1. Clone the repo

    ```sh
    git clone https://github.com/civilcoder55/laravel-laposta.git
    ```

2. update env file

    ```sh
    cp .env.example .env
    ```

3. start workers

    ```sh
    php artisan queue:work
    ```

    ```sh
    php artisan schedule:work
    ```

    ```sh
    php artisan websockets:serve
    ```

5. start server

    ```sh
    php artisan serve
    ```

6. access website at

    ```sh
    http://127.0.0.1:8000
    ```

## Screenshots

<p align="center"><img src="screenshots/1.png"></p>
<p align="center"><img src="screenshots/2.png"></p>
<p align="center"><img src="screenshots/3.png"></p>
<p align="center"><img src="screenshots/4.png"></p>
<p align="center"><img src="screenshots/5.png"></p>
<p align="center"><img src="screenshots/6.png"></p>
<p align="center"><img src="screenshots/7.png"></p>
<p align="center"><img src="screenshots/8.png"></p>
<p align="center"><img src="screenshots/9.png"></p>
<p align="center"><img src="screenshots/10.png"></p>
<p align="center"><img src="screenshots/11.png"></p>
<br>

## Video Preview

-   [Youtube Video](https://www.youtube.com/watch?v=38HfwgmgL-8)

## Built With

-   [Laravel](https://laravel.com)

<!-- GETTING STARTED -->
