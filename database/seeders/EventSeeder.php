<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        
        if (!$user) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }

        $events = [
            [
                'title' => 'Client Meeting - ABC Corp',
                'description' => 'Quarterly review meeting with ABC Corporation to discuss project progress and future requirements.',
                'start' => Carbon::now()->addDays(1)->setTime(9, 0),
                'end' => Carbon::now()->addDays(1)->setTime(10, 30),
                'type' => 'meeting',
                'status' => 'pending',
                'color' => '#4f46e5',
                'all_day' => false,
                'created_by' => $user->id,
            ],
            [
                'title' => 'Product Launch Planning',
                'description' => 'Strategic planning session for the new product launch scheduled for next quarter.',
                'start' => Carbon::now()->addDays(3)->setTime(14, 0),
                'end' => Carbon::now()->addDays(3)->setTime(16, 0),
                'type' => 'meeting',
                'status' => 'pending',
                'color' => '#4f46e5',
                'all_day' => false,
                'created_by' => $user->id,
            ],
            [
                'title' => 'Team Building Event',
                'description' => 'Annual team building activities and lunch.',
                'start' => Carbon::now()->addDays(7)->setTime(0, 0),
                'end' => Carbon::now()->addDays(7)->setTime(23, 59),
                'type' => 'other',
                'status' => 'pending',
                'color' => '#6b7280',
                'all_day' => true,
                'created_by' => $user->id,
            ],
            [
                'title' => 'Submit Monthly Report',
                'description' => 'Complete and submit the monthly sales and inventory report to management.',
                'start' => Carbon::now()->addDays(5)->setTime(16, 0),
                'end' => Carbon::now()->addDays(5)->setTime(17, 0),
                'type' => 'task',
                'status' => 'pending',
                'color' => '#06b6d4',
                'all_day' => false,
                'created_by' => $user->id,
            ],
            [
                'title' => 'Doctor Appointment',
                'description' => 'Regular health checkup with Dr. Smith.',
                'start' => Carbon::now()->addDays(10)->setTime(11, 0),
                'end' => Carbon::now()->addDays(10)->setTime(12, 0),
                'type' => 'appointment',
                'status' => 'pending',
                'color' => '#10b981',
                'all_day' => false,
                'created_by' => $user->id,
            ],
            [
                'title' => 'Pay Monthly Subscription',
                'description' => 'Reminder to pay the software subscriptions for next month.',
                'start' => Carbon::now()->addDays(2)->setTime(9, 0),
                'end' => Carbon::now()->addDays(2)->setTime(9, 30),
                'type' => 'reminder',
                'status' => 'pending',
                'color' => '#f59e0b',
                'all_day' => false,
                'created_by' => $user->id,
            ],
            [
                'title' => 'Inventory Audit',
                'description' => 'Quarterly inventory audit and stock verification.',
                'start' => Carbon::now()->addDays(14)->setTime(8, 0),
                'end' => Carbon::now()->addDays(14)->setTime(17, 0),
                'type' => 'task',
                'status' => 'pending',
                'color' => '#06b6d4',
                'all_day' => false,
                'created_by' => $user->id,
            ],
            [
                'title' => 'Board Meeting',
                'description' => 'Monthly board meeting to discuss company performance and strategic decisions.',
                'start' => Carbon::now()->addDays(21)->setTime(10, 0),
                'end' => Carbon::now()->addDays(21)->setTime(12, 0),
                'type' => 'meeting',
                'status' => 'pending',
                'color' => '#4f46e5',
                'all_day' => false,
                'created_by' => $user->id,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }

        $this->command->info('Created ' . count($events) . ' sample events.');
    }
}
