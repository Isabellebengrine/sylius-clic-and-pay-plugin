<?php

declare(strict_types=1);

namespace Poliofu\SyliusClicAndPayPlugin\Payum\Action;

use Poliofu\SyliusClicAndPayPlugin\Payum\SyliusApi;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Sylius\Component\Core\Model\PaymentInterface as SyliusPaymentInterface;
use Payum\Core\Request\Capture;

final class CaptureAction implements ActionInterface, ApiAwareInterface
{
    /** @var Client */
    private $client;
    /** @var SyliusApi */
    private $api;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function execute($request): void
    {//dd($request);
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var SyliusPaymentInterface $payment */
        $payment = $request->getModel();

        $params = [
            'vads_action_mode' => 'INTERACTIVE',
            'vads_amount' => $payment->getAmount(),
            'vads_capture_delay' => 0,
            'vads_ctx_mode' => 'TEST',
            'vads_currency' => 978,//pour EUR cf doc clicandpay
            'vads_page_action' => 'PAYMENT',
            'vads_payment_cards' => 'CB',
            'vads_payment_config' => 'SINGLE',
            'vads_site_id' => $this->api->getUsername(),
            'vads_trans_date' => date('ymdhms', $payment->getCreatedAt()->getTimestamp()),
            'vads_trans_id' => 123456,//test 2309 - voir où pêcher info ???
            'vads_validation_mode' => 0,
            'vads_version' => 'V2',
        ];

        try {
            $response = $this->client->request('POST', 'https://clicandpay.groupecdn.fr/vads-payment/', [
                'body' => json_encode([
                    'vads_action_mode' => 'INTERACTIVE',
                    'vads_amount' => $payment->getAmount(),
                    'vads_capture_delay' => 0,
                    'vads_ctx_mode' => 'TEST',
                    'vads_currency' => 978,//pour EUR cf doc clicandpay
                    'vads_page_action' => 'PAYMENT',
                    'vads_payment_cards' => 'CB',
                    'vads_payment_config' => 'SINGLE',
                    'vads_site_id' => $this->api->getUsername(),
                    'vads_trans_date' => date('ymdhms', $payment->getCreatedAt()->getTimestamp()),
                    'vads_trans_id' => 123456,//test 2309 - voir où pêcher info sur n° transaction ???
                    'vads_validation_mode' => 0,
                    'vads_version' => 'V2',
                    'signature' => $this->getSignature($params, $this->api->getKey()),
                ]),
            ]);
            // sets a HTTP response header - 2309 check if needed or not
            //header('Authorization: Basic ' . base64_encode($this->api->getUsername() . ':' . $this->api->getPassword()));
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
        } finally {
            $payment->setDetails(['status' => $response->getStatusCode()]);
        }
    }

    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof SyliusPaymentInterface
        ;
    }

    public function setApi($api): void
    {
        if (!$api instanceof SyliusApi) {
            throw new UnsupportedApiException('Not supported. Expected an instance of ' . SyliusApi::class);
        }

        $this->api = $api;
    }

    public function getSignature($params, $key)
    {
        /**
         * Fonction qui calcule la signature.
         * $params : tableau contenant les champs à envoyer dans le formulaire.
         * $key : clé de TEST ou de PRODUCTION
         */
        //Initialisation de la variable qui contiendra la chaine à chiffrer
        $contenu_signature = "";

        //Tri des champs par ordre alphabétique
        ksort($params);
        foreach ($params as $nom=>$valeur) {

            //Récupération des champs vads_
            if (substr($nom, 0, 5)=='vads_') {

                //Concaténation avec le séparateur "+"
                $contenu_signature .= $valeur."+";
            }
        }
        //Ajout de la clé en fin de chaine
        $contenu_signature .= $key;

        //Encodage base64 de la chaine chiffrée avec l'algorithme HMAC-SHA-256
        $signature = base64_encode(hash_hmac('sha256', $contenu_signature, $key, true));
        return $signature;
    }
}
