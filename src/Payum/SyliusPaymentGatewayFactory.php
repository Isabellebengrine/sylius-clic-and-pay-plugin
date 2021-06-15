<?php

declare(strict_types=1);

namespace Poliofu\SyliusClicAndPayPlugin\Payum;

use Poliofu\SyliusClicAndPayPlugin\Payum\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class SyliusPaymentGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => 'sylius_clic_and_pay',
            'payum.factory_title' => 'Sylius Clic&Pay',
            'payum.action.status' => new StatusAction(),
        ]);

        $config['payum.api'] = function (ArrayObject $config) {
            return new SyliusApi($config['api_key']);
        };
    }
}