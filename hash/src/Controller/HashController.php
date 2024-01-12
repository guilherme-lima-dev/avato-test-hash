<?php

namespace App\Controller;

use App\Repository\AvatoRequestsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;

class HashController extends AbstractController
{

    public function __construct(private readonly AvatoRequestsRepository $avatoRequestsRepository)
    {
    }

    #[Route('/calculate-hash', name: 'app_hash', methods: ['POST'])]
    public function index(Request $request, RateLimiterFactory $hashApiLimiter): JsonResponse
    {
        $limiter = $hashApiLimiter->create($request->getClientIp());

        if (false === $limiter->consume(1)->isAccepted()) {
            return $this->json(['error' => 'too many requests'], 429);
        }

        $content = json_decode($request->getContent());
        $inputString = $content?->inputString;

        if ($inputString === null) {
            return $this->json(
                ['error' => 'O parametro inputString precisa ser enviado!'],
                400
            );
        }

        $attempts = 0;
        do {
            $attempts++;
            $key = bin2hex(random_bytes(4));
            $concat = $inputString . $key;

            $hash = md5($concat);
        } while (!str_starts_with($hash, '0000'));

        $responseData = [
            'hash' => $hash,
            'key' => $key,
            'attempts' => $attempts
        ];

        return $this->json($responseData);
    }

    #[Route('/results', name: 'results_list')]
    public function results(Request $request)
    {
        $page = $request->query->get('page') ?? 1;
        $limit = $request->query->get('maxPerPage') ?? 10;

        $offset = ($page - 1) * $limit;

        $filter = $request->query->all();

        $data = $this->avatoRequestsRepository->search($filter, $limit, $offset);

        return $this->json(
            [
                'data' => $data,
                'pagination' => [
                    'page' => $page,
                    'maxPerPage' => $limit,
                    'count' => count($data)
                ]
            ]
        );
    }

}
