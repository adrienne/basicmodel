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

        $this->dbfixt('users');

        $this->total = $this->CI->db->count_all('users');
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
        $this->assertTrue($model->save());

        $query = $this->CI->db->last_query();
        $this->assertStringStartsWith('INSERT INTO `users`', $query);

        $count = $this->CI->db->count_all('users');
        $this->assertEquals($this->total + 1, $count);

        $id = $this->CI->db->insert_id();
        $this->assertEquals($id, $model->id);
    }

    /**
     * Instance method `save()` should update the model in the db if it is not new
     */
    public function testOldModelSave()
    {
        $user = User::find(2);
        $user->name = $this->attributes['name'];
        $user->email = $this->attributes['email'];

        $this->assertTrue($user->save());

        $query = $this->CI->db->last_query();
        $this->assertStringStartsWith('UPDATE `users` SET', $query);

        $count = $this->CI->db->count_all('users');
        $this->assertEquals($this->total, $count);
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
        $this->assertEquals($this->total + 1, $count);

        $id = $this->CI->db->insert_id();
        $this->assertEquals($id, $model->id);
    }

    /**
     * Static method `create()` should return `FALSE` if the model was not saved
     */
    public function testStaticCreateReturnsFalse()
    {
        $this->assertFalse(User::create(array_merge($this->attributes, array('id' => 1))));
    }

    /**
     * Instande method `update()` should return `TRUE` on successfull update
     * @return [type] [description]
     */
    public function testUpdateSuccess()
    {
        $model = User::find(2);
        $this->assertTrue($model->update($this->attributes));
    }

    /**
     * Instace method `update()` should run the correct query
     */
    public function testUpdateQuery()
    {
        $model = User::find(2);
        $model->update($this->attributes);

        $query = $this->CI->db->last_query();
        $this->assertStringStartsWith("UPDATE `users` SET `id` = '2'", $query);
    }
    
}
