<?php

namespace AceOugi;

class Debug
{
    /**
     * @param string $file_path
     * @param int $file_line
     */
    protected static function highlight(string $file_path, int $file_line)
    {
        //ini_set('highlight.string', '#4CAF50');
        //ini_set('highlight.comment', '#9E9E9E');
        //ini_set('highlight.keyword', 'inherit');
        //ini_set('highlight.bg', 'none');
        //ini_set('highlight.default', '#FF9800');
        //ini_set('highlight.html', 'inherit');

        $source = file_get_contents($file_path);
        $lines = preg_split('{\r\n|\r|\n}', $source);
        $count = count($lines);

        echo '<ol style="white-space: pre;">';
        for($i = 0 ; $i < $count ; $i++)
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

    protected static function display($title, $message, $file_path = null, $file_line = null, $traces = [], ...$expressions)
    {
        while (ob_get_level()) ob_end_clean();

        include __DIR__.'/Debug.phtml';

        exit(1);
    }

    /**
     * @param mixed $expression
     * @param mixed ...$expressions [optional]
     */
    public static function dump($expression, ...$expressions)
    {
        static::display('Debug', 'Dumps information', null, null, [], $expression, ...$expressions);
    }

    public static function errorHandler($errno, $errstr, $errfile = null, $errline = null, $errcontext = [])
    {
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

        static::display($type, $errstr, $errfile, $errline, [], $errcontext);
    }

    public static function exceptionHandler(\Throwable $ex)
    {
        static::display(
            'Exception '.get_class($ex).($ex->getCode() ? '#'.$ex->getCode() : ''),
            $ex->getMessage(),
            $ex->getFile(),
            $ex->getLine(),
            $ex->getTrace()
        );
    }

    public function __invoke($request, $response, $next)
    {
        set_error_handler(__CLASS__.'::errorHandler');
        set_exception_handler(__CLASS__.'::exceptionHandler');

        return $next($request, $response);
    }
}
