<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @group Lib
 */

class BasicmodelAttributesTest extends CIUnit_TestCase
{

    public function setUp()
    {
        $this->attributes = array(
            'name' => 'Mindaugas Bujanauskas',
            'email' => 'mindaugas@example.com'
        );
    }

    public function testNoAttributes()
    {
        $model = new Basicmodel();
        $this->assertObjectNotHasAttribute('name', $model);
    }

    public function testHasAttributes()
    {
        $model = new Basicmodel($this->attributes);
        $this->assertArrayHasKey('name', $model->attributes);
        $this->assertArrayHasKey('email', $model->attributes);
    }
    
}
