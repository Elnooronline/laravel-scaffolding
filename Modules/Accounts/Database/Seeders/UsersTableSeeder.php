<?php

use Illuminate\Database\Seeder;
use  Modules\Accounts\Entities\Admin;
use Modules\Accounts\Entities\Supervisor;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\Modules\Accounts\Entities\Admin::class)->create([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
        ]);
        factory(\Modules\Accounts\Entities\Admin::class)->create([
            'name' => 'Mohamed Said',
            'email' => 'themsaid@gmail.com',
        ]);

        factory(\Modules\Accounts\Entities\Admin::class)->create([
            'name' => 'Dries Vints',
            'email' => 'dries.vints@gmail.com',
        ]);

        factory(\Modules\Accounts\Entities\Customer::class)->create([
            'name' => 'Jeffrey Way',
            'email' => 'jeffrey@laracasts.com',
        ]);
        factory(\Modules\Accounts\Entities\Customer::class)->create([
            'name' => 'Tom Witkowski',
            'email' => 'dev.gummibeer@gmail.com',
        ]);
        factory(\Modules\Accounts\Entities\Customer::class)->create([
            'name' => 'Jonas Staudenmeir',
            'email' => 'mail@jonas-staudenmeir.de',
        ]);
        factory(\Modules\Accounts\Entities\Customer::class)->create([
            'name' => 'Freek Van der Herten',
            'email' => 'freek@spatie.be',
        ]);
        factory(\Modules\Accounts\Entities\Customer::class)->create([
            'name' => 'Raphael Jackstadt',
            'email' => 'info@rejack.de',
        ]);
        factory(\Modules\Accounts\Entities\Customer::class)->create([
            'name' => 'Weblate (bot)',
            'email' => 'hosted@weblate.org',
        ]);
    }
}
