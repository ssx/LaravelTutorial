<?php


namespace Tests;


use App\Module;
use App\Role;
use App\User;

trait TimetableSetupTesting {

    private function createUserAs(string $authRole)
    {
        $user = factory(User::class)->create();
        $role = new Role();
        $role->user_id = $user->id;
        $role->role = $authRole;
        $role->save();

        return $user;
    }

    /**
     * @param $values
     * @return array
     */
    private function idsAsArray($values)
    {
        return $values->map(
            function ($val) {
                return $val->id;
            }
        )->sort()->toArray();
    }

    private function setup_one_user_with_one_module(): array
    {
        $user = $this->createUserAs('student');
        $module = factory(Module::class)->create();
        $user->modules()->attach($module);
        return array ($user, $module);
    }
}
