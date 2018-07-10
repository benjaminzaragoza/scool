Cron config:

$ sudo joe /etc/crond.d/scool
* * * * * sergi php /home/sergi/Code/acacha/scool/artisan schedule:run >> /dev/null 2>&1

With Laravel Forge we use Laravel Forge Scheduler option.