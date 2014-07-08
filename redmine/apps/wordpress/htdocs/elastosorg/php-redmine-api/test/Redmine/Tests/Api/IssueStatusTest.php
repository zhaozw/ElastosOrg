<?php

namespace Redmine\Tests\Api;

use Redmine\Api\IssueStatus;

/**
 * @coversDefaultClass Redmine\Api\IssueStatus
 * @author     Malte Gerth <mail@malte-gerth.de>
 */
class IssueStatusTest extends \PHPUnit_Framework_TestCase
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
                $this->stringStartsWith('/issue_statuses.json')
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new IssueStatus($client);

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
    public function testAllReturnsClientGetResponseWithParametersAndProject()
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
                    $this->stringStartsWith('/issue_statuses.json'),
                    $this->stringContains('not-used')
                )
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new IssueStatus($client);

        // Perform the tests
        $this->assertSame($getResponse, $api->all($parameters));
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
            'issue_statuses' => array(
                array('id' => 1, 'name' => 'IssueStatus 1'),
                array('id' => 5, 'name' => 'IssueStatus 5')
            ),
        );
        $expectedReturn = array(
            'IssueStatus 1' => 1,
            'IssueStatus 5' => 5,
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->atLeastOnce())
            ->method('get')
            ->with(
                $this->stringStartsWith('/issue_statuses.json')
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new IssueStatus($client);

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
            'issue_statuses' => array(
                array('id' => 1, 'name' => 'IssueStatus 1'),
                array('id' => 5, 'name' => 'IssueStatus 5')
            ),
        );
        $expectedReturn = array(
            'IssueStatus 1' => 1,
            'IssueStatus 5' => 5,
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('get')
            ->with(
                $this->stringStartsWith('/issue_statuses.json')
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new IssueStatus($client);

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
            'issue_statuses' => array(
                array('id' => 1, 'name' => 'IssueStatus 1'),
                array('id' => 5, 'name' => 'IssueStatus 5')
            ),
        );
        $expectedReturn = array(
            'IssueStatus 1' => 1,
            'IssueStatus 5' => 5,
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->exactly(2))
            ->method('get')
            ->with(
                $this->stringStartsWith('/issue_statuses.json')
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new IssueStatus($client);

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
            'issue_statuses' => array(
                array('id' => 5, 'name' => 'IssueStatus 5')
            ),
        );

        // Create the used mock objects
        $client = $this->getMockBuilder('Redmine\Client')
            ->disableOriginalConstructor()
            ->getMock();
        $client->expects($this->once())
            ->method('get')
            ->with(
                $this->stringStartsWith('/issue_statuses.json')
            )
            ->willReturn($getResponse);

        // Create the object under test
        $api = new IssueStatus($client);

        // Perform the tests
        $this->assertFalse($api->getIdByName('IssueStatus 1'));
        $this->assertSame(5, $api->getIdByName('IssueStatus 5'));
    }
}
