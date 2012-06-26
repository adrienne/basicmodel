<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @group Lib
 */

class BasicmodelTest extends CIUnit_TestCase
{

    public function setUp()
    {
        $this->model = new Basicmodel();
    }

    public function testNewInstance()
    {
        $this->assertInstanceOf('Basicmodel', $this->model);
    }

    public function testHasAttributes()
    {
        $this->assertClassHasAttribute('attributes', 'Basicmodel');
    }

    public function testHasStaticAttributes()
    {
        $this->assertClassHasStaticAttribute('table', 'Basicmodel');
        $this->assertClassHasStaticAttribute('key', 'Basicmodel');
    }

    public function testIsNew()
    {
        $this->assertTrue($this->model->is_new(), 'When key not set, should be TRUE');

        $this->model->{Basicmodel::$key} = 10;
        $this->assertFalse($this->model->is_new(), 'When key is set, should be FALSE');
    }
    
}
