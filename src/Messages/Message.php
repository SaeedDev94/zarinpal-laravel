<?php

namespace Zarinpal\Messages;

class Message
{
    /**
     * Get message of status
     *
     * @param  string $lang
     * @param  int    $status
     *
     * @return string
     */
    public static function get($lang, $status) {
        $message = '';
        $path = __DIR__.'/lang/'.$lang.'.php';
        if(file_exists($path)) {
            $messages = require $path;
            if(isset($messages[$status])) {
                $message = $messages[$status];
            }
        }

        return $message;
    }
}
