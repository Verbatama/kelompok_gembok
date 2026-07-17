<?php
namespace App\Services\GenieDash;

use App\Models\GenieCredentialsDash;
use Illuminate\Support\Facades\Http;

class Helper
{
    private $host;
    private $port;
    private $username;
    private $password;
    private $baseUrl;

    public function __construct($host = null, $port = 7557, $username = null, $password = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->baseUrl = "http://{$this->host}:{$this->port}";
    }

    public function isGenieConfigured()
    {
        return GenieCredentialsDash::where('is_connected', 1)->exists();
    }

    // Ambil data divice dari genieacs API
    public function getDevices(array $query = [], int $limit = 0, int $skip = 0)
    {
        $params = [];

        if (!empty($query)) {
            $params['query'] = json_encode($query);
        }

        if ($limit > 0) {
            $params['limit'] = $limit;
        }

        if ($skip > 0) {
            $params['skip'] = $skip;
        }

        $response = Http::withBasicAuth($this->username, $this->password)
            ->get($this->baseUrl . '/devices', $params);

        if (!$response->successful()) {
            return [];
        }

        return $response->json();
    }

    public function getDeviceStats()
    {
        $devices = $this->getDevices();

        return [
            'total' => count($devices),
        ];
    }

    //

    public function addWanConfig(array $input): array
    {
        $deviceId = $input['device_id'];
        $connectionIndex = (int) $input['connection_index'];
        $connectionType = strtolower($input['connection_type']);
        $connectionName = $input['name'] ?? '';
        $parameters = $input['parameters'];

        // Validasi
        if ($connectionIndex < 1 || $connectionIndex > 8) {
            return [
                'success' => false,
                'message' => 'Connection index harus antara 1-8.'
            ];
        }

        if (!in_array($connectionType, ['ppp', 'ip'])) {
            return [
                'success' => false,
                'message' => 'Connection type harus ppp atau ip.'
            ];
        }

        // Base Path TR-069
        $basePath = "InternetGatewayDevice.WANDevice.1.WANConnectionDevice.{$connectionIndex}";

        $basePath .= $connectionType === 'ppp'
            ? '.WANPPPConnection.1'
            : '.WANIPConnection.1';

        // Parameter yang diizinkan
        $allowedParams = [
            'Name',
            'Enable',
            'ConnectionType',
            'Username',
            'Password',
            'NATEnabled',
            'X_CT-COM_ServiceList',
            'X_CT-COM_LanInterface',
            'X_CT-COM_VLANID',
        ];

        $genieParams = [];

        if (!empty($connectionName)) {
            $genieParams[$basePath . '.Name'] = $connectionName;
        }

        foreach ($parameters as $key => $value) {
            if (!in_array($key, $allowedParams)) {
                return [
                    'success' => false,
                    'message' => "Parameter {$key} tidak valid."
                ];
            }

            $genieParams[$basePath . '.' . $key] = $value;
        }

        // Validasi PPP
        if (
            $connectionType === 'ppp' &&
            (
                empty($parameters['Username']) ||
                empty($parameters['Password'])
            )
        ) {
            return [
                'success' => false,
                'message' => 'Username dan Password wajib diisi.'
            ];
        }

        // Kirim ke GenieACS
        return $this->setParameterValues($deviceId, $genieParams);
    }

    public function setParameterValues($deviceId, $parameters, $timeout = 3000)
    {
        $encodedId = rawurlencode($deviceId);

        $endpoint = "/devices/{$encodedId}/tasks?timeout={$timeout}&connection_request";

        $data = [
            'name' => 'setParameterValues',
            'parameterValues' => $parameters
        ];

        return $this->request($endpoint, 'POST', $data);
    }
}
