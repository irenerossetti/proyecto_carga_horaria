<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Proyecto Carga Horaria - API",
 *     version="1.0.0",
 *     description="Documentación completa de la API del sistema de gestión de carga horaria docente"
 * )
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Servidor local de desarrollo"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="cookieAuth",
 *     type="apiKey",
 *     in="cookie",
 *     name="laravel_session"
 * )
 */
abstract class Controller
{
    //
}
