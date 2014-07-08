<?php

namespace Redmine\Tests\Api;

use Redmine\Api\IssuePriority;

/**
 * @coversDefaultClass Redmine\Api\IssuePriority
 * @author     Malte Gerth <mail@malte-gerth.de>
 */
class IssuePriorityTest extends \PHPUnit_Framework_TestCase
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
            ->with(
                $this->stringStartsWith('/enumerations/issue_priorities.json')
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new IssuePriority($client);

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
        $allParameters = array('not-used');
        $getResponse = 'API Response';

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->any())
            ->method('get')
            ->with(
                $this->stringContains('not-used')
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new IssuePriority($client);

        // Perform the tests
        $this->assertSame(array($getResponse), $api->all($allParameters));
    }
}
