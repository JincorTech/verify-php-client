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
use JincorTech\VerifyClient\ValueObjects\VerificationResult;

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
     * @return VerificationResult
     *
     * @throws InvalidCodeException
     */
    public function validate(ValidationData $validationData): VerificationResult
    {
        try {
            $response = $this->httpClient->request(
                'POST', '/methods/'
                    .$validationData->getMethodType().'/verifiers/'
                    .$validationData->getVerificationId().'/actions/validate',
                [
                    'json' => $validationData->getRequestParameters(),
                ]
            );

            return new VerificationResult(json_decode($response->getBody()->getContents(), true));
        } catch (ClientException $clientException) {
            if ($clientException->getCode() === 422) {
                throw new InvalidCodeException('Invalid Code');
            }

            throw $clientException;
        }
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
