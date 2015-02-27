<?php

namespace Vreasy\Utils;

class StatusMapper
{
	public static function Map($response){
		$status = Status::ERROR_PARSE;

        if (ResponseParser::HasAccepted($response))
        {
            return Status::ACCEPTED;
        }

        if (ResponseParser::HasDeclined($response))
        {
            return Status::DECLINED;
        }

        return $status;
	}
}