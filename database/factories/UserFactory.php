<?php

/** @var Factory $factory */

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factory;


$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'username' => $faker->userName,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'api_token'      => 'eeuu21u312yda7sihdljakasdi34u9ihf4omre9uvrmERJKJEKJFVNDdeEa3'
    ];
});

$factory->state(User::class, 'admin', [
    'name'     => 'Administrator',
    'username' => 'admin',
    'password' => Hash::make('admin'),
]);
