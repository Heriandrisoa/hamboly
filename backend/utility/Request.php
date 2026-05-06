<?php

class Request
{
    // pour directement recuperer le json des requetes
    public static function json(): array
    {
        $body = file_get_contents("php://input");
        $data = json_decode($body, true);

        if (!is_array($data)) {
            return [];
        }

        return $data;
    }
}