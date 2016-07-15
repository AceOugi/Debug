<?php

if (!function_exists('debug'))
{
    /**
     * @param mixed $expression
     * @param mixed[] ...$expressions [optional]
     */
    function debug($expression, ...$expressions)
    {
        Ace\Debug::display('Debug', 'Dumps information', null, null, [], $expression, ...$expressions);
    }
}
