<?php
use Vreasy\Utils\StatusMapper;
use Vreasy\Utils\Status;

class StatusMapperTest extends \PHPUnit_Framework_TestCase
{
	public function testAcceptedResponseMapsStatusAccepted()
    {
    	$response = "Yes";
        $this->assertEquals(StatusMapper::Map($response), Status::ACCEPTED);
    }

    public function testDeclinedResponseMapsStatusDeclined()
    {
    	$response = "N";
        $this->assertEquals(StatusMapper::Map($response), Status::DECLINED);
    }

    public function testNonRecognizedResponseMapsStatusError()
    {
    	$response = "ABC";
        $this->assertEquals(StatusMapper::Map($response), Status::ERROR_PARSE);
    }
}