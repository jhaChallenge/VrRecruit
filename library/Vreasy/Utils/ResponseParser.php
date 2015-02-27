<?php

namespace Vreasy\Utils;

class ResponseParser
{
    const ACCEPT_REGEX = "/\\b(yes|si|ok)\\b|^(y|s)$/im";
    const DECLINE_REGEX = "/\\b(no)\\b|^(n)$/im";

    public static function HasAccepted($response)
    {
        return (boolean)preg_match(self::ACCEPT_REGEX, $response);
    }

    public static function HasDeclined($response)
    {
        return (boolean)preg_match(self::DECLINE_REGEX, $response);
    }
}