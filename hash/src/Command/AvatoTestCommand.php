<?php

namespace App\Command;

use App\Entity\AvatoRequests;
use App\Repository\AvatoRequestsRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'avato:test',
    description: 'consulta a rota criada e armazena os resultados na base de dados',
)]
class AvatoTestCommand extends Command
{

    public function __construct(
        private readonly AvatoRequestsRepository $avatoRequestsRepository,
        private readonly HttpClientInterface $client
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('inputString', InputArgument::REQUIRED, 'string inicial da primeira requisição')
            ->addOption('requests', null, InputOption::VALUE_REQUIRED, 'numero de requisições')
            ->addOption('alias', null, InputOption::VALUE_REQUIRED, 'apelido para a execução do comando');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $inputString = $input->getArgument('inputString');
        $requests = $input->getOption('requests') ?? 5;
        $alias = $input->getOption('alias') ?? bin2hex(random_bytes(4));

        $requests = filter_var($requests, FILTER_VALIDATE_INT);

        if ($requests === false) {
            throw new InvalidArgumentException('O valor de requests precisa ser um número inteiro!');
        }

        $requestInterval = 60 / 10;

        for ($i = 0; $i < $requests; $i++) {
            $this->makeRequestAndSaveResult($io, $inputString, $alias, $i);
            if ($i < $requests - 1) {
                sleep($requestInterval);
            }
        }


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    private function makeRequestAndSaveResult($io, &$inputString, $alias, $iteration): void
    {
        $io->note('Realizando requisição número: ' . ($iteration + 1));

        $params = ['inputString' => $inputString];


        $response = $this->client->request(
            'POST',
            'http://localhost:8000/calculate-hash',
            [
                'json' => $params
            ]
        );

        $response = $response->toArray();

        $avato = $this->createAvatoRequestObject($inputString, $response, $alias, $iteration);

        $inputString = $response['hash'];

        $this->avatoRequestsRepository->saveAndFlush($avato);
    }

    private function createAvatoRequestObject($inputString, $response, $alias, $iteration): AvatoRequests
    {
        return new AvatoRequests(
            requestNumber: $iteration + 1,
            inputString: $inputString,
            keyFound: $response['key'],
            hash: $response['hash'],
            attempts: $response['attempts'],
            momentRequest: new \DateTime(),
            alias: $alias
        );
    }
}
