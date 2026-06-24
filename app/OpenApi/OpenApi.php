<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Todo API",
    version: "1.0.0",
    description: "REST API for Task management with Laravel + Passport"
)]
#[OA\SecurityScheme(
    securityScheme: "passport",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
class OpenApi
{

}
