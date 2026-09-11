<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

User::query()->delete();
$user = User::factory()->create([
    'user_code' => 'AB12345',
    'password' => Hash::make('password'),
]);

dump('user', $user->toArray());
dump('lookup count', User::where('user_code', 'AB12345')->count());
dump('hash check', Hash::check('password', $user->password));

dump('attempt', Auth::attempt(['user_code' => 'AB12345', 'password' => 'password']));
dump('check after attempt', Auth::check());

auth()->logout();

$request = Illuminate\Http\Request::create('/login', 'POST', ['user_code' => 'AB12345', 'password' => 'password']);
$request->setLaravelSession(app('session.store'));
$app['router']->getRoutes()->match($request);

// direct controller call
$route = app('router')->getRoutes()->match($request);
$action = $route->getAction();
dump('matched action', $action['uses'] ?? $action['controller'] ?? null);

$login = new App\Http\Requests\Auth\LoginRequest();
$login = App\Http\Requests\Auth\LoginRequest::create('/login', 'POST', ['user_code' => 'AB12345', 'password' => 'password']);
$login->setContainer(app());
$login->setLaravelSession(app('session.store'));
$login->validateResolved();
$login->authenticate();
dump('after direct authenticate check', Auth::check());
