<?php

if (!function_exists('dd'))
{
    /**
     * @param mixed[] ...$expressions
     */
    function dd(...$expressions)
    {
        \Ace\Debug::dump(...$expressions);
    }
}
