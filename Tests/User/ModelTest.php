<?php

require_once(dirname(__FILE__) . '/../../params/Configuration.php');
require_once(dirname(__FILE__) . '/../../User/Model.php');

// @TODO namespace

/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-07-11 at 11:34:18.
 */
class ModelTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Model
     */
    protected $object;
    
    protected $paramList;
    
    protected $idList;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Model;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }
    
    protected function addData($prefix = '') {
        // Populate database with test data, and save IDs generated.
        $paramList = [];
        $idList = [];
        for ($i = 0; $i < Configuration::TEST_DATA_SIZE; $i++) {
            $iStr = strval($i);
            $paramList[$i] = [
                'e' => $prefix . 'email' . $iStr . '@' . $prefix . 'Test.dev',
                'fn' => $prefix . 'first' . $iStr,
                'ln' => $prefix . 'last' . $iStr,
                'p' => $prefix . 'password' . $iStr,
            ];
            // Save the list of IDs for created users.
            $idList[$i] = $this->object->create($paramList[$i]);
        }
        return ['params' => $paramList, 'ids' => $idList];
    }
    
    protected function removeData($data) {
        // Delete data from test database.
        for ($i = 0; $i < Configuration::TEST_DATA_SIZE; $i++) {
            $this->object->delete($data['ids'][$i]);
        }
    }
    
    protected function validateData($prefix = '', $data) {
        // Read all entries written to database and compare with original parameters.
        $paramList = $data['params'];
        $idList = $data['ids'];

        for ($i = 0; $i < Configuration::TEST_DATA_SIZE; $i++) {
            // Read the data from the setup
            $result = $this->object->read($idList[$i]);
            
            // For delete, should not be able to read the data, returns a false.
            if ($prefix == 'delete') {
                $this->assertFalse($result);
            }
            else {
                // Check each column for other cases of validation
                $this->assertEquals($paramList[$i]['e'], $result['email']);
                $this->assertEquals($paramList[$i]['fn'], $result['first_name']);
                $this->assertEquals($paramList[$i]['ln'], $result['last_name']);
                $this->assertEquals($idList[$i], $result['id_users']);
                // Verify the password hash.
                $this->assertTrue(password_verify($paramList[$i]['p'], $result['passwd']));
            }
        }
    }

    /**
     * 
     */
    public function testCreate() {
        $data = $this->addData('create');
        $this->validateData('create', $data);
        $this->removeData($data);
    }

    /**
     * 
     */
    public function testUpdate() {
        // Create test data.
        $data = $this->addData('update');
        
        // Modify the entry, not the email in this test.
        for ($i = 0; $i < Configuration::TEST_DATA_SIZE; $i++) {
            $data['params'][$i]['id'] = $data['ids'][$i];
            $data['params'][$i]['fn'] = $data['params'][$i]['fn'] . bin2hex(random_bytes(4));
            $data['params'][$i]['ln'] = $data['params'][$i]['ln'] . bin2hex(random_bytes(4));
            $data['params'][$i]['p'] = $data['params'][$i]['p'] . bin2hex(random_bytes(4));
            
            // Call update with the modded data.
            $this->object->update($data['params'][$i]);
        }

        // Test the data.
        $this->validateData('update', $data);
        
        // Remove the test data.
        $this->removeData($data);
    }

    /**
     * 
     */
    public function testRead() {
        $data = $this->addData('read');
        $this->validateData('read', $data);
        $this->removeData($data);
    }

    /**
     * 
     */
    public function testDelete() {
        // Here we add data, then remove it first and validate that all have been removed.
        $data = $this->addData('delete');
        $this->removeData($data);
        $this->validateData('delete', $data);
    }

    /**
     * 
     */
    public function testGetErrors() {
        $this->assertEquals(1, 1);
    }

    /**
     * 
     */
    public function testAddError() {
        $this->assertEquals(1, 1);
    }

}
