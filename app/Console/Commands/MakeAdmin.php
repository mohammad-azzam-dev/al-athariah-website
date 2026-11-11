<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MakeAdmin extends Command
{
    protected $signature = 'athariyah:admin {email : Admin email} {--name= : Display name} {--password= : Password (asked for if omitted)}';

    protected $description = 'Create an admin for the dashboard, or promote an existing user and reset their password';

    public function handle(): int
    {
        $email = Str::lower($this->argument('email'));
        $user = User::where('email', $email)->first();
        $name = $this->option('name') ?: ($user?->name ?: $this->ask('الاسم (Name)', 'Admin'));
        $password = $this->option('password') ?: $this->secret('كلمة المرور (Password, min 8)');

        $v = Validator::make(compact('email', 'name', 'password'), [
            'email' => ['required', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if ($v->fails()) {
            foreach ($v->errors()->all() as $e) {
                $this->error($e);
            }

            return self::FAILURE;
        }

        $user ??= new User(['email' => $email]);
        $user->name = $name;
        $user->password = $password;
        $user->is_admin = true;
        $user->save();

        $this->info(($user->wasRecentlyCreated ? 'Created' : 'Updated').' admin '.$email.'. Sign in at '.url('/admin/login'));

        return self::SUCCESS;
    }
}
