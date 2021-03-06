<?php

namespace Tests;

use Modules\Accounts\Entities\Admin;
use Modules\Accounts\Entities\Customer;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Set the currently logged in admin for the application.
     *
     * @param null $driver
     * @return \Modules\Accounts\Entities\Admin
     */
    public function actingAsAdmin($driver = null)
    {
        $admin = factory(Admin::class)->create();

        $this->be($admin, $driver);

        return $admin;
    }

    /**
     * Set the currently logged in customer for the application.
     *
     * @param null $driver
     * @return \Modules\Accounts\Entities\Customer
     */
    public function actingAsCustomer($driver = null)
    {
        $customer = factory(Customer::class)->create();

        $this->be($customer, $driver);

        return $customer;
    }
}
