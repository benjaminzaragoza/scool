# Laravel websockets limit:

https://docs.beyondco.de/laravel-websockets/1.0/faq/deploying.html

Copy file laravel-echo.conf to /etc/security/limits.d/laravel-echo.conf 

An create user laravel-echo:

```
$ sudo useradd laravel-echo
```

Also change:

```
sudo pecl install ev
# or
sudo pecl install event
```

I also appended line:

```
extension=ev.so
```

to the end of the file:

```
/etc/php/7.2/fpm/php.ini
```
