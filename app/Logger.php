<?php
/**
 * Logger Class
 * 
 * Handles error logging for the application
 */
class Logger {
    private static $logFile = '';
    
    /**
     * Initialize the logger
     * 
     * @param string $logFile Path to the log file
     * @return void
     */
    public static function init($logFile = '') {
        // Set default log file if not provided
        if (empty($logFile)) {
            self::$logFile = __DIR__ . '/../logs/error.log';
        } else {
            self::$logFile = $logFile;
        }
        
        // Create log directory if it doesn't exist
        $logDir = dirname(self::$logFile);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        // Set up error handling
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleFatalError']);
    }
    
    /**
     * Handle PHP errors
     * 
     * @param int $errno Error number
     * @param string $errstr Error message
     * @param string $errfile File where the error occurred
     * @param int $errline Line where the error occurred
     * @return bool
     */
    public static function handleError($errno, $errstr, $errfile, $errline) {
        $errorType = self::getErrorType($errno);
        $message = "[$errorType] $errstr in $errfile on line $errline";
        self::log($message);
        
        // Don't execute PHP's internal error handler
        return true;
    }
    
    /**
     * Handle exceptions
     * 
     * @param \Throwable $exception The exception object
     * @return void
     */
    public static function handleException($exception) {
        $message = "[EXCEPTION] " . $exception->getMessage() . " in " . 
                   $exception->getFile() . " on line " . $exception->getLine() . 
                   "\nStack trace: " . $exception->getTraceAsString();
        self::log($message);
    }
    
    /**
     * Handle fatal errors
     * 
     * @return void
     */
    public static function handleFatalError() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $errorType = self::getErrorType($error['type']);
            $message = "[$errorType] {$error['message']} in {$error['file']} on line {$error['line']}";
            self::log($message);
        }
    }
    
    /**
     * Log a message to the log file
     * 
     * @param string $message The message to log
     * @return void
     */
    public static function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message" . PHP_EOL;
        
        // Append to log file
        file_put_contents(self::$logFile, $logMessage, FILE_APPEND);
    }
    
    /**
     * Get the error type as a string
     * 
     * @param int $errno Error number
     * @return string
     */
    private static function getErrorType($errno) {
        switch ($errno) {
            case E_ERROR:
                return 'E_ERROR';
            case E_WARNING:
                return 'E_WARNING';
            case E_PARSE:
                return 'E_PARSE';
            case E_NOTICE:
                return 'E_NOTICE';
            case E_CORE_ERROR:
                return 'E_CORE_ERROR';
            case E_CORE_WARNING:
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR:
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING:
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR:
                return 'E_USER_ERROR';
            case E_USER_WARNING:
                return 'E_USER_WARNING';
            case E_USER_NOTICE:
                return 'E_USER_NOTICE';
            case E_STRICT:
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR:
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED:
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED:
                return 'E_USER_DEPRECATED';
            default:
                return 'UNKNOWN';
        }
    }
}
?>
