<?php

namespace Tests\Functional;

class LeadsTest extends BaseTestCase
{
    //TODO: Ensure that all /auth, the /login/ and /logout/ routes are reachable
    
    /**
    * Test that all pages behind /auth work
    */
    public function testGetAuthPages()
    {
        $response=$this->runApp('GET', '/auth/');
        
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
    * Test that the login page exists
    */
    public function testGetLoginPage()
    {
        $response=$this->runApp('GET', '/login/');
        
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
    * Test that the logout page exists 
    */
    public function testGetLogoutPage()
    {
        $response=$this->runApp('GET', '/logout/');
        
        $this->assertEquals(200, $response->getStatusCode());
    }
}
