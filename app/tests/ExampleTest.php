<?php

class ExampleTest extends TestCase
{

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicAuthAtLogin()
    {
        // We make sure that our first request requires BASIC auth.
        $crawler = $this->client->request('GET', '/');
        $this->assertFalse($this->client->getResponse()->isOk());
    }

}
