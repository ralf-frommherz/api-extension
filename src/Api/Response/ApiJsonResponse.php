<?php


namespace Cs\ApiExtensionBundle\Api\Response;


use Symfony\Component\HttpFoundation\JsonResponse;

class ApiJsonResponse extends JsonResponse
{
    /**
     * @var ApiResponse
     */
    private ApiResponse $apiResponse;

    /**
     * ApiJsonResponse constructor.
     * @param ApiResponse $apiResponse
     * @param null $data
     * @param int $status
     * @param array $headers
     * @param bool $json
     */
    public function __construct(ApiResponse $apiResponse, $data = null, int $status = 200, array $headers = [], bool $json = false)
    {
        parent::__construct($data, $status, $headers, $json);
        $this->apiResponse = $apiResponse;
    }

    /**
     * @return ApiResponse
     */
    public function getApiResponse(): ApiResponse
    {
        return $this->apiResponse;
    }

    /**
     * @param ApiResponse $apiResponse
     */
    public function setApiResponse(ApiResponse $apiResponse): void
    {
        $this->apiResponse = $apiResponse;
    }
}