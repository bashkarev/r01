<?php

use bashkarev\r01\soap\Connection;


class ConnectionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Connection
     */
    public $client;

    protected function setUp()
    {
        $this->client = Connection::get();
    }

    public function testConnect()
    {
        $this->assertNull($this->client->open());
    }

    public function testLogin()
    {
        $this->client->login();
        $this->assertTrue(isset($this->client->soap->_cookies['SOAPClient']));
    }

    public function testLogOut()
    {
        $this->client->logOut();
        $this->assertTrue(!isset($this->client->soap->_cookies['SOAPClient']));
    }

}