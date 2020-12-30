<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(PMW\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'id' => $faker->isbn10,
        'nama' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'request' => true
    ];
});

$factory->define(PMW\Models\Proposal::class, function(Faker\Generator $faker){
    return [
        'judul' => $faker->sentence,
        'direktori' => 'dir',
        'usulan_dana' => $faker->numberBetween(2000000,5000000),
        'abstrak' => $faker->text(300),
        'keyword' => 'keyword',
        'jenis_usaha' => 'barang'
    ];
});