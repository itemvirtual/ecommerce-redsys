<?php

namespace Itemvirtual\EcommerceRedsys;

use Sermepa\Tpv\Tpv;
use Illuminate\Support\Facades\Log;

class EcommerceRedsys
{
    private $redsys;
    private $environment;
    private $amount;
    private $merchantCode;
    private $currency;
    private $language;
    private $transactionType;
    private $terminal;
    private $method;
    private $notificationUrl;
    private $urlOk;
    private $urlKo;
    private $tradeName;
    private $titular;
    private $description;
    private $merchantData;

    private $submitButtonName = "submit_ecommerce_redsys";
    private $submitButtonId = "submit-ecommerce-redsys";
    private $submitButtonTitle = "Enviar";
    private $submitButtonClass = "btn btn-primary";

    const TIPO_PAGO_AUTORIZACION = 0;
    const TIPO_PAGO_PREAUTORIZACION = 1;
    const TIPO_PAGO_CONFIRMACION = 2;
    const TIPO_PAGO_DEVOLUCION_AUTOMATICA = 3;
    const TIPO_PAGO_PAGO_REFERENCIA = 4;
    const TIPO_PAGO_TRANSACCION_RECURRENTE = 5;
    const TIPO_PAGO_TRANSACCION_SUCESIVA = 6;
    const TIPO_PAGO_AUTENTICACION = 7;
    const TIPO_PAGO_CONFIRMACION_AUTENTICACION = 8;
    const TIPO_PAGO_ANULACION_PREAUTORIZACION = 9;


    public function __construct()
    {
        $this->createTransaction();
    }

    public function createTransaction()
    {
        $env = config('ecommerce-redsys.environment');

        $this->redsys = new Tpv();
        $this->redsys->setOrder(time());
        $this->redsys->setMerchantcode(config('ecommerce-redsys.parameters.' . $env . '.merchant_code'));
        $this->redsys->setCurrency(config('ecommerce-redsys.parameters.' . $env . '.currency'));
        $this->redsys->setLanguage(config('ecommerce-redsys.parameters.' . $env . '.language'));
        $this->redsys->setTransactiontype(self::TIPO_PAGO_AUTORIZACION);
        $this->redsys->setTerminal(config('ecommerce-redsys.parameters.' . $env . '.terminal'));
        $this->redsys->setMethod('C'); //Solo pago con tarjeta, no mostramos iupay
        $this->redsys->setNotification(config('ecommerce-redsys.parameters.' . $env . '.notification_url'));
        $this->redsys->setUrlOk(config('ecommerce-redsys.parameters.' . $env . '.url_ok'));
        $this->redsys->setUrlKo(config('ecommerce-redsys.parameters.' . $env . '.url_ko'));
        $this->redsys->setVersion('HMAC_SHA256_V1');
        $this->redsys->setTradeName(config('ecommerce-redsys.parameters.' . $env . '.trade_name'));
        $this->redsys->setTitular(config('ecommerce-redsys.parameters.' . $env . '.titular'));
        $this->redsys->setEnvironment(config('ecommerce-redsys.environment'));

        return $this;
    }

    private function createSignature()
    {
        $signature = $this->redsys->generateMerchantSignature(config('ecommerce-redsys.parameters.' . config('ecommerce-redsys.environment') . '.key'));
        $this->redsys->setMerchantSignature($signature);
    }

    public function createForm()
    {
        $this->createSignature();
        $this->redsys->setAttributesSubmit($this->submitButtonName, $this->submitButtonId, $this->submitButtonTitle, '', $this->submitButtonClass);

        return $this->redsys->createForm();
    }

    public function debugParameters()
    {
        $parameters = $this->redsys->getParameters();
        Log::channel('ecommerce-redsys')->info('EcommerceRedsys debugParameters');
        Log::channel('ecommerce-redsys')->info(json_encode($parameters));
        return $parameters;
    }

    // *********************************************************************************** TPV RESPONSE

    public function getMerchantParameters($Ds_MerchantParameters)
    {
        try {
            $parameters = $this->redsys->getMerchantParameters($Ds_MerchantParameters);
            return $parameters;
        } catch (\Sermepa\Tpv\TpvException $exception) {
            Log::channel('ecommerce-redsys')->error(json_encode($exception));
        }
        return null;
    }

    public function getMerchantData($Ds_MerchantParameters)
    {
        try {
            $parameters = $this->redsys->getMerchantParameters($Ds_MerchantParameters);
            if (array_key_exists('Ds_MerchantData', $parameters)) {
                return $parameters['Ds_MerchantData'];
            }
        } catch (\Sermepa\Tpv\TpvException $exception) {
            Log::channel('ecommerce-redsys')->error(json_encode($exception));
        }
        return null;
    }

