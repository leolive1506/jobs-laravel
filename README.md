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

- **OBS:** notificaçõs (artisan make:notification) já é um job, para usá-lo como job basta implementar a classe ShouldQueue e disparar a notificação diretamente do pai
```php
User::limit(10)->get()->each(fn ($user) => SendNotificationJob::dispatch($user));
// para
User::limit(10)->get()->each(fn ($user) => $user->notify(new SantamJobTop()));
```

# Especificar qual fila
->onQueue('fila')

# Adicionar delay
```php
        ProcessPodcast::dispatch($podcast)
                    ->delay(now()->addMinutes(10));
```
