<?php

if (!function_exists('debug'))
{
    /**
     * @param mixed $expression
     * @param mixed[] ...$expressions [optional]
     */
    function debug($expression, ...$expressions)
    {
        \Ace\Debug::dump($expression, ...$expressions);
    }
}