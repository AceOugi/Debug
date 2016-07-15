<?php

if (!function_exists('debug'))
{
    /**
     * @param DateTime[] ...$expressions
     */
    function debug(...$expressions)
    {
        Ace\Debug::display('Debug', 'Dumps information', null, null, [], ...$expressions);
    }
}
