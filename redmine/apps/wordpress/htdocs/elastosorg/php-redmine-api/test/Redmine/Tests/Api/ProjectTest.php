<?php

namespace Redmine\Tests\Api;

use Redmine\Api\Project;

/**
 * @coversDefaultClass Redmine\Api\Project
 * @author     Malte Gerth <mail@malte-gerth.de>
 */
class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test all()
     *
     * @covers ::all
     * @test
     *
     * @return void
     */
    public function testAllReturnsClientGetResponse()
    {
        // Test values
        $getResponse = 'API Response';

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('get')
            ->with('/projects.json')
            ->willReturn($getResponse);

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->all());
    }

    /**
     * Test all()
     *
     * @covers ::all
     * @test
     *
     * @return void
     */
    public function testAllReturnsClientGetResponseWithParameters()
    {
        // Test values
        $parameters = array('not-used');
        $getResponse = array('API Response');

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->any())
            ->method('get')
            ->with(
                $this->logicalAnd(
                    $this->stringStartsWith('/projects.json'),
                    $this->stringContains('not-used')
                )
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->all($parameters));
    }

    /**
     * Test show()
     *
     * @covers ::get
     * @covers ::show
     * @test
     *
     * @return void
     */
    public function testShowReturnsClientGetResponse()
    {
        // Test values
        $getResponse = 'API Response';

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('get')
            ->with(
                '/projects/5.json?include=trackers,issue_categories,'
                . 'attachments,relations'
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->show(5));
    }

    /**
     * Test remove()
     *
     * @covers ::delete
     * @covers ::remove
     * @test
     *
     * @return void
     */
    public function testRemoveCallsDelete()
    {
        // Test values
        $getResponse = 'API Response';

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('delete')
            ->with('/projects/5.xml')
            ->willReturn($getResponse);

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->remove(5));
    }

    /**
     * Test listing()
     *
     * @covers ::listing
     * @test
     *
     * @return void
     */
    public function testListingReturnsNameIdArray()
    {
        // Test values
        $getResponse = array(
            'projects' => array(
                array('id' => 1, 'name' => 'Project 1'),
                array('id' => 5, 'name' => 'Project 5')
            ),
        );
        $expectedReturn = array(
            'Project 1' => 1,
            'Project 5' => 5,
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->atLeastOnce())
            ->method('get')
            ->with(
                $this->stringStartsWith('/projects.json')
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($expectedReturn, $api->listing());
    }

    /**
     * Test listing()
     *
     * @covers ::listing
     * @test
     *
     * @return void
     */
    public function testListingCallsGetOnlyTheFirstTime()
    {
        // Test values
        $getResponse = array(
            'projects' => array(
                array('id' => 1, 'name' => 'Project 1'),
                array('id' => 5, 'name' => 'Project 5')
            ),
        );
        $expectedReturn = array(
            'Project 1' => 1,
            'Project 5' => 5,
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('get')
            ->with(
                $this->stringStartsWith('/projects.json')
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($expectedReturn, $api->listing());
        $this->assertSame($expectedReturn, $api->listing());
    }

    /**
     * Test listing()
     *
     * @covers ::listing
     * @test
     *
     * @return void
     */
    public function testListingCallsGetEveryTimeWithForceUpdate()
    {
        // Test values
        $getResponse = array(
            'projects' => array(
                array('id' => 1, 'name' => 'Project 1'),
                array('id' => 5, 'name' => 'Project 5')
            ),
        );
        $expectedReturn = array(
            'Project 1' => 1,
            'Project 5' => 5,
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->exactly(2))
            ->method('get')
            ->with(
                $this->stringStartsWith('/projects.json')
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($expectedReturn, $api->listing(true));
        $this->assertSame($expectedReturn, $api->listing(true));
    }

    /**
     * Test getIdByName()
     *
     * @covers ::getIdByName
     * @test
     *
     * @return void
     */
    public function testGetIdByNameMakesGetRequest()
    {
        // Test values
        $getResponse = array(
            'projects' => array(
                array('id' => 5, 'name' => 'Project 5')
            ),
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('get')
            ->with(
                $this->stringStartsWith('/projects.json')
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertFalse($api->getIdByName('Project 1'));
        $this->assertSame(5, $api->getIdByName('Project 5'));
    }

    /**
     * Test create()
     *
     * @covers ::create
     * @expectedException Exception
     * @test
     *
     * @return void
     */
    public function testCreateThrowsExceptionWithEmptyParameters()
    {
        // Test values
        $getResponse = 'API Response';

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->create(5));
    }

    /**
     * Test create()
     *
     * @covers ::create
     * @expectedException Exception
     * @test
     *
     * @return void
     */
    public function testCreateThrowsExceptionIfIdentifierIsMissingInParameters()
    {
        // Test values
        $getResponse = 'API Response';
        $parameters = array(
            'name' => 'Test Project'
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->create($parameters));
    }

    /**
     * Test create()
     *
     * @covers ::create
     * @expectedException Exception
     * @test
     *
     * @return void
     */
    public function testCreateThrowsExceptionIfNameIsMissingInParameters()
    {
        // Test values
        $getResponse = 'API Response';
        $parameters = array(
            'identifier' => 'test-project'
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->create($parameters));
    }

    /**
     * Test create()
     *
     * @covers ::create
     * @covers ::post
     * @test
     *
     * @return void
     */
    public function testCreateCallsPost()
    {
        // Test values
        $getResponse = 'API Response';
        $parameters = array(
            'identifier' => 'test-project',
            'name' => 'Test Project'
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('post')
            ->with(
                '/projects.xml',
                $this->logicalAnd(
                    $this->stringStartsWith('<?xml version="1.0"?>' . "\n" . '<project>'),
                    $this->stringEndsWith('</project>' . "\n"),
                    $this->stringContains('<identifier>test-project</identifier>'),
                    $this->stringContains('<name>Test Project</name>')
                )
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->create($parameters));
    }

    /**
     * Test create()
     *
     * @covers ::create
     * @covers ::post
     * @test
     *
     * @return void
     */
    public function testCreateCallsPostWithTrackers()
    {
        // Test values
        $getResponse = 'API Response';
        $parameters = array(
            'identifier' => 'test-project',
            'name' => 'Test Project',
            'tracker_ids' => array(10, 5)
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('post')
            ->with(
                '/projects.xml',
                $this->logicalAnd(
                    $this->stringStartsWith('<?xml version="1.0"?>' . "\n" . '<project>'),
                    $this->stringEndsWith('</project>' . "\n"),
                    $this->stringContains('<tracker_ids type="array">'),
                    $this->stringContains('<tracker>10</tracker>'),
                    $this->stringContains('<tracker>5</tracker>'),
                    $this->stringContains('</tracker_ids>')
                )
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->create($parameters));
    }

    /**
     * Test update()
     *
     * @covers ::put
     * @covers ::update
     * @test
     *
     * @return void
     */
    public function testUpdateCallsPut()
    {
        // Test values
        $getResponse = 'API Response';
        $parameters = array(
            'name' => 'Test Project'
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('put')
            ->with('/projects/5.xml')
            ->willReturn($getResponse);

        // Create the object under test
        $api = new Project($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->update(5, $parameters));
    }
}
