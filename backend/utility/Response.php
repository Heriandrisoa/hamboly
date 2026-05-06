<?php

class Response
{
    // pour directement envoyer du json et le code de retour
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}