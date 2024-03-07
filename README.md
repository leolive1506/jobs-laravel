# Create job
php artisan make:job NameJob
- Work job
```sh
php artisan queue:work
```
- Especificar qual fila
```php
->onQueue('fila')
```

- Adicionar delay
```php
ProcessPodcast::dispatch($podcast)->delay(now()->addMinutes(10));
```

## [Batch](https://laravel.com/docs/10.x/queues#job-batching)
- sequencia de processos que podem depender do outro
    - ex: compra: valida cartão -> cobrança
- utliza banco de dados para controlar
- possivel acompanhar quantos "%" já foi feito
```sh
php artisan queue:batches-table
 
php artisan migrate
```
- Jobs precisam ter trait Batchable
- allowFailures
    - fala que se um job falhar pode executar os outros, um não depende do outro
## cancelar batch
- Ao cancelar (batch->cancel()), vai ao banco e cancela
    - porém, como fila roda no redis, é muito mais rapido que uma consulta no banco
    - o qeu pode acontecer de rodar alguns jobs mesmo não querendo
    - para verificar
```php
$user1 = User::query()->offset(0)->first();
$user2 = User::query()->offset(1)->first();
$user3 = User::query()->offset(3)->first();

Bus::batch([
    new TestExceptionJob($user1, true),
    new TestExceptionJob($user2, false),
    new TestExceptionJob($user3, false)
])->catch(function (Batch $batch, Throwable $e) {
    // First batch job failure detected...
    info('Primeiro job falhou: ' . $e);
    $batch->cancel();
})->dispatch();
```
- no job
```php
// se comentar o sleep, alguns irão passar, se não todos
sleep(5);
info('Cancelado: ' . json_encode($this->batch()->cancelled()));
if ($this->batch()->cancelled()) {
    info('batch cancelado');
    return;
}

info($this->user->name);


if ($this->needsToFail) {
    throw new Exception(self::class, 1);
}
```
## Chain
- deixa uma depencia muito forte entre os jobs e so para o próximo, caso tenha sucesso no anterior

## Batch X Chain
- batch eu acompanho um progresso de jobs e posso querer não executar um job caso o batch seja cancelado
- no chain, um job depende do sucesso do anterior
- mas posso ter dentro batch um chain para ter o controle de um job p outro

# [Horizon](https://laravel.com/docs/10.x/horizon#main-content)
- Painel para gerenciamenot de filas
```sh
composer require laravel/horizon
php artisan horizon:install
# toda vez que fizer alteração no código, tem que parar esse comando e rodar de novo
php artisan horizon
```

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
