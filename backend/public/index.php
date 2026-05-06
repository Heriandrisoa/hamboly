<?php

require_once __DIR__ . "/../middlewares/LoggerMiddleware.php";
LoggerMiddleware::handle();

require_once __DIR__ . "/../config/cors.php";
require_once __DIR__ . "/../config/session.php";
require_once __DIR__ . "/../config/database.php";

require_once __DIR__ . "/../utility/Request.php";
require_once __DIR__ . "/../utility/Response.php";

require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/Plants.php";
require_once __DIR__ . "/../controllers/AuthController.php";
require_once __DIR__ . "/../controllers/PlantController.php";
require_once __DIR__ . "/../controllers/EngineerController.php";

require_once __DIR__ . "/../routes/api.php";