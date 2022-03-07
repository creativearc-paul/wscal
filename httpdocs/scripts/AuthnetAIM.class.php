<?php

class AuthnetAIMException extends Exception {}

class AuthnetAIM
	{
    const LOGIN    = '7A87zbMCthw';
    const TRANSKEY = '83VXgEJt6S224DjV';
    const TEST     = false;
    const GODADDY  = false;

    private $params   = array();
    private $results  = array();
    private $approved = false;
    private $declined = false;
    private $error    = true;
    private $response;
    private $url;

    public function __construct()
    {
        if (!self::LOGIN || !self::TRANSKEY)
        {
            throw new AuthnetAIMException('You have not configured your Authnet login credentials.');
        }

        $this->url = 'https://secure.authorize.net/gateway/transact.dll';

		$this->params['x_test']			  = 'FALSE';
        $this->params['x_delim_data']     = 'TRUE';
        $this->params['x_delim_char']     = '|';
        $this->params['x_relay_response'] = 'FALSE';
        $this->params['x_url']            = 'FALSE';
        $this->params['x_version']        = '3.1';
        $this->params['x_method']         = 'CC';
        $this->params['x_type']           = 'AUTH_CAPTURE';
        $this->params['x_login']          = self::LOGIN;
        $this->params['x_tran_key']       = self::TRANSKEY;
    }

    public function __toString()
    {
        if (!$this->params)
        {
            return (string) $this;
        }

        $output  = '';
        $output .= '<table summary="Authnet Results" id="authnet">' . "\n";
        $output .= '<tr>' . "\n\t\t" . '<th colspan="2"><b>Outgoing Parameters</b></th>' . "\n" . '</tr>' . "\n";

        foreach ($this->params as $key => $value)
        {
            $output .= "\t" . '<tr>' . "\n\t\t" . '<td><b>' . $key . '</b></td>';
            $output .= '<td>' . $value . '</td>' . "\n" . '</tr>' . "\n";
        }

        if ($this->results)
        {
            $output .= '<tr>' . "\n\t\t" . '<th colspan="2"><b>Incomming Parameters</b></th>' . "\n" . '</tr>' . "\n";

            $response = array('Response Code', 'Response Subcode', 'Response Reason Code',
                              'Response Reason Text', 'Approval Code', 'AVS Result Code',
                              'Transaction ID', 'Invoice Number', 'Description', 'Amount',
                              'Method', 'Transaction Type', 'Customer ID', 'Cardholder First Name',
                              'Cardholder Last Name', 'Company', 'Billing Address', 'City',
                              'State', 'Zip', 'Country', 'Phone', 'Fax', 'Email', 'Ship to First Name',
                              'Ship to Last Name', 'Ship to Company', 'Ship to Address',
                              'Ship to City', 'Ship to State', 'Ship to Zip', 'Ship to Country',
                              'Tax Amount', 'Duty Amount', 'Freight Amount', 'Tax Exempt Flag',
                              'PO Number', 'MD5 Hash', 'Card Code (CVV2/CVC2/CID) Response Code',
                              'Cardholder Authentication Verification Value (CAVV) Response Code');

            foreach ($this->results as $key => $value)
            {
                if ($key > 40) break;
                $output .= "\t" . '<tr>' . "\n\t\t" . '<td><b>' . $response[$key] . '</b></td>';
                $output .= '<td>' . $value . '</td>' . "\n" . '</tr>' . "\n";
            }
        }

        $output .= '</table>' . "\n";
        return $output;
    }

    public function process($retries = 3)
    {
        $ch = curl_init($this->url);

        $count = 0;
        while ($count < $retries)
        {
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->params));
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            if (self::GODADDY)
            {
                curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
                curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                curl_setopt($ch, CURLOPT_PROXY, 'http://proxy.shr.secureserver.net:3128');
            }
            $this->response = curl_exec($ch);
            $this->parseResults();
            if ($this->getResultResponseFull() == 'Approved')
            {
                $this->approved = true;
                $this->declined = false;
                $this->error    = false;
                break;
            }
            else if ($this->getResultResponseFull() == 'Declined')
            {
                $this->approved = false;
                $this->declined = true;
                $this->error    = false;
                break;
            }
            $count++;
        }
        curl_close($ch);
    }

    private function parseResults()
    {
        $this->results = explode($this->params['x_delim_char'], $this->response);
    }

    public function setTransaction($cardnum, $expiration, $amount, $cvv = null, $invoice = null, $tax = null)
    {
        $this->params['x_card_num']    = (string) trim($cardnum);
        $this->params['x_exp_date']    = (string) trim($expiration);
        $this->params['x_amount']      = (float)  $amount;
        $this->params['x_invoice_num'] = (int)    $invoice;
        $this->params['x_tax']         = (float)  $tax;
        $this->params['x_card_code']   = str_pad((int) $cvv, 3, "0", STR_PAD_LEFT);
        if (empty($this->params['x_card_num']))
        {
            throw new AuthnetAIMException('Required information for transaction processing omitted: credit card number');
        }
        if (empty($this->params['x_exp_date']))
        {
            throw new AuthnetAIMException('Required information for transaction processing omitted: expiration date');
        }
        if (empty($this->params['x_amount']))
        {
            throw new AuthnetAIMException('Required information for transaction processing omitted: dollar amount');
        }
        if (!$this->validateExpirationDate())
        {
            throw new AuthnetAIMException('Expiration date is in an invalid format');
        }
    }

    public function setParameter($field = '', $value = null)
    {
        $field = (is_string($field)) ? trim($field) : $field;
        $value = (is_string($value)) ? trim($value) : $value;
        if (!is_string($field))
        {
            throw new AuthnetAIMException('setParameter() arg 1 must be a string: ' . gettype($field) . ' given.');
        }
        if (!is_string($value) && !is_numeric($value) && !is_bool($value))
        {
            throw new AuthnetAIMException('setParameter() arg 2 (' . $field . ')must be a string, integer, or boolean value: ' . gettype($value) . ' given.');
        }
        if (empty($field))
        {
            throw new AuthnetAIMException('setParameter() requires a parameter field to be named.');
        }
        if ($value === '')
        {
            throw new AuthnetAIMException('setParameter() requires a parameter value to be assigned: $field');
        }
        $this->params[$field] = $value;
    }

    public function setTransactionType($type = '')
    {
        $type      = strtoupper(trim($type));
        $typeArray = array('AUTH_CAPTURE', 'AUTH_ONLY', 'PRIOR_AUTH_CAPTURE', 'CREDIT', 'CAPTURE_ONLY', 'VOID');
        if (!in_array($type, $typeArray))
        {
            throw new AuthnetAIMException('setTransactionType() requires a valid value to be assigned.');
        }
        $this->params['x_type'] = $type;
    }

    public function setEcheck($aba, $account, $accttype, $bankname, $bankacctname, $echecktype, $transtype = 'AUTH_CAPTURE')
    {
        $amount       = (float) $amount;
        $aba          = trim($aba);
        $account      = trim($account);
        $accttype     = strtoupper(trim($accttype));
        $bankname     = substr(strtoupper(trim($bankname)), 0, 50);
        $bankacctname = substr(strtoupper(trim($bankacctname)), 0, 22);
        $echecktype   = strtoupper(trim($echecktype));
        $transtype    = strtoupper(trim($transtype));

        if (!preg_match('/^\d{1,4}(\.?\d{0,2})?$/', $amount))
        {
            throw new AuthnetAIMException('setEcheck() requires a valid dollar amount.');
        }
        if (!preg_match('/^\d{9}$/', $aba))
        {
            throw new AuthnetAIMException('setEcheck() requires a nine digit ABA/routing number.');
        }
        if (!preg_match('/^\d{6,20}$/', $account))
        {
            throw new AuthnetAIMException('setEcheck() requires a valid account number.');
        }
        if (empty($bankname))
        {
            throw new AuthnetAIMException('setEcheck() requires a valid bank name.');
        }
        else
        {
            $bankname = substr($bankname, 0, 50);
        }
        if (empty($bankacctname))
        {
            throw new AuthnetAIMException('setEcheck() requires the name that appears on the checking account.');
        }

        $accountTypeArray = array('CHECKING', 'BUSINESSCHECKING', 'SAVINGS');
        if (!in_array($accttype, $accountTypeArray))
        {
            throw new AuthnetAIMException('setEcheck() requires a valid bank account type to be assigned.');
        }

        $echeckTypeArray = array('CCD', 'PPD', 'TEL', 'WEB');
        if (!in_array($echecktype, $echeckTypeArray))
        {
            throw new AuthnetAIMException('setEcheck() requires a valid echeck type value to be assigned.');
        }

        $transTypeArray = array('AUTH_CAPTURE', 'CREDIT');
        if (!in_array($transtype, $transTypeArray))
        {
            throw new AuthnetAIMException('setEcheck() requires a valid transaction type.');
        }

        $this->params['x_amount']         = $amount;
        $this->params['x_method']         = 'ECHECK';
        $this->params['x_bank_aba_code']  = $aba;
        $this->params['x_bank_acct_num']  = $account;
        $this->params['x_bank_acct_type'] = $accttype;
        $this->params['x_bank_name']      = $bankname;
        $this->params['x_bank_acct_name'] = $bankacctname;
        $this->params['x_type']           = $transtype;
        $this->params['x_echeck_type']    = $echecktype;
    }

    private function validateExpirationDate()
    {
        if (preg_match('|^\d{4}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\d{2}/\\d{2}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\\d{2}-\d{2}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\d{6}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\d{2}/\d{4}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\d{2}-\d{4}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\d{4}-\d{2}-\d{2}$|', $this->params['x_exp_date'])) return true;
        if (preg_match('|^\d{4}/\d{2}/\d{2}$|', $this->params['x_exp_date'])) return true;
        return false;
    }

    public function getResultResponse()
    {
        return $this->results[0];
    }

    public function getResultResponseFull()
    {
        $response = array('', 'Approved', 'Declined', 'Error');
        return $response[$this->results[0]];
    }

    public function isApproved()
    {
        return $this->approved;
    }

    public function isDeclined()
    {
        return $this->declined;
    }

    public function isError()
    {
        return $this->error;
    }

    public function isConfigError()
    {
        $reasons = array(5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 28, 29, 30, 31, 33, 34, 35, 36,
                        37, 38, 39, 40, 42, 43, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 64, 66, 67, 68,
                        69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 91, 92, 93, 94, 95, 96,
                        97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114,
                        115, 116, 117, 118, 119, 120, 121, 122, 123, 170, 171, 172, 173, 174, 175, 176, 177,
                        178, 179, 180, 181, 182, 183, 184, 185, 243, 244, 245, 246, 247, 261, 270, 271);
        return in_array($this->getResponseCode(), $reasons);
    }

    public function isTempError()
    {
        $reasons = array(19, 20, 21, 22, 23, 24, 25, 26, 57, 58, 59, 60, 61, 62, 63);
        return in_array($this->getResponseCode(), $reasons);
    }

    public function getResponseSubcode()
    {
        return $this->results[1];
    }

    public function getResponseCode()
    {
        return $this->results[2];
    }

    public function getResponseText()
    {
        return $this->results[3];
    }

    public function getAuthCode()
    {
        return $this->results[4];
    }

    public function getAVSResponse()
    {
        return $this->results[5];
    }

    public function getTransactionID()
    {
        return $this->results[6];
    }

    public function getInvoiceNumber()
    {
        return $this->results[7];
    }

    public function getDescription()
    {
        return $this->results[8];
    }

    public function getAmount()
    {
        return $this->results[9];
    }

    public function getPaymentMethod()
    {
        return $this->results[10];
    }

    public function getTransactionType()
    {
        return $this->results[11];
    }

    public function getCustomerID()
    {
        return $this->results[12];
    }

    public function getCHFirstName()
    {
        return $this->results[13];
    }

    public function getCHLastName()
    {
        return $this->results[14];
    }

    public function getCompany()
    {
        return $this->results[15];
    }

    public function getBillingAddress()
    {
        return $this->results[16];
    }

    public function getBillingCity()
    {
        return $this->results[17];
    }

    public function getBillingState()
    {
        return $this->results[18];
    }

    public function getBillingZip()
    {
        return $this->results[19];
    }

    public function getBillingCountry()
    {
        return $this->results[20];
    }

    public function getPhone()
    {
        return $this->results[21];
    }

    public function getFax()
    {
        return $this->results[22];
    }

    public function getEmail()
    {
        return $this->results[23];
    }

    public function getShippingFirstName()
    {
        return $this->results[24];
    }

    public function getShippingLastName()
    {
        return $this->results[25];
    }

    public function getShippingCompany()
    {
        return $this->results[26];
    }

    public function getShippingAddress()
    {
        return $this->results[27];
    }

    public function getShippingCity()
    {
        return $this->results[28];
    }

    public function getShippingState()
    {
        return $this->results[29];
    }

    public function getShippingZip()
    {
        return $this->results[30];
    }

    public function getShippingCountry()
    {
        return $this->results[31];
    }

    public function getTaxAmount()
    {
        return $this->results[32];
    }

    public function getDutyAmount()
    {
        return $this->results[33];
    }

    public function getFreightAmount()
    {
        return $this->results[34];
    }

    public function getTaxExemptFlag()
    {
        return $this->results[35];
    }

    public function getPONumber()
    {
        return $this->results[36];
    }

    public function getMD5Hash()
    {
        return $this->results[37];
    }

    public function getCVVResponse()
    {
        return $this->results[38];
    }

    public function getCAVVResponse()
    {
        return $this->results[39];
    }
}

?> 
