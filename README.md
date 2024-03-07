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

# [Batch](https://laravel.com/docs/10.x/queues#job-batching)
- sequencia de processos que um depende do outro
    - ex: compra: valida cartão -> cobrança
- utliza banco de dados para controlar
```sh
php artisan queue:batches-table
 
php artisan migrate
```
- Jobs precisam ter trait Batchable
- allowFailures
    - fala que se um job falhar pode executar os outros, um não depende do outro

# [Horizon](https://laravel.com/docs/10.x/horizon#main-content)
- Painel para gerenciamenot de filas
```sh
composer require laravel/horizon
php artisan horizon:install
# toda vez que fizer alteração no código, tem que parar esse comando e rodar de novo
php artisan horizon
```
