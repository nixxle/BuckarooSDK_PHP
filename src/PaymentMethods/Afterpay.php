<?php

namespace Buckaroo\PaymentMethods;

use Buckaroo\Client;
use Buckaroo\Model\Address;
use Buckaroo\Model\Article;
use Buckaroo\Model\CapturePayload;
use Buckaroo\Model\Customer;
use Buckaroo\Model\PaymentPayload;
use Buckaroo\Model\RefundPayload;
use Buckaroo\Model\ServiceList;
use Buckaroo\Services\AfterpayParametersService;
use Buckaroo\Services\PayloadService;
use Buckaroo\Services\ServiceListParameters\ArticleParameters;
use Buckaroo\Services\ServiceListParameters\CustomerParameters;
use Buckaroo\Services\ServiceListParameters\DefaultParameters;
use Buckaroo\Transaction\Request\Adapters\CapturePayloadAdapter;
use Buckaroo\Transaction\Response\TransactionResponse;

class Afterpay extends PaymentMethod
{
    public const SERVICE_VERSION = 1;
    public const PAYMENT_NAME = 'afterpay';

    public function authorize($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();
        $this->request->setPayload($this->getPaymentPayload());

        $serviceList = new ServiceList(
            self::PAYMENT_NAME,
            self::SERVICE_VERSION,
            'Authorize'
        );

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new ArticleParameters($parametersService, $this->payload['articles'] ?? []);
        $parametersService = new CustomerParameters($parametersService, $this->payload['customer'] ?? []);
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function capture($payload): TransactionResponse
    {
        $this->payload = (new PayloadService($payload))->toArray();

        $capturePayload = (new CapturePayloadAdapter(new CapturePayload($this->payload)))->getValues();

        $this->request->setPayload($capturePayload);

        $serviceList = new ServiceList(
            self::PAYMENT_NAME,
            self::SERVICE_VERSION,
            'Capture'
        );

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new ArticleParameters($parametersService, $serviceParameters['articles'] ?? []);

        $this->request->getServices()->pushServiceList($serviceList);

        return $this->postRequest();
    }

    public function setPayServiceList(array $serviceParameters = [])
    {
        $serviceList =  new ServiceList(
            self::PAYMENT_NAME,
            self::SERVICE_VERSION,
            'Pay'
        );

        $parametersService = new DefaultParameters($serviceList);
        $parametersService = new ArticleParameters($parametersService, $serviceParameters['articles'] ?? []);
        $parametersService = new CustomerParameters($parametersService, $serviceParameters['customer'] ?? []);
        $parametersService->data();

        $this->request->getServices()->pushServiceList($serviceList);

        return $this;
    }

    public function paymentName(): string
    {
        return self::PAYMENT_NAME;
    }

    public function serviceVersion(): int
    {
        return self::SERVICE_VERSION;
    }
}
