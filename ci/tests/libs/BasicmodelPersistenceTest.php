<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @group Lib
 */

class BasicmodelPersistenceTest extends CIUnit_TestCase
{

    /**
     * Load `User` model and set attributes for each test
     */
    public function setUp()
    {
        $this->CI->load->model('user');

        $this->attributes = array(
            'name' => 'Mindaugas Bujanauskas',
            'email' => 'mindaugas@example.com'
        );
    }

    /**
     * Truncate `users` table in the database
     */
    public function tearDown()
    {
        $this->CI->db->from('users');
        $this->CI->db->truncate();
    }

    /**
     * Instance method `save()` should save the model to the db if it is new and set ID
     */
    public function testNewModelSave()
    {
        $model = new User($this->attributes);
        $model->save();

        $query = $this->CI->db->last_query();
        $this->assertStringStartsWith('INSERT INTO `users`', $query);

        $count = $this->CI->db->count_all('users');
        $this->assertEquals(1, $count);

        $id = $this->CI->db->insert_id();
        $this->assertEquals($id, $model->id);
    }

    /**
     * Instance method `save()` should update the model in the db if it is not new
     */
    public function testOldModelSave()
    {
        
    }

    /**
     * Static mehod `create()` should save the model in the DB
     */
    public function testStaticCreateSavesModel()
    {
        $model = User::create($this->attributes);
        $this->assertInstanceOf('User', $model);

        $query = $this->CI->db->last_query();
        $this->assertStringStartsWith('INSERT INTO `users`', $query);

        $count = $this->CI->db->count_all('users');
        $this->assertEquals(1, $count);

        $id = $this->CI->db->insert_id();
        $this->assertEquals($id, $model->id);
    }
    
}
