<?php


namespace nickdnk\ZeroBounce;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;

class ZeroBounce
{

    private $apiKey, $client;

    /**
     * ZeroBounce constructor.
     *
     * @param $apiKey
     */
    public function __construct(string $apiKey)
    {

        $this->apiKey = $apiKey;
        $this->client = new Client(
            [
                'base_uri'        => 'https://api.zerobounce.net/v2/',
                'timeout'         => 15,
                'connect_timeout' => 10
            ]
        );
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

            $response = $this->client->get(
                'validate',
                [
                    'query' => [
                        'email'      => $email->getEmail(),
                        'ip_address' => $email->getIpAddress() ?? '',
                        'api_key'    => $this->apiKey
                    ]
                ]
            );

            $json = json_decode($response->getBody());

            if (!$json) {

                throw new APIError(
                    'Failed to parse response from ZeroBounce as JSON. Possibly a server error. Message returned: '
                    . $response->getBody()
                );

            }

            if ($json->error) {

                throw new APIError($json->error);

            }

            return new Result(
                $json->address,
                $json->status,
                $json->sub_status,
                $json->account,
                $json->domain,
                $json->did_you_mean,
                $json->domain_age_days,
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

        } catch (RequestException $exception) {

            $json = json_decode($exception->getResponse()->getBody());

            if (!$json) {

                throw new APIError(
                    'Failed to parse response from ZeroBounce as JSON. Possibly a server error. Message returned: '
                    . $exception->getResponse()->getBody()
                );

            }

            throw new APIError($json->error);

        } catch (TransferException $exception) {

            throw new APIError(
                'Failed to connect to ZeroBounce or connection timed out. Error: ' . $exception->getMessage()
            );

        }

    }
}