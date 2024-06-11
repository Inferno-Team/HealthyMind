<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Channel;
use App\Models\ChannelSubscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channel = Channel::create([
            'name' => "admin-channel",
            'type' => 'private',
        ]);
        ChannelSubscription::create([
            'channel_id' => $channel->id,
            'user_id' => Admin::first()->id,
        ]);
    }
}
