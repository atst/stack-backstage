<?php

namespace tests\integration;

use Atst\StackBackstage;
use Stack\CallableHttpKernel;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StackBackstageTest extends \PHPUnit_Framework_TestCase
{
    protected $maintenancePath;

    public function setUp()
    {
        $this->maintenancePath = __DIR__.'/maintenance.html';
    }

    public function tearDown()
    {
        @unlink($this->maintenancePath);
    }

    /** @test */
    public function itShouldPassThroughToTheApp()
    {
        $request = Request::create("/");
        $response = $this->getApp()->handle($request);

        $this->assertEquals("Hello World!", $response->getContent());
    }

    /** @test */
    public function itShould503IfFileExists()
    {
        touch($this->maintenancePath);

        $request = Request::create("/");
        $response = $this->getApp()->handle($request);

        $this->assertEquals(503, $response->getStatusCode());
    }

    /** @test */
    public function itShouldRespondWithTheFilesContents()
    {
        file_put_contents($this->maintenancePath, "Uh oh");

        $request = Request::create("/");
        $response = $this->getApp()->handle($request);

        $this->assertEquals("Uh oh", $response->getContent());
    }

    protected function getApp()
    {
        $app = new CallableHttpKernel(function (Request $request) {
            return new Response('Hello World!');
        });

        $backstage = new StackBackstage($app, $this->maintenancePath);

        return $backstage;
    }
}
