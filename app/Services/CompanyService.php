<?php
namespace App\Services;
use App\Models\Packages;
use App\Models\Patient;
use App\Traits\BreadCrumpTrait;
use App\Traits\CompanyIdentityTrait;
use Illuminate\Http\Request;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    use BreadCrumpTrait, CompanyIdentityTrait;
    public function editCompanyDetails(Request $request, Patient $client)
    {
        // Update client and contact details
        $this->updateClientAndContact($request, $client);

        // Check if we are on a tenant domain
        if ($this->isTenantDomain()) {
            $this->updateTenantDatabase($request, $client);
        }
    }

    private function updateClientAndContact(Request $request, Patient $client)
    {
        $client->update($request->all());

        // Load and update contact details
        $client->load('contacts');
        $contact = $client->contacts;

        if ($contact) {
            $contact->update([
                'first_name' => $request->first_name ?? '',
                'surname' => $request->surname ?? '',
                'email' => $request->email ?? '',
                'contact_number' => $request->contact_number ?? '',
            ]);
        }
    }

    private function isTenantDomain(): bool
    {
        $centralDomains = env('CENTRAL_DOMAINS');
        $host = request()->getHost();
        return $host !== $centralDomains;
    }

    private function updateTenantDatabase(Request $request, Patient $client)
    {
        $currentDatabaseName = DB::connection()->getDatabaseName();
        $tenantDatabaseName = $client->database_name;

        // Temporarily switch to tenant database
        $this->switchDatabaseConnection($tenantDatabaseName);

        // Update tenant database
        $tenantClient = Patient::where('database_name', $tenantDatabaseName)->first();
        if ($tenantClient) {
            $this->updateClientAndContact($request, $tenantClient);
        }

        // Revert to the original database
        $this->revertDatabaseConnection($currentDatabaseName);
    }

    private function switchDatabaseConnection($databaseName)
    {
        $dbConfig = [
            'driver' => env('DB_CONNECTION'),
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
        ];

        Config::set('database.connections.tenant', $dbConfig);
        DB::purge('tenant');
        DB::reconnect('tenant');
        DB::setDefaultConnection('tenant');
    }

    private function revertDatabaseConnection($databaseName)
    {
        DB::disconnect('tenant');
        DB::purge($databaseName);
        DB::reconnect($databaseName);
        DB::setDefaultConnection($databaseName);
    }


    public function renderEditCompanyPage()
    {
        $data = $this->breadcrumb(
            'Client ',
            'Client page for Client related settings',
            'patient_details',
            'Client Profile',
            'Client Details'
        );

        // get central domain
        $centralDomains = env('CENTRAL_DOMAINS');
        // get host url
        $host = request()->getHost();

        if ($host === $centralDomains)
        {
            // Main system
            $client = Patient::latest()->first();
            $client->load('packages','contacts');
			

        }
        else
        {
            // Likely a tenant
            $client = Patient::latest()->first();
            $client->load('packages','contacts');
        }

        	
        $data['client'] = $client;
        $data['packages'] = Packages::getPackages();
        return $data;
    }

}
