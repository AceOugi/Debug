<?php

namespace AceOugi;

class Debug
{
    /**
     * @param mixed $expression
     * @param mixed ...$expressions [optional]
     */
    public static function dump($expression, ...$expressions)
    {
        @ob_clean();

        include __DIR__.'/Debug.phtml';
        include __DIR__.'/DebugDump.phtml';

        Dumper::dump($expression, ...$expressions);

        exit;
    }

    /**
     * @param string $file
     * @param int $file_line
     */
    protected static function highlight(string $file, int $file_line)
    {
        $source = file_get_contents($file);
        $lines = preg_split('{\r\n|\r|\n}', $source);
        $count = count($lines);

        echo '<ol style="white-space: pre;">';
        for($i = 0 ; $i < count($lines) ; $i++)
        {
            $j = $i+1;
            if ($j < $file_line-9) continue;
            if ($j > $file_line+9) continue;

            if ($j == $file_line)
                echo '<li value="'.$j.'" style="color: #F44336">'.htmlspecialchars($lines[$i]);
            elseif ($j == $file_line-1 OR $j == $file_line+1)
                echo '<li value="'.$j.'" style="color: #E57373">'.htmlspecialchars($lines[$i]);
            elseif ($j == $file_line-2 OR $j == $file_line+2)
                echo '<li value="'.$j.'" style="color: #FFCDD2">'.htmlspecialchars($lines[$i]);
            else
                echo '<li value="'.$j.'">'.htmlspecialchars($lines[$i]);
        }
        echo '</ol>';
    }

    public static function errorHandler($errno, $errstr, $errfile = null, $errline = null, $errcontext = [])
    {
        @ob_clean();

        $type = 'unknown';
        switch ($errno)
        {
            // Parse error
            case E_PARSE: $type = 'critical'; break;
            // Fatal error
            case E_ERROR: $type = 'error'; break;
            case E_CORE_ERROR: $type = 'error'; break;
            case E_COMPILE_ERROR: $type = 'error'; break;
            case E_USER_ERROR : $type = 'error'; break;
            // Catchable fatal error
            case E_RECOVERABLE_ERROR: $type = 'error'; break;
            // Warning
            case E_WARNING: $type = 'warning'; break;
            case E_CORE_WARNING: $type = 'warning'; break;
            case E_COMPILE_WARNING: $type = 'warning'; break;
            case E_USER_WARNING: $type = 'warning'; break;
            // Notice
            case E_NOTICE: $type = 'notice'; break;
            case E_USER_NOTICE: $type = 'notice'; break;
            // Deprecated
            case E_DEPRECATED: $type = 'info'; break;
            case E_USER_DEPRECATED: $type = 'info'; break;
            case E_STRICT: $type = 'info'; break;
            default: throw new \InvalidArgumentException('Parameter must be predefined error constants constants');
        }
        $type = ucfirst($type);

        include __DIR__.'/Debug.phtml';
        include __DIR__.'/DebugError.phtml';

        exit;
    }

    public static function exceptionHandler(\Throwable $ex)
    {
        @ob_clean();

        include __DIR__.'/Debug.phtml';
        include __DIR__.'/DebugException.phtml';

        exit;
    }

    public function __invoke($request, $response, $next)
    {
        set_error_handler(__CLASS__.'::errorHandler');
        set_exception_handler(__CLASS__.'::exceptionHandler');

        return $next($request, $response);
    }
}
