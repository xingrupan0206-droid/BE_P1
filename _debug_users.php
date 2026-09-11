<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = App\Models\User::get(['id', 'name', 'email', 'rolename']);

foreach ($users as $user) {
    echo json_encode($user->toArray()) . PHP_EOL;
}