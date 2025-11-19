<?php
require_once 'config/config.php';

class SupabaseConnection {
    private $baseUrl;
    private $apiKey;

    public function __construct($baseUrl, $apiKey) {
        $this->baseUrl = rtrim($baseUrl, '/') . '/rest/v1/';
        $this->apiKey = $apiKey;
    }

    private function request($method, $table, $params = [], $body = null) {
        $url = $this->baseUrl . $table;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $headers = [
            'apikey: ' . $this->apiKey,
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("cURL error: $error");
        }

        return json_decode($response, true);
    }

    public function select($table, $filters = []) {
        return $this->request('GET', $table, $filters);
    }

    public function insert($table, $data) {
        return $this->request('POST', $table, [], $data);
    }

    public function update($table, $filters, $data) {
        return $this->request('PATCH', $table, $filters, $data);
    }

    public function delete($table, $filters) {
        return $this->request('DELETE', $table, $filters);
    }
}

class Database {
    public function getConnection() {
        return new SupabaseConnection(SUPABASE_URL, SUPABASE_API_KEY);
    }
}
