<?php
declare(strict_types=1);

namespace App\Infrastructure\Symfony\Http\Response;

use App\Domain\Entity\Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MessageListResponse extends JsonResponse
{
    /**
     * @param Message[] $messages
     */
    public function __construct(array $messages)
    {
        $data = $this->getSerializer()->serialize(['messages' => $messages], 'json');

        parent::__construct($data, Response::HTTP_OK, [], true);
    }

    /**
     * @return Serializer
     */
    private function getSerializer(): Serializer
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];

        return new Serializer($normalizers, $encoders);
    }
}
