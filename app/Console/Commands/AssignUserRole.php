<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class AssignUserRole extends Command
{
    protected $signature = 'user:assign-role
                            {user_id : User Id}
                            {role : Role code}';

    protected $description = 'Assign role to user';


    public function handle(): int
    {
        $userId = $this->argument('user_id');
        $roleCode = $this->argument('role');


        $user = User::find($userId);


        if (!$user) {

            $this->error(
                "User with ID {$userId} not found"
            );

            return Command::FAILURE;
        }


        $role = Role::where(
            'code',
            $roleCode
        )->first();


        if (!$role) {

            $this->error(
                "Role '{$roleCode}' not found"
            );

            return Command::FAILURE;
        }


        $user->roles()->sync([
            $role->id
        ]);


        $this->info(
            "Role '{$role->name}' assigned to {$user->name}"
        );


        return Command::SUCCESS;
    }
}
