<?php

ini_set("session.use_strict_mode", "1");

session_name("HAMBOLY_SESSION");

session_set_cookie_params([
    "lifetime" => 0,
    "path" => "/",
    "domain" => "",
    "secure" => false, // satria http
    "httponly" => true,
    "samesite" => "Lax",
]);

session_start();