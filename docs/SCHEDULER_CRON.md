Cron config:

$ sudo joe /etc/crond.d/scool
* * * * * sergi php /home/sergi/Code/acacha/scool/artisan schedule:run >> /dev/null 2>&1

With Laravel Forge we use Laravel Forge Scheduler option:

A:
```
https://forge.laravel.com/servers/231518#/scheduler
```
Posem:

```
php /home/forge/scool.cat/artisan schedule:run
```

Cada minut.
