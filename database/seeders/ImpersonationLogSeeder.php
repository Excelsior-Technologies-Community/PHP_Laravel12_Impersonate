<?php

namespace Database\Seeders;

use App\Models\ImpersonationLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ImpersonationLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();
        $users = User::where('is_admin', false)->get();

        if (!$admin || $users->isEmpty()) {
            return;
        }

        $reasons = [
            'Customer Support (Ticket Troubleshooting)',
            'Billing & Subscription Dispute Investigation',
            'Bug Reproduction (Reported UI Glitch)',
            'Compliance & Security Access Audit',
            'User Onboarding & Feature Walkthrough',
        ];

        $modes = ['read_only', 'read_only', 'read_only', 'full_access']; // 75% Safe Mode

        // Create 24 realistic logs across the past 14 days
        for ($i = 24; $i >= 1; $i--) {
            $daysAgo = (int) floor($i / 2);
            $targetUser = $users->random();
            $mode = $modes[array_rand($modes)];
            $reason = $reasons[array_rand($reasons)];
            $ticketNumber = 'TICK-' . str_pad((string) (8400 + $i), 4, '0', STR_PAD_LEFT);
            $durationMinutes = [5, 15, 30, 60][array_rand([5, 15, 30, 60])];
            $sessionLengthSeconds = rand(45, $durationMinutes * 60);

            $createdAt = Carbon::now()->subDays($daysAgo)->subMinutes(rand(10, 400));
            $updatedAt = (clone $createdAt)->addSeconds($sessionLengthSeconds);

            ImpersonationLog::create([
                'admin_id' => $admin->id,
                'user_id' => $targetUser->id,
                'ticket_id' => $ticketNumber,
                'reason' => $reason,
                'access_mode' => $mode,
                'duration_minutes' => $durationMinutes,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/130.0.0.0 Safari/537.36',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }
    }
}
