<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository;
use App\TwitterApi\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class TweetHistoryController extends AbstractController
{

    /** @var Repository\Tweet Tweet Repository */
    private $tweetRepository;


    /**
     * Constructor.
     *
     * @param Repository\Tweet $tweetRepository
     */
    public function __construct(Repository\Tweet $tweetRepository)
    {
        $this->tweetRepository = $tweetRepository;
    }


    public function histogram(string $username): ResponseInterface
    {
        try {
            $tweets = $this->tweetRepository->getAllForUser($username);
        } catch (RequestException $e) {
            return $this->jsonResponse([
                'error' => $e->getMessage(),
            ], 400);
        }

        $times = [];

        for ($i = 0; $i <= 24; $i++) {
            $times[] = 0;
        }

        foreach ($tweets as $tweet) {
            $tweetHour = (int) $tweet->getCreatedAt()->format('H');
            $times[$tweetHour]++;
        }

        return $this->jsonResponse((object) $times);
    }
}