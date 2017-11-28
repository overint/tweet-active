<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository;
use App\TwitterApi\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

/**
 * Tweet History Controller
 * @package App\Controller
 */
class TweetHistoryController extends AbstractController
{

    /** @var Repository\Tweet Tweet Repository */
    private $tweetRepository;


    /**
     * Constructor
     *
     * @param Repository\Tweet $tweetRepository Tweet Repository
     */
    public function __construct(Repository\Tweet $tweetRepository)
    {
        $this->tweetRepository = $tweetRepository;
    }


    /**
     * Return  hour -> tweet count histogram
     *
     * @param string $username Twitter screen name
     *
     * @return ResponseInterface
     */
    public function histogram(string $username): ResponseInterface
    {
        try {
            $tweets = $this->tweetRepository->getForUser($username);
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
            $tweetHour = (int)$tweet->getCreatedAt()->format('H');
            $times[$tweetHour]++;
        }

        return $this->jsonResponse((object)$times);
    }
}