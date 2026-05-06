<?php

class LoggerMiddleware
{
    public static function handle(): void
    {
        $method = $_SERVER["REQUEST_METHOD"] ?? "UNKNOWN";
        $uri = $_SERVER["REQUEST_URI"] ?? "/";
        $ip = $_SERVER["REMOTE_ADDR"] ?? "unknown";
        $userAgent = $_SERVER["HTTP_USER_AGENT"] ?? "unknown";
        $contentType = $_SERVER["CONTENT_TYPE"] ?? "none";

        $time = date("Y-m-d H:i:s");

        $message = sprintf(
            "[%s] %s %s | IP: %s | Content-Type: %s | User-Agent: %s",
            $time,
            $method,
            $uri,
            $ip,
            $contentType,
            $userAgent
        );
        
        error_log($message);
    }
}