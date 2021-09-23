<?php

declare(strict_types=1);

namespace Poliofu\SyliusClicAndPayPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ClicandpayGatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('username', TextType::class, [
                    'label' => 'sylius.form.gateway_configuration.clicandpay.username',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'sylius.gateway_config.clicandpay.username.not_blank',
                        ]),
                    ],
                ])
                ->add('password', TextType::class, [
                    'label' => 'sylius.form.gateway_configuration.clicandpay.password',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'sylius.gateway_config.clicandpay.username.not_blank',
                        ]),
                    ],
                ])
                ->add('publicKey', TextType::class, [
                    'label' => 'sylius.form.gateway_configuration.clicandpay.publicKey',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'sylius.gateway_config.clicandpay.username.not_blank',
                        ]),
                    ],
                ])
                ->add('sha256Key', TextType::class, [
                    'label' => 'sylius.form.gateway_configuration.clicandpay.sha256Key',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'sylius.gateway_config.clicandpay.username.not_blank',
                        ]),
                    ],
                ])
        ;
    }
}
