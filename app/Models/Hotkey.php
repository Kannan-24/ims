<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotkey extends Model
{
    protected $fillable = [
        'user_id',
        'action_name',
        'hotkey_combination',
        'description',
        'action_url',
        'action_type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the user that owns the hotkey.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted hotkey combination for display
     */
    public function getFormattedHotkeyAttribute(): string
    {
        return str_replace('+', ' + ', $this->hotkey_combination);
    }

    /**
     * Check if hotkey combination is valid
     */
    public static function isValidCombination(string $combination): bool
    {
        $validKeys = ['ctrl', 'shift', 'alt', 'meta', 'cmd'];
        $parts = explode('+', strtolower($combination));
        
        if (count($parts) < 2) {
            return false;
        }
        
        $lastKey = array_pop($parts);
        if (strlen($lastKey) !== 1 && !in_array($lastKey, ['enter', 'space', 'tab', 'escape'])) {
            return false;
        }
        
        foreach ($parts as $part) {
            if (!in_array(trim($part), $validKeys)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get default hotkeys for seeding
     */
    public static function getDefaultHotkeys(): array
    {
        return [
            [
                'action_name' => 'Dashboard',
                'hotkey_combination' => 'Ctrl+Shift+D',
                'description' => 'Navigate to Dashboard',
                'action_url' => '/dashboard',
                'action_type' => 'navigate'
            ],
            [
                'action_name' => 'Calendar',
                'hotkey_combination' => 'Ctrl+Shift+C',
                'description' => 'Open Calendar',
                'action_url' => '/calendar',
                'action_type' => 'navigate'
            ],
            [
                'action_name' => 'Hotkey Manager',
                'hotkey_combination' => 'Ctrl+Shift+K',
                'description' => 'Open Hotkey Manager',
                'action_url' => '/hotkeys',
                'action_type' => 'navigate'
            ],
            [
                'action_name' => 'Search',
                'hotkey_combination' => 'Ctrl+K',
                'description' => 'Open Search Modal',
                'action_url' => '#',
                'action_type' => 'modal'
            ],
            [
                'action_name' => 'Settings',
                'hotkey_combination' => 'Ctrl+Comma',
                'description' => 'Open Settings',
                'action_url' => '/settings',
                'action_type' => 'navigate'
            ]
        ];
    }
}
