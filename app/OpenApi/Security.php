<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer"
)]
class Security
{
}
