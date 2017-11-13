<?php

namespace JincorTech\VerifyClient;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use JincorTech\VerifyClient\Abstracts\VerificationDetailsCreator;
use JincorTech\VerifyClient\Abstracts\InvalidationData;
use JincorTech\VerifyClient\Abstracts\ValidationData;
use JincorTech\VerifyClient\Abstracts\VerificationDetails;
use JincorTech\VerifyClient\Exceptions\InvalidCodeException;
use JincorTech\VerifyClient\Interfaces\VerificationMethod;
use JincorTech\VerifyClient\Interfaces\VerifyService;

/**
 * Class VerifyClient
 *
 * @package JincorTech\VerifyClient
 */
class VerifyClient implements VerifyService
{
    /**
     * Http Client
     *
     * @var ClientInterface
     */
    private $httpClient;


    /**
     * VerifyClient constructor.
     *
     * @param ClientInterface $client Http Client
     */
    public function __construct(ClientInterface $client)
    {
        $this->httpClient = $client;
    }


    /**
     * Initiate verification process
     *
     * @param VerificationMethod $verificationMethod Verification Method
     *
     * @return VerificationDetails
     *
     * @throws Exception
     */
    public function initiate(VerificationMethod $verificationMethod): VerificationDetails
    {
        $response = $this->httpClient->request(
            'POST', '/methods/'.$verificationMethod->getMethodType().'/actions/initiate', [
                'json' => $verificationMethod->getRequestParameters(),
            ]
        );

        $data = json_decode($response->getBody()->getContents(), true);

        return VerificationDetailsCreator::create($verificationMethod->getMethodType(), $data);
    }


    /**
     * Validate the code
     *
     * @param ValidationData $validationData Validation Data
     *
     * @return bool
     *
     * @throws InvalidCodeException
     */
    public function validate(ValidationData $validationData): bool
    {
        try {
            $this->httpClient->request(
                'POST', '/methods/'
                    .$validationData->getMethodType().'/verifiers/'
                    .$validationData->getVerificationId().'/actions/validate',
                [
                    'json' => $validationData->getRequestParameters(),
                ]
            );
        } catch (ClientException $clientException) {
            if ($clientException->getCode() === 422) {
                $detail = json_decode($clientException->getResponse()->getBody()->getContents(), true);
                throw new InvalidCodeException(
                    'Invalid Code',
                    0
                );
            }

            throw $clientException;
        }

        return true;
    }


    /**
     * Invalidate the code
     *
     * @param InvalidationData $invalidationData Invalidation Data
     *
     * @return bool
     */
    public function invalidate(InvalidationData $invalidationData): bool
    {
        $this->httpClient->request(
            'DELETE', '/methods/'
                .$invalidationData->getMethodType().'/verifiers/'
                .$invalidationData->getVerificationId()
        );

        return true;
    }
}
