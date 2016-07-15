<?php

if (!function_exists('dd'))
{
    /**
     * @param mixed[] ...$expressions
     */
    function dd(...$expressions)
    {
        \Ace\debug(...$expressions);
    }
}
