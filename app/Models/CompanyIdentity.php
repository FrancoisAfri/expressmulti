<?php

namespace App\Models;

use App\Traits\Uuids;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;

class CompanyIdentity extends Model
{
    use HasFactory , Uuids,  LogsActivity , Loggable;

    //Specify the table name
    public $table = 'company_identies';

    //Mass assignable fields
    protected $fillable = [
        'company_name', 'full_company_name', 'header_name_bold', 'header_name_regular'
        , 'header_acronym_bold','header_acronym_regular', 'company_logo'
        , 'sys_theme_color', 'mailing_name', 'mailing_address', 'support_email'
        ,'company_website','password_expiring_month','system_background_image',
        'login_background_image','monthly_revenue_target','daily_revenue_target','admin_email','debit_order_form'
    ];

    protected static $logAttributes = ['company_name', 'full_company_name', 'company_logo', 'type', 'status'];

    protected static $recordEvents = ['created', 'updated'];

    protected static $logName = 'company Identity';


    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "Company Identity   {$eventName}  ";
    }

    /**
     * Getter: get the location of the logo on the server.
     *
     * @return string
     */
    public function getCompanyLogoUrlAttribute()
    {
        if (! empty($this->company_logo)) return  asset('uploads/'.$this->company_logo);
//            Storage::disk('local')->url("logos/$this->company_logo");
        else return '';
    }
    public function getSystemBackgroundImageUrlAttribute()
    {
        if (! empty($this->company_logo)) return  asset('uploads/'.$this->system_background_image);
        if (! empty($this->system_background_image)) return Storage::disk('local')->url("logos/$this->system_background_image");
        else return '';
    }
    public function getLoginbackgroundImageUrlAttribute()
    {
//        if (! empty($this->company_logo)) return  asset('uploads/'.$this->login_background_image);
        if (! empty($this->login_background_image)) return $this->login_background_image;
        else return '';
    }

    /**
     * Static helper function that return the company identity data from the setup or the default values.
     *
     * @param string $settingName
     * @return string
     * @return array
     */
    public static function systemSettings($settingName = null)
    {
        $companyDetails = (Schema::hasTable('company_identies')) ? CompanyIdentity::first() : null;

        $terms = "
        By signing these terms and conditions, the signatory acknowledges that these terms and conditions
        are applicable to Dr Vinola Naidoo.

        I, the undersigned hereby agree to the following:
        1. Liability for Payment

        1.1 I undertake personal liability for all amounts payable to this practice in respect of services
        rendered to the patients indicated on this patient information form.

        1.2 The fact that the practice may submit a claim to the medical aid/ scheme or Insurer, will not in
        any way relieve me of my liability as aforesaid.

        1.3 l acknowledge that shall be liable for: » Any bank charges levied against the practice in the
        event of a bank declining to honour any method of payment made by myself; » Any legal costs
        incurred by the practice in recovering any amount due, calculated on the attorney and own client
        scale, including tracing fees and collection commission and administrative costs.

        1.4 | acknowledge that in accordance with the provisions of Section 53(1) of the Health Professions
        Act of 1974 (duly amended) and section 6 (C) of the National Health Act 61 of 2003, the costs
        associated with all medical services rendered by the professionals have been discussed and fully
        explained to me, to the extent required in law and professional ethics, and that am given
        opportunity to request more information.

        1.5 | undertake to notify the practice of any change in my indicated address, contact details or
        medical aid/ scheme details.

        1.6 | confirm that | am aware that this practice’s fees are charged at up to 3 times the Reference
        Price Lists

        1.7 I acknowledge that the fees charged by the practice may be different from the benefits to be
        paid by the medical aid / scheme, and | accept responsibility for any co-payment resulting fromthe
        difference between these two amounts.

        1.8 | agree that in the event of any amounts owed to the practice are not paid on the due date, the
        practice shall be entitled to charge interest on the outstanding amount calculated as from the due
        date of payment at the maximum rate which may be legally charged.

        1.9 Appointments not cancelled 2hours in advance will be charged for.

        2. Medical Scheme Benefit

        2.1 | warrant that, as indicated, the patient is a current, paid-up member or dependant of such
        member under the medical aid / scheme and that the patient has not resigned or services have not
        been terminated.

        2.2 | authorize the practice to submit the account to the relevant medical aid / scheme for payment
        on behalf of the patient.

        2.3 undertake to: Settle the account within 30 days in case of non-payment from the medical aid /
        scheme.

        2.4 | acknowledge that pre-authorization for treatment / services do not guarantee payment by the
        medical aid / scheme, and that it remains my responsibility to obtain such authorization if required
        by the medical aid / scheme.

        2.5 | acknowledge that if the practice is nota network doctor as indicated by the medical aid, that |
        will be liable forall costs pertaining to treatment.

        3. Disclosure of Medical Information:

        3.1 The practice is hereby authorized to disclose to the medical aid / scheme, orinsureror to whom
        a claim is submitted in relation to amounts payable to the practice, full details as tothe nature,
        diagnosis, condition or treatment of the patient.

        3.2 The responsible person and/or patient has been informed that in certain circumstances, such as
        disclosure of ICD-10 codes, the exact consequences of disclosing such information are unknown to
        the practice and that information relating to these consequences must be obtained by a responsible
        person and/or person from the third party to whom the information is disclosed.

        4. Exclusion of Liability:

        4.1 The practice and its employees shall not be liable for, and | hereby indemnify the practice and its
        employees from, all liability for any loss, injury and/or damage of whatsoever nature suffered by
        whomever, including, but not limited to, loss or damage (direct, indirect or inconsequential), any
        injury (including fatal), sustained by and/or harm caused to the patient or any disease (including
        terminal) contracted by the patient, what ever the cause may be, whilst receiving treatment of any
        other services, whether arising, either directly or indirectly, out of any omission, delict of contract by
        the practice or its employees.

        4.2 | acknowledge that the practice hours are displayed and advertised for the medical practice, and
        that these hours may not necessarily be the hours of the attending medical doctor.

        5. South African Jurisdiction and Law: The patient, guardian and guarantor (as may be applicable):
        5.1 Consent and submit to the exclusive jurisdiction of the appropriate Magistrate’s or High Court of
        South Africa in respect of any dispute, which arises from or is in any way connected with the terms
        and conditions of treatment / services rendered, and agree that disputes of whatsoever nature will
        be subject to and governed exclusively by the laws of the Republic of South Africa, and the
        appropriate Court of South Africa.

        6. Minor patients and warranty of authority and indemnity:

        6.1 Where the patient is a minor, is unmarried and below the age of 18 years, then the minor's
        guardian(s) shall sign this contract in their personal and representative capacities and in so doing
        accept interalia responsibility for payment in full to the practice and warrant their authority to
        waive the minor's rights and agree to the disclaimer and indemnify the practice and associated
        company, directors and employees in respect of any damages, which arise from a breach of contract.

        6.2 In terms of Family Law, parents are jointly and severally liable for payment of the practice fees of
        a minor patient irrespective of their marital status. In the event the event of non-payment of the
        practice fees, the practice will hold both parents liable irrespective of any maintenance and court
        orders which may exist between the parties.

        7. General: The patient, guardian and guarantor (as may be applicable):

        7.11, the patient/guardian, guarantor, chooses as my domicilium citandi et executandithe
        residential address recorded above under the heading “Patient information”.

        7.2 Agree that all patient records remain the property of the practice, and shall only be released on
        demand by an authorized person, with the discretion of the practice.

        7.3 Agree that if any provision of this agreement should be /become invalid, unenforceable,
        defective or illegal for any reason whatsoever, then that provision shall be dee med to be severable
        from the remaining provisions of this agreement, which shall continue in full force and effect.

        8. Warranty of authority and indemnity:

        8.1 The signatory (other than the guardian or guarantor) warrants, that where the signatory is not
        the patient, the signatory has the authority to contract on behalf of the patient and act as the
        patient’s agent in all respect, including authority to waive the patient’s rights and agree to the
        disclaimers and indemnities in the respects set out in this contract.

        9. Terms of this contract read, understood and agreed:

        9.1 The signatory warrants that the signatory has read, understood and agreed to the terms and
        conditions set out herein including the disclaimer of liability and indemnities and contracts on such
        terms and conditions.

        9.2 | understand that | am entitled to obtain a copy of this document.
        Express Consent

        “By signing this contract and/or informed consent form, you/*the parent/guardian agree(s) to the
        use of your/*the patient's personal information as required under the Protection of Personal
        Information Act, 2013. You /*the patient also consent to the sharing of your /*the patient’s personal
        information with third parties such as other medical professionals for purposes of rendering
        treatment to you /*the patient and with medical schemes for billing purposes” ";

        $settings = [];
        $settings['hospital'] = ($companyDetails && $companyDetails->hospital) ? $companyDetails->hospital : 'Sandton Hospital';
        $settings['header_name_bold'] = ($companyDetails && $companyDetails->header_name_bold) ? $companyDetails->header_name_bold : 'Mkhaya MK';
        $settings['header_name_regular'] = ($companyDetails && $companyDetails->header_name_regular) ? $companyDetails->header_name_regular : 'MK';
        $settings['header_acronym_bold'] = ($companyDetails && $companyDetails->header_acronym_bold) ? $companyDetails->header_acronym_bold : 'M';
        $settings['header_acronym_regular'] = ($companyDetails && $companyDetails->header_acronym_regular) ? $companyDetails->header_acronym_regular : 'MK';
        $settings['sys_theme_color'] = ($companyDetails && $companyDetails->sys_theme_color) ? $companyDetails->sys_theme_color : 'blue';
        $settings['mailing_address'] = ($companyDetails && $companyDetails->mailing_address) ? $companyDetails->mailing_address : 'noreply@mkhayamk.co.za';
        $settings['mailing_name'] = ($companyDetails && $companyDetails->mailing_name) ? $companyDetails->mailing_name : 'Mkhaya MK';
        $settings['company_name'] = ($companyDetails && $companyDetails->company_name) ? $companyDetails->company_name : 'Mkhaya MK';
        $settings['full_company_name'] = ($companyDetails && $companyDetails->full_company_name) ? $companyDetails->full_company_name : 'Mkhaya MK (PTY) LTD';
        $settings['support_email'] = ($companyDetails && $companyDetails->support_email) ? $companyDetails->support_email : 'support@mkhayamk.co.za';
        $settings['address'] = ($companyDetails && $companyDetails->address) ? $companyDetails->address : '52 Englewold Dr' ;
        $settings['suburb'] = ($companyDetails && $companyDetails->suburb) ? $companyDetails->suburb : 'Rosebank' ;
        $settings['city'] = ($companyDetails && $companyDetails->city) ? $companyDetails->city : 'Johannesburg,' ;
        $settings['postal_code'] = ($companyDetails && $companyDetails->postal_code) ? $companyDetails->postal_code : '2196' ;
        $settings['company_logo_url'] = ($companyDetails && $companyDetails->company_logo) ? $companyDetails->company_logo : '' ;
        $settings['monthly_revenue_target'] = ($companyDetails && $companyDetails->monthly_revenue_target) ? $companyDetails->monthly_revenue_target : 0.00 ;
        $settings['daily_revenue_target'] = ($companyDetails && $companyDetails->daily_revenue_target) ? $companyDetails->daily_revenue_target : 0.00 ;
        $settings['system_background_image_url'] = ($companyDetails && $companyDetails->system_background_image_url) ? $companyDetails->system_background_image_url : '';
        $settings['login_background_image_url'] = ($companyDetails && $companyDetails->login_background_image_url) ? $companyDetails->login_background_image_url : '';
        $settings['terms_and_conditions'] = ($companyDetails && $companyDetails->terms_and_conditions) ? $companyDetails->terms_and_conditions : $terms;
        $settings['admin_email'] = ($companyDetails && $companyDetails->admin_email) ? $companyDetails->admin_email : '';

        if ($settingName != null) {
            if (array_key_exists($settingName, $settings)) return $settings[$settingName];
            else return null;
        }
        else return $settings;
    }


    public static function getCompanyDetails(){
        return CompanyIdentity::first();
    }

    public function getExpectedRevenue(){
       return CompanyIdentity::select('monthly_revenue_target')->first();
    }

}
