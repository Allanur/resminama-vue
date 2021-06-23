<?php

/** @var Factory $factory */

use App\Paper;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Paper::class, function (Faker $faker) {
    return [
        'name' => $faker->words($faker->numberBetween(2, 7), true),
    ];
});

$factory->afterCreating(Paper::class, function (Paper $paper, Faker $faker) {

    if ($faker->boolean(80)) {
        if (Paper::exists()) {
            if (Paper::whereRoot(false)->count() > 8) {
                $paper->parent_id = Paper::whereRoot(false)->whereKeyNot($paper->id)->pluck('id')->random();
            } else {
                $paper->parent_id = Paper::whereKeyNot($paper->id)->pluck('id')->random();
            }
        }
    }

    $paper->root = $paper->parent_id == null;
    $paper->leaf = $paper->children()->doesntExist();
    $paper->save();

    $paper->parent()->update(['leaf' => false]);
});