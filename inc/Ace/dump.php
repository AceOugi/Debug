<?php

namespace Ace;

/**
 * @param mixed[] ...$expressions
 */
function dump(...$expressions)
{
    // Recursive anonyme function
    $dump_recursive = function(&$expression, $expression_name = null) use (&$dump_recursive)
    {
        // Start tag
        echo PHP_EOL; // str_repeat(' ', $depth*2)
        echo '<div>';

        // Expression Name
        if ($expression_name !== null)
            echo '<span style="color: #FF9800">'.$expression_name.':</span> ';

        // Expression Type
        switch (gettype($expression))
        {
            // Scalar bool
            case 'boolean':
                echo '<span style="color: #9C27B0">bool(';
                echo '<span style="color: #2196F3">'.($expression?'true':'false').'</span>)';
                echo '</span>';
                break;

            // Scalar int
            case 'integer':
                echo '<span style="color: #9C27B0">int(';
                echo '<span style="color: #2196F3">'.$expression.'</span>)';
                echo '</span>';
                break;

            // Scalar float ==> double
            case 'double':
                echo '<span style="color: #9C27B0">float(';
                echo '<span style="color: #2196F3">'.$expression.'</span>)';
                echo '</span>';
                break;

            // Scalar string
            case 'string': // white-space: nowrap ; overflow: hidden ; text-overflow: ellipsis
                echo '<span style="color: #9C27B0">string('.strlen($expression).') ';
                if (strlen(strip_tags($expression)) < 48)
                    echo '<span style="color: #4CAF50">"'.$expression.'"</span>';
                else
                    echo '<span style="color: #4CAF50" title="'.strip_tags($expression).'">"'.substr(strip_tags($expression), 0, 48).'..."</span>';
                echo '</span>';
                break;

            // Compound array
            case 'array':
                if (isset($expression['__dump_recursion']) OR array_key_exists('__dump_recursion', $expression)):
                    echo '*RECURSION*';
                else:
                    echo '<span style="color: #9C27B0">array('.count($expression).')</span>';
                    echo '<div style="margin-left: 2px; padding-left: 30px; border-left: 1px dotted rgba(158, 158, 158, 0.25)">';
                    $expression['__dump_recursion'] = true;
                    foreach ($expression as $key => &$value)
                        if ($key !== '__dump_recursion')
                            $dump_recursive($value, $key);
                    unset($expression['__dump_recursion']);
                    echo '</div>';
                endif;
                break;

            // Compound object
            case 'object':
                static $__object_recursion = [];
                $hash = 'id'.spl_object_hash($expression);
                if (isset($__object_recursion[$hash]) OR array_key_exists($hash, $__object_recursion)):
                    echo '*RECURSION*';
                else:
                    $reflection = new \ReflectionObject($expression);
                    echo '<span style="color: #9C27B0">object('.$reflection->getName().')</span>';
                    echo '<div style="margin-left: 2px; padding-left: 30px; border-left: 1px dotted rgba(158, 158, 158, 0.25)">';
                    $__object_recursion[$hash] = true;
                    foreach ($reflection->getProperties() as $property)
                    {
                        $property->setAccessible(true);
                        $value = $property->getValue($expression);
                        $dump_recursive($value, $property->getName());
                    }
                    unset($__object_recursion[$hash]);
                    echo '</div>';
                endif;
                break;

            // Special resource
            case 'resource':
                echo '<span style="color: #9E9E9E">resource(';
                echo '<span style="color: #607D8B">'.get_resource_type($expression).'</span>)';
                echo '</span>';
                break;

            // Special NULL
            case 'NULL':
                echo '<span style="color: #9E9E9E">NULL</span>';
                break;

            // Special unknown type
            default:
                echo '<span style="color: #F44336">'.gettype($expression).'</span>';
                break;
        }

        // End tag
        echo '</div>';
    };

    // Call the recursive function anonyme for all expressions
    echo '<div style="font-family: Consolas, monospace">';
    foreach ($expressions as $expression)
        $dump_recursive($expression);
    echo '</div>'.PHP_EOL;
}
