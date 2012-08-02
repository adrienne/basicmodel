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

    /**
     * Should be instance of Basicmodel
     */
    public function testNewInstance()
    {
        $this->assertInstanceOf('Basicmodel', $this->model);
    }

    /**
     * Class should have correct attributes
     */
    public function testHasAttributes()
    {
        $this->assertClassHasAttribute('attributes', 'Basicmodel');
    }

    /**
     * Class should have correct static attributes
     */
    public function testHasStaticAttributes()
    {
        $this->assertClassHasStaticAttribute('table', 'Basicmodel');
        $this->assertClassHasStaticAttribute('key', 'Basicmodel');
    }

    /**
     * Class should have correct default attribute values
     */
    public function testClassAttributesHaveCorrectValues()
    {
        $this->assertEquals('id', Basicmodel::$key);
        $this->assertTrue(empty(Basicmodel::$table));
    }

    /**
     * Instance method `is_new()` should behave properly
     * 
     * - It should return `TRUE` if the model does not have value assigned to its key
     * - It should return `FALSE` if the model has value assigned to its key
     */
    public function testIsNew()
    {
        $this->assertTrue($this->model->is_new());

        $this->model->{Basicmodel::$key} = null;
        $this->assertTrue($this->model->is_new());

        $this->model->{Basicmodel::$key} = 10;
        $this->assertFalse($this->model->is_new());
    }

    /**
     * Instance method `table()` should behave properly
     *
     * - It should return model name in plural, if static `$table` is not set
     * - It should return static `$table` if it is set
     *
     * @todo It should return correct plural form (person => people, money => money)
     */
    public function testTable()
    {
        Basicmodel::$table = '';
        $this->assertEquals('basicmodels', $this->model->table());

        Basicmodel::$table = 'users';
        $this->assertEquals('users', $this->model->table());
    }

    /**
     * Static method `table_name()` should behave properly
     *
     * - It should return model name in plural, if static `$table` is not set
     * - It should return static `$table` if it is set
     */
    public function testStaticTableName()
    {
        Basicmodel::$table = '';
        $this->assertEquals('basicmodels', Basicmodel::table_name());

        Basicmodel::$table = 'users';
        $this->assertEquals('users', $this->model->table());
        Basicmodel::$table = '';

        $this->CI->load->model('user');
        $user = new User();
        $this->assertEquals('users', $user->table());
    }
    
}
