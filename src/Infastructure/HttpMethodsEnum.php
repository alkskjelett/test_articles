<?php

namespace App\Infastructure;

enum HttpMethodsEnum: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    CASE PATCH = 'PATCH';
    CASE DELETE = 'DELETE';
}
