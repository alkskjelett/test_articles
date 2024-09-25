<?php

declare(strict_types=1);

namespace App\Infastructure;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonResponse extends Response
{
    private const JSON_FORMAT = 'json';
    private const DEFAULT_HEADERS = ['Content-Type' => 'application/json'];

    public function __construct(?array $content = [], int $status = 200, array $headers = self::DEFAULT_HEADERS)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $serializedContent = $serializer->serialize($content, self::JSON_FORMAT);
        parent::__construct($serializedContent, $status, $headers);
    }
}