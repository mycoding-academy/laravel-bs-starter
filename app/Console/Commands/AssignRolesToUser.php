<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class AssignRolesToUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:assign-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign role(s) to user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->ask('Input user email');
        $this->line('<comment>If multiple roles, separate with comma!</comment>');
        $roles = $this->ask('Roles to assign');

        $model = config('auth.model') ?: config('auth.providers.users.model');

        if (!$user = $model::where('email', $email)->first()) {
            return $this->error('User doesnot exists');
        }

        $this->assignRoles($user, $roles);
    }

    protected function assignRoles($user, $roles)
    {
        try{
            $user->assignRole($this->toArray($roles));    
        }catch(RoleDoesNotExist $e){
            return $this->error('Role(s) doesnot exists!');
        }catch(\Exception $e) {
            return $this->error($e->getMessage());
        }    
    }

    protected function toArray($roles)
    {
        return collect(explode(',', $roles))
            ->flatten()
            ->map(function($role) {
                return trim($role);
            });
    }
}
