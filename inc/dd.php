<?php

if (!function_exists('dd'))
{
    /**
     * @param mixed $expression
     * @param mixed ...$expressions [optional]
     */
    function dd($expression, ...$expressions)
    {
        \AceOugi\Debug::dump($expression, ...$expressions);
    }
}
