<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // defaultny password je 'password'
            'api_token' => Str::random(80), // tu sy sa mal dat pouzit aj prikaz str_random(80) ale toto by malo byt bezpecnejsie
            'remember_token' => Str::random(10),
            'is_admin' => false 
        ];
    }

    public function defaultUser()
    {
        return $this->state([
            'name' => 'John Brown',
            'email' => 'jb@pako.sk',
            'password' => Hash::make('dilino', ['rounds' => 12]), //'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password 
            'api_token' => Str::random(80),
            'remember_token' => Str::random(10),
            'is_admin' => true
        ]);
    }

    public function culakUser()
    {
        return $this->state([
            'name' => 'Martin Culak',
            'email' => 'culak.martin@gmail.com',
            'password' => Hash::make('dilino', ['rounds' => 12]), //'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password 
            'api_token' => Str::random(80),
            'remember_token' => Str::random(10),
            'is_admin' => true
        ]);
    }
}
