<?php

namespace Zarinpal\Messages;

class Message
{
    /**
     * Get message of status
     *
     * @param  string  $lang
     * @param  int  $code
     *
     * @return string
     */
    public static function get(string $lang, int $code) {
        $message = '';
        $path = __DIR__.'/lang/'.$lang.'.php';
        if(file_exists($path)) {
            $messages = require $path;
            if(isset($messages[(string) $code])) {
                $message = $messages[(string) $code];
            }
        }

        return $message;
    }
}
