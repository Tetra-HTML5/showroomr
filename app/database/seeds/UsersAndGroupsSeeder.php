<?php

class UsersAndGroupsSeeder extends Seeder {

    private function createGroup($name, $permissions){
        return Sentry::getGroupProvider()->create(array('name' => $name));
    }

    private function createAdminGroup($name){
        $this->createGroup($name, array('admin'=>1));
    }

    public function run()
    {

        $groups = array('Administrator', 'Chatmedewerker', 'Medewerker');
        foreach($groups as $id => $name){
            $this->createAdminGroup($name);
        }

        Sentry::getUserProvider()->create(array(
            'email'       => 'admin@pl.com',
            'password'    => 'testtest',
            'first_name'  => 'Admin',
            'last_name'   => 'Boss',
            'activated'   => 1,
        ));

        $user = Sentry::getUserProvider()->findByLogin('admin@pl.com');
        $user->addGroup(Sentry::getGroupProvider()->findByName('Administrator'));
    }

}
