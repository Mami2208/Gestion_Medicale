<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OrthancService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('orthanc.url'),
            'auth' => [config('orthanc.username'), config('orthanc.password')],
            'headers' => ['Accept' => 'application/json']
        ]);
    }

    public function uploadDicom($file): string
    {
        try {
            $response = $this->client->post('/instances', [
                'body' => file_get_contents($file->getRealPath())
            ]);
            return json_decode($response->getBody(), true)['ID'];
        } catch (GuzzleException $e) {
            throw new \RuntimeException("Orthanc error: ".$e->getMessage());
        }
    }

    public function getInstanceFile(string $id): string
    {
        try {
            return $this->client->get("/instances/$id/file")->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new \RuntimeException("File retrieval failed: ".$e->getMessage());
        }
    }
}