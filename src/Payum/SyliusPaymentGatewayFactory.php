<?php

declare(strict_types=1);

namespace Poliofu\SyliusClicAndPayPlugin\Payum;

use GuzzleHttp\Client;
use Poliofu\SyliusClicAndPayPlugin\Payum\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;
use Poliofu\SyliusClicAndPayPlugin\Payum\Action\CaptureAction;

final class SyliusPaymentGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => 'sylius.clic_and_pay',
            'payum.factory_title' => 'Clic&Pay',
            'payum.action.status' => new StatusAction(),
        ]);

        $config['payum.api'] = function (ArrayObject $config) {
            return new SyliusApi($config['api_key']);
        };
    }
}