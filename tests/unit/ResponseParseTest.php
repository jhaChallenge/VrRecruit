<?php

use Vreasy\Utils\ResponseParser;

class ResponseParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider acceptWordsProvider
     */
    public function testHasAcceptedAndNotDeclined($response)
    {
        $this->assertTrue(ResponseParser::HasAccepted($response));
        $this->assertFalse(ResponseParser::HasDeclined($response));
    }

    /**
     * @dataProvider declineWordsProvider
     */
    public function testHasDeclinedAndNotAccepted($response)
    {
        $this->assertTrue(ResponseParser::HasDeclined($response));
        $this->assertFalse(ResponseParser::HasAccepted($response));
    }

    public function acceptWordsProvider()
    {
        return array(
          array('Y'),
          array('Y'),
          array('y'),
          array('YeS'),
          array('yEs'),
          array('yes'),
          array('yES'),
          array('yeS'),
          array('si'),
          array('sI'),
          array('SI'),
          array('sI'),
          array('Ok'),
          array('OK'),
          array('oK'),
          array('ok')
        );
    }

    public function declineWordsProvider()
    {
        return array(
          array('N'),
          array('n'),
          array('nO'),
          array('No'),
          array('NO')
        );
    }
}
?>