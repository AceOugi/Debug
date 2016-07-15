<?php

if (!function_exists('dd'))
{
    /**
     * @param mixed $expression
     * @param mixed[] ...$expressions [optional]
     */
    function dd($expression, ...$expressions)
    {
        \Ace\Debug::dump($expression, ...$expressions);
    }
}
