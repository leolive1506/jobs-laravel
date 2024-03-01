# Create job
php artisan make:job NameJob
# Work job
php artisan queue:work

# Dicas
AO trabalhar com jobs, o ideal é que seja executado rapidamente
- Por default o job tem um tempo de execução
- uma solução para jobs em massa é criar um job pai e um job filho
    - SendNotificationsJob
    - SendNotificationJob
- Com isso cada usuário terá seu job e seu tempo de execução e o trabalho do job pai será apenas disparar os outros eventos
