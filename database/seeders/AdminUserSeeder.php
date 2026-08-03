<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Permet de relancer les seeders autant de fois qu'on veut sans créer plusieurs comptes.
    User::updateOrCreate(
      ['email' => 'elviredev@gmail.com'],
      [
        'name' => 'Elvire',
        'password' => Hash::make('Elvire*123456'),
        'avatar_path' => 'avatars/default-avatar.jpg',
      ]
    );
  }
}
