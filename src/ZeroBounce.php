<?php


namespace nickdnk\ZeroBounce;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\TransferException;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class ZeroBounce
{

    private $apiKey, $client;

    /**
     * ZeroBounce constructor.
     *
     * @param string $apiKey
     * @param int $timeout
     * @param array|null $proxy
     */
    public function __construct(string $apiKey, int $timeout = 15, ?array $proxy = null)
    {

        $this->apiKey = $apiKey;
        $options = [
            'base_uri'        => 'https://api.zerobounce.net/v2/',
            'timeout'         => $timeout,
            'connect_timeout' => 10
        ];
        if ($proxy) {
            $options['proxy'] = $proxy;
        }
        $this->client = new Client($options);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return stdClass
     * @throws APIError
     */
    private function handleResponse(ResponseInterface $response): stdClass
    {

        $json = json_decode($response->getBody());

        if (!$json) {

            throw new APIError(
                'Failed to parse response from ZeroBounce as JSON. Possibly a server error. Message returned: '
                . $response->getBody()
            );

        }

        if (property_exists($json, 'error')) {

            throw new APIError($json->error);

        } else {

            if (property_exists($json, 'Message')) {

                throw new APIError($json->Message);
            }

        }

        return $json;

    }

    /**
     * @param Email $email
     *
     * @return Result
     * @throws APIError
     */
    public function validateEmail(Email $email): Result
    {

        try {

            $json = $this->handleResponse(
                $this->client->get(
                    'validate',
                    [
                        'query' => [
                            'email'      => $email->getEmail(),
                            'ip_address' => $email->getIpAddress() ?? '',
                            'api_key'    => $this->apiKey
                        ]
                    ]
                )
            );

            return new Result(
                $json->address,
                $json->status,
                $json->sub_status,
                $json->account,
                $json->domain,
                $json->did_you_mean,
                is_numeric($json->domain_age_days) ? (int)$json->domain_age_days : null,
                $json->free_email,
                $json->mx_found,
                $json->mx_record,
                $json->smtp_provider,
                new User(
                    $json->firstname,
                    $json->lastname,
                    $json->gender,
                    $json->city,
                    $json->region,
                    $json->zipcode,
                    $json->country
                ),
                $json->processed_at
            );

        } catch (BadResponseException $exception) {

            $this->handleResponse($exception->getResponse());

            throw new APIError(
                'Failed to handle error response. Perhaps the ZeroBounce API has been modified. Response received: '
                . $exception->getResponse()->getBody()
            );

        } catch (TransferException $exception) {

            throw new APIError(
                'Failed to connect to ZeroBounce or connection timed out. Error: ' . $exception->getMessage()
            );

        }

    }
}
