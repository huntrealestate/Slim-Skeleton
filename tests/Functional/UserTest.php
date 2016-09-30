<?php

namespace Tests\Functional;

class UserTest extends BaseTestCase

{
    public function testRetrieveModel()
    {
        $app = $this->getApp();
        $model= $app->getContainer()->model;
        
        $this->assertArrayHasKey('users',$model);
        $this->assertEmpty($model);
       //TODO this does NOT fail, which is wrong. Further tests required 
    }
    
    
}