    public function getDsResponse($Ds_MerchantParameters)
    {
        try {
            $parameters = $this->redsys->getMerchantParameters($Ds_MerchantParameters);
            if (array_key_exists('Ds_Response', $parameters)) {
                return $parameters['Ds_Response'];
            }
        } catch (\Sermepa\Tpv\TpvException $exception) {
            Log::channel('ecommerce-redsys')->error(json_encode($exception));
        }
        return 99999;
    }


    public function checkValidPayment($post)
    {
        $key = config('ecommerce-redsys.parameters.' . config('ecommerce-redsys.environment') . '.key');
        $DsResponse = $this->getDsResponse($post['Ds_MerchantParameters']);
        $DsResponse += 0;

        if ($this->redsys->check($key, $post) && $DsResponse <= 99) {
            return true;
        }

        return false;
    }

    // *********************************************************************************** SETTERS

    /**
     * @param mixed $environment
     * @return EcommerceRedsys
     */
    public function setEnvironment($environment)
    {
        $this->redsys->setEnvironment($environment);
        return $this;
    }

    /**
     * @param mixed $amount
     * @return EcommerceRedsys
     */
    public function setAmount($amount)
    {
        $amount = number_format($amount, 2, '.', ',');
        $this->redsys->setAmount($amount);
        return $this;
    }

    /**
     * @param mixed $merchantCode
     * @return EcommerceRedsys
     */
    public function setMerchantCode($merchantCode)
    {
        $this->redsys->setMerchantcode($merchantCode);
        return $this;
    }

    /**
     * @param mixed $currency
     * @return EcommerceRedsys
     */
    public function setCurrency($currency)
    {
        $this->redsys->setCurrency($currency);
        return $this;
    }

    /**
     * @param mixed $language
     * @return EcommerceRedsys
     */
    public function setLanguage($language)
    {
        $this->redsys->setLanguage($language);
        return $this;
    }

    /**
     * @param mixed $transactionType
     * @return EcommerceRedsys
     */
    public function setTransactionType($transactionType)
    {
        $this->redsys->setTransactiontype($transactionType);
        return $this;
    }

    /**
     * @param mixed $terminal
     * @return EcommerceRedsys
     */
    public function setTerminal($terminal)
    {
        $this->redsys->setTerminal($terminal);
        return $this;
    }

    /**
     * @param mixed $method
     * @return EcommerceRedsys
     */
    public function setMethod($method)
    {
        $this->redsys->setMethod($method);
        return $this;
    }

    /**
     * @param mixed $notificationUrl
     * @return EcommerceRedsys
     */
    public function setNotificationUrl($notificationUrl)
    {
        $this->redsys->setNotification($notificationUrl);
        return $this;
    }

    /**
     * @param mixed $urlOk
     * @return EcommerceRedsys
     */
    public function setUrlOk($urlOk)
    {
        $this->redsys->setUrlOk($urlOk);
        return $this;
    }

    /**
     * @param mixed $urlKo
     * @return EcommerceRedsys
     */
    public function setUrlKo($urlKo)
    {
        $this->redsys->setUrlKo($urlKo);
        return $this;
    }

    /**
     * @param mixed $tradeName
     * @return EcommerceRedsys
     */
    public function setTradeName($tradeName)
    {
        $this->redsys->setTradeName($tradeName);
        return $this;
    }

    /**
     * @param mixed $titular
     * @return EcommerceRedsys
     */
    public function setTitular($titular)
    {
        $this->redsys->setTitular($titular);
        return $this;
    }

    /**
     * @param mixed $description
     * @return EcommerceRedsys
     */
    public function setDescription($description)
    {
        $this->redsys->setProductDescription($description);
        return $this;
    }

    /**
     * @param mixed $merchantData
     * @return EcommerceRedsys
     */
    public function setMerchantData($merchantData)
    {
        $this->redsys->setMerchantData($merchantData);
        return $this;
    }


    /**
     * @param string $submitButtonTitle
     * @return EcommerceRedsys
     */
    public function setSubmitButtonTitle(string $submitButtonTitle)
    {
        $this->submitButtonTitle = $submitButtonTitle;
        return $this;
    }

    /**
     * @param string $submitButtonName
     * @return EcommerceRedsys
     */
    public function setSubmitButtonName(string $submitButtonName)
    {
        $this->submitButtonName = $submitButtonName;
        return $this;
    }

    /**
     * @param string $submitButtonId
     * @return EcommerceRedsys
     */
    public function setSubmitButtonId(string $submitButtonId)
    {
        $this->submitButtonId = $submitButtonId;
        return $this;
    }

    /**
     * @param string $submitButtonClass
     * @return EcommerceRedsys
     */
    public function setSubmitButtonClass(string $submitButtonClass)
    {
        $this->submitButtonClass = $submitButtonClass;
        return $this;
    }



}
