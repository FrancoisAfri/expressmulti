<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class InvoiceCompanyProfile extends Model
{
    use HasFactory;

    protected $table = 'invoice_company_profile';

    protected $fillable = [
        'company_name', 'registration_number', 'vat_number',
        'bank_name', 'bank_branch_code', 'bank_account_name',
        'bank_account_number', 'validity_period', 'letter_head',
        'status','note'
    ];


    /**
     * @param $settingName
     * @return array|mixed|string|null
     */
    public static function invoiceSettings($settingName = null)
    {
        $InvoiceCompanyProfile = (Schema::hasTable('invoice_company_profile')) ? InvoiceCompanyProfile::first() : null;

        $settings = [];
        $settings['company_name'] = ($InvoiceCompanyProfile && $InvoiceCompanyProfile->company_name) ? $InvoiceCompanyProfile->company_name : 'MKhaya MK (PTY)LTD';
        $settings['registration_number'] = ($InvoiceCompanyProfile && $InvoiceCompanyProfile->registration_number) ? $InvoiceCompanyProfile->registration_number : '2019/023490/07';
        $settings['vat_number'] = ($InvoiceCompanyProfile && $InvoiceCompanyProfile->vat_number) ? $InvoiceCompanyProfile->vat_number : '';
        $settings['bank_name'] = ($InvoiceCompanyProfile && $InvoiceCompanyProfile->bank_name) ? $InvoiceCompanyProfile->bank_name : 'FNB';
        $settings['bank_branch_code'] = ($InvoiceCompanyProfile && $InvoiceCompanyProfile->bank_branch_code) ? $InvoiceCompanyProfile->bank_branch_code : '270243';
        $settings['bank_account_name'] = ($InvoiceCompanyProfile && $InvoiceCompanyProfile->bank_account_name) ? $InvoiceCompanyProfile->bank_account_name : 'MKhaya MK (PTY)LTD';
        $settings['bank_account_number'] = ($InvoiceCompanyProfile && $InvoiceCompanyProfile->bank_account_number) ? $InvoiceCompanyProfile->bank_account_number : '62799179083';
        $settings['validity_period'] = ($InvoiceCompanyProfile && $InvoiceCompanyProfile->validity_period) ? $InvoiceCompanyProfile->validity_period : '3';
        $settings['letter_head'] = ($InvoiceCompanyProfile && $InvoiceCompanyProfile->letter_head) ? $InvoiceCompanyProfile->letter_head : '';
        $settings['note'] = ($InvoiceCompanyProfile && $InvoiceCompanyProfile->note) ? $InvoiceCompanyProfile->note : 'All accounts are to be paid within 7 days from receipt of invoice.
         To be paid by cheque or credit card or direct payment online. If account is not paid within 7 days the credits details supplied as confirmation of
         work undertaken will be charged the agreed quoted fee noted above. ';


        if ($settingName != null) {
            if (array_key_exists($settingName, $settings)) return $settings[$settingName];
            else return null;
        }
        else return $settings;
    }
}
