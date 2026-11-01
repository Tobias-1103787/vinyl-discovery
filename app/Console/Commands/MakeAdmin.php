<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:make-admin')]
#[Description('Command description')]
class MakeAdmin extends Command
{
    /**
     * Execute the console command.
     */
    protected $signature = 'user:make-admin {email}';
    protected $description = 'Geef een bestaande gebruiker adminrechten';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('Gebruiker niet gevonden.');
            return self::FAILURE;
        }

        $user->forceFill(['admin' => true])->save();
        $this->info("{$user->email} is nu admin.");

        return self::SUCCESS;
    }
}
