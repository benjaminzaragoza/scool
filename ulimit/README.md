# Laravel websockets limit:

https://docs.beyondco.de/laravel-websockets/1.0/faq/deploying.html

Copy file laravel-echo.conf to /etc/security/limits.d/laravel-echo.conf 

An create user laravel-echo:

```
laravel-echo
```

Also change:

```
sudo pecl install ev
# or
sudo pecl install event
```
