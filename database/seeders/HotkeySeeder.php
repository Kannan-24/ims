<?php

namespace Database\Seeders;

use App\Models\Hotkey;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotkeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (assuming admin user exists)
        $user = User::first();
        
        if (!$user) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }

        $defaultHotkeys = Hotkey::getDefaultHotkeys();
        
        foreach ($defaultHotkeys as $hotkeyData) {
            // Check if hotkey already exists for this user
            $exists = Hotkey::where('user_id', $user->id)
                           ->where('hotkey_combination', $hotkeyData['hotkey_combination'])
                           ->exists();
                           
            if (!$exists) {
                Hotkey::create([
                    'user_id' => $user->id,
                    ...$hotkeyData
                ]);
            }
        }
        
        $this->command->info('Default hotkeys seeded successfully for user: ' . $user->name);
    }
}
