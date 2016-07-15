<?php

namespace Ace;

/**
 * @param mixed[] ...$expressions
 */
function debug(...$expressions)
{
    Debug::display('Debug', 'Dumps information', null, null, [], ...$expressions);
}
