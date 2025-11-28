<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Solar Energy Management API",
 *      description="API documentation for Solar Energy Management System",
 *      @OA\Contact(
 *          email="admin@example.com"
 *      )
 * )
 * 
 * @OA\Server(
 *      url="http://localhost:8088",
 *      description="Local Development Server"
 * )
 * 
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Enter token in format: Bearer {token}"
 * )
 */
abstract class Controller {}
