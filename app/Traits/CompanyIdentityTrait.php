<?php

namespace App\Traits;

use App\Models\CompanyIdentity;

trait CompanyIdentityTrait
{
    /**
     * @return array
     */
    protected function CompanyIdentityDetails()
    {
        $companyDetails = CompanyIdentity::systemSettings();

        $data['logo'] = (!empty($companyDetails['company_logo_url'])) ?
            asset('uploads/'.$companyDetails['company_logo_url'] ) : asset('images/logo_default.png');

        $data['logos'] = (!empty($companyDetails['company_logo_url'])) ?
            asset('uploads/' . $companyDetails['company_logo_url']) :
            $data['system_background_image_url'] = $companyDetails['system_background_image_url'];

        $data['hospital'] = $companyDetails['hospital'];
        $data['support'] = $companyDetails['support_email'];
        $data['mailing_address'] = $companyDetails['mailing_address'];
        $data['mailing_name'] = $companyDetails['mailing_name'];
        $data['company_name'] = $companyDetails['company_name'];
        $data['header_name_bold'] = $companyDetails['header_name_bold'];
        $data['header_acronym_bold'] = $companyDetails['header_acronym_bold'];
        $data['header_acronym_regular'] = $companyDetails['header_acronym_regular'];
        $data['address'] = $companyDetails['address'];
        $data['suburb'] = $companyDetails['suburb'];
        $data['city'] = $companyDetails['city'];
        $data['postal_code'] = $companyDetails['postal_code'];
        $data['monthly_revenue_target'] = $companyDetails['monthly_revenue_target'];
        $data['daily_revenue_target'] = $companyDetails['daily_revenue_target'];
        $data['terms_and_conditions'] = $companyDetails['terms_and_conditions'];
        $data['admin_email'] = $companyDetails['admin_email'];
        return $data;
    }

}
