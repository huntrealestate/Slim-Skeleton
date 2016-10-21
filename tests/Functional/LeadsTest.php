<?php

namespace Tests\Functional;

class LeadsTest extends BaseTestCase
{
    /**
    * Test that all pages behind /auth work
    */
    //TODO: Currently throws 500 status
    public function testGetAuthPages()
    {
        $response=$this->runApp('GET', '/auth/dashboard/leads/all/');
        
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
    * Test that the login page exists
    */
    public function testGetLoginPage()
    {
        $response=$this->runApp('GET', '/login/');    
        $this->assertEquals(200, $response->getStatusCode());
        $response=$this->runApp('GET', '/login');    
        $this->assertEquals(200, $response->getStatusCode());
   
    }

    /**
    * Test that the logout page exists 
    */
    //TODO: Check that when authenticated the logout page gives a 200 status
    //TODO: Without authentication /logout/ should redirect, currently gives 500 status
    public function testGetLogoutPage()
    {
        $response=$this->runApp('GET', '/logout/');
        $this->assertEquals(302, $response->getStatusCode());
        $response=$this->runApp('GET', '/logout');
        $this->assertEquals(302, $response->getStatusCode());

    }
}
