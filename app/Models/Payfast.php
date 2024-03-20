<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

/**
 * Description of Payfast
 *
 * @author macuser
 */
class Payfast {
    protected $custom_str1;
    protected $custom_str2;
    protected $custom_str3;
    protected $custom_str4;
    protected $custom_str5;
    protected $custom_int1;
    protected $custom_int2;
    protected $custom_int3;
    protected $custom_int4;
    protected $custom_int5;
    protected $merchant;
    protected $buyer;
    protected $amount;
    protected $item;
    protected $output;
    protected $vars;
    protected $response_vars;
    protected $host;
    protected $button;
    protected $status;
    protected $payment_method;
    protected $subscription_type;
    protected $m_payment_id;

    public function __construct() {
        $this->merchant = config('payfast.merchant');
    }

    public function getMerchant() {
        return $this->merchant;
    }

    public function setBuyer($first, $last, $email) {
        $this->buyer = [
            'name_first' => $first,
            'name_last' => $last,
            'email_address' => $email
        ];
    }


    public function setItem($item, $description) {
        $this->item = [
            'item_name' => $item,
            'item_description' => $description,
        ];
    }

    public function setAmount($amount) {
        $this->amount = number_format(sprintf("%.2f", $amount), 2, '.', '');
    }

    public function paymentForm($submitButton = false) {
        $this->button = $submitButton;
        $this->vars = $this->paymentVars();
        $this->buildQueryString();
        $this->vars['signature'] = md5($this->output);
        return $this->buildForm();
    }

    public function paymentVars() {
        if (config('payfast.testing')) {
            return array_merge($this->merchant,[
                'm_payment_id' => $this->m_payment_id,
                'amount' => $this->amount,
                'item_name' => $this->item['item_name'],
                'item_description' => $this->item['item_description'],
                'custom_int1' => $this->custom_int1,
                'custom_int2' => $this->custom_int2,
                'custom_int3' => $this->custom_int3,
                'custom_int4' => $this->custom_int4,
                'custom_int5' => $this->custom_int5,
                'custom_str1' => $this->custom_str1,
                'custom_str2' => $this->custom_str2,
                'custom_str3' => $this->custom_str3,
                'custom_str4' => $this->custom_str4,
                'custom_str5' => $this->custom_str5,
                'payment_method' => $this->payment_method,
                'subscription_type' => $this->subscription_type,
            ]);
        } 
		else {
            return array_merge($this->merchant, [
                'm_payment_id' => $this->m_payment_id,
                'amount' => $this->amount,
                'item_name' => $this->item['item_name'],
                'item_description' => $this->item['item_description'],
                'custom_int1' => $this->custom_int1,
                'custom_int2' => $this->custom_int2,
                'custom_int3' => $this->custom_int3,
                'custom_int4' => $this->custom_int4,
                'custom_int5' => $this->custom_int5,
                'custom_str1' => $this->custom_str1,
                'custom_str2' => $this->custom_str2,
                'custom_str3' => $this->custom_str3,
                'custom_str4' => $this->custom_str4,
                'custom_str5' => $this->custom_str5,
                'payment_method' => $this->payment_method,
                'subscription_type' => $this->subscription_type,
            ]);
        }
    }

    public function buildQueryString() {
        foreach ($this->vars as $key => $val) {
            if (!empty($val)) {
                $this->output .= $key . '=' . urlencode(trim($val)) . '&';
            }
        }
        $this->output = substr($this->output, 0, -1);
        $passPhrase = 'payfasT_2018';
        if (isset($passPhrase)) {
            $this->output .= '&passphrase=' . $passPhrase;
        }
    }

    public function buildForm() {
        $this->getHost();
        $htmlForm = '<form id="payfast-pay-form" action="https://' . $this->host . '/eng/process" method="post">';
        foreach ($this->vars as $name => $value) {
            $htmlForm .= '<input type="hidden" name="' . $name . '" value="' . $value . '">';
        }
        if ($this->button) {
            $htmlForm .= '<button type="submit">' . $this->getSubmitButton() . '</button>';
        }
        return $htmlForm . '</form><script>document.getElementById("payfast-pay-form").submit();</script>';
    }

    public function verify($request, $amount) {
        $this->setHeader();
        $this->response_vars = $request->all();
        foreach ($this->response_vars as $key => $val) {
            $this->vars[$key] = stripslashes($val);
        }
        $this->setAmount($amount);
        $this->buildQueryString();
        $this->validateSignature($request->get('signature'));
        //$this->validateHost($request);
        $this->validateAmnt($amount);
        $this->status = $request->get('payment_status');
        return $this;
    }
    
    public function validateAmnt($amount) {
          if ($this->amount === $amount) {
            return true;
        } else {
            return 'Invalid Amount';
        }
    }

    public function status() {
        return $this->status;
    }

    public function setHeader() {
        header('HTTP/1.0 200 OK');
        flush();
    }

    public function validateSignature($signature) {
        if ($this->vars['signature'] === $signature) {
            return true;
        } else {
            return 'Invalid Signature';
        }
    }

    public function validateHost($request) {
        $hosts = $this->getHosts();
        if (!in_array($request->server('REMOTE_ADDR'), $hosts)) {
            throw new Exception('Not a valid Host');
        }
        return true;
    }

    public function getHosts() {
        $hosts = [];
        foreach (config('payfast.hosts') as $host) {
            $ips = gethostbynamel($host);
            if (count($ips) > 0) {
                foreach ($ips as $ip) {
                    $hosts[] = $ip;
                }
            }
        }
        return array_unique($hosts);
    }

    public function getHost() {
        return $this->host =  'www.payfast.co.za';
    }

    public function getSubmitButton() {
        if (is_string($this->button)) {
            return $this->button;
        }
        if ($this->button == true) {
            return 'Pay Now';
        }
        return false;
    }

    public function responseVars() {
        return $this->response_vars;
    }
    
    public function setMpaymentId($m_payment_id){
        $this->m_payment_id = $m_payment_id;
    }

    public function setSubscriptionType($subscriptionType) {
        $this->subscription_type = $subscriptionType;
    }

    public function setCancelUrl($url) {
        $this->merchant['cancel_url'] = $url;
    }

    public function setReturnUrl($url) {
        $this->merchant['return_url'] = $url;
    }

    public function setNotifyUrl($url) {
        $this->merchant['notify_url'] = $url;
    }

    public function setCustomStr1($string = '') {
        $this->custom_str1 = $string;
    }

    public function setCustomStr2($string = '') {
        $this->custom_str2 = $string;
    }

    public function setCustomStr3($string = '') {
        $this->custom_str3 = $string;
    }

    public function setCustomStr4($string = '') {
        $this->custom_str4 = $string;
    }

    public function setCustomStr5($string = '') {
        $this->custom_str5 = $string;
    }

    public function setCustomInt1($int) {
        $this->custom_int1 = $int;
    }

    public function setCustomInt2($int) {
        $this->custom_int2 = $int;
    }

    public function setCustomInt3($int) {
        $this->custom_int3 = $int;
    }

    public function setCustomInt4($int) {
        $this->custom_int4 = $int;
    }

    public function setCustomInt5($int) {
        $this->custom_int5 = $int;
    }

    public function setPaymentMethod($method) {
        $this->payment_method = $method;
    }
}
