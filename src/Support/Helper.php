<?php

function dd($message, $description = null)
{
    echo "======>>> ".$description." start\n";
    if (\is_array($message)) {
        echo \var_export($message, true);
    } else if (\is_string($message)) {
        echo $message."\n";
    } else {
        var_dump($message);
    }
    echo  "======>>> ".$description." end\n";
}
