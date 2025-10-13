<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotkey extends Model
{
    use HasFactory, HasUuids;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';
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
            ],
            // Supplier Management Hotkeys
            [
                'action_name' => 'Suppliers List',
                'hotkey_combination' => 'Ctrl+Shift+S',
                'description' => 'Navigate to Suppliers List',
                'action_url' => '/ims/suppliers',
                'action_type' => 'navigate'
            ],
            [
                'action_name' => 'New Supplier',
                'hotkey_combination' => 'Ctrl+Shift+N',
                'description' => 'Create New Supplier',
                'action_url' => '/ims/suppliers/create',
                'action_type' => 'navigate'
            ],
            [
                'action_name' => 'Supplier Help',
                'hotkey_combination' => 'Ctrl+Shift+H',
                'description' => 'Open Supplier Help Page',
                'action_url' => '/ims/suppliers/help',
                'action_type' => 'navigate'
            ],
            [
                'action_name' => 'Search Suppliers',
                'hotkey_combination' => 'Ctrl+F',
                'description' => 'Focus on Supplier Search',
                'action_url' => '#',
                'action_type' => 'function'
            ],
            // Customer Management Hotkeys
            [
                'action_name' => 'Customers List',
                'hotkey_combination' => 'Ctrl+Shift+U',
                'description' => 'Navigate to Customers List',
                'action_url' => '/ims/customers',
                'action_type' => 'navigate'
            ],
            [
                'action_name' => 'New Customer',
                'hotkey_combination' => 'Ctrl+Shift+M',
                'description' => 'Create New Customer',
                'action_url' => '/ims/customers/create',
                'action_type' => 'navigate'
            ]
        ];
    }
}
