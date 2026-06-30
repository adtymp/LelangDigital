<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    protected $client;
    protected $driveService;

    public function __construct()
    {
        $this->client = new Client();
        $credentialsPath = base_path(env('GOOGLE_DRIVE_CREDENTIALS_PATH', 'storage/app/google-credentials.json'));
        
        if (file_exists($credentialsPath)) {
            $this->client->setAuthConfig($credentialsPath);
            $this->client->addScope(Drive::DRIVE_READONLY);
        }
        
        $this->driveService = new Drive($this->client);
    }

    /**
     * Mengekstrak File ID dari Link Share Google Drive
     */
    public function extractFileId($url)
    {
        if (preg_match('/src="([^"]+)"/', $url, $matches)) {
            $url = $matches[1];
        }
        
        $pattern = '/(?:id=|folders\/|d\/)([a-zA-Z0-9-_]{25,})/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return $url; // Kembalikan string asli jika inputnya langsung berupa ID
    }

    /**
     * Mengunduh file dari Google Drive ke server lokal secara sementara
     */
    public function downloadFile($fileId, $outputPath)
    {
        try {
            $response = $this->driveService->files->get($fileId, ['alt' => 'media']);
            $content = $response->getBody()->getContents();
            
            // Buat folder output jika belum ada
            $directory = dirname($outputPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            file_put_contents($outputPath, $content);
            return true;
        } catch (\Exception $e) {
            Log::error("Gagal mengunduh file dari Google Drive ID {$fileId}: " . $e->getMessage());
            throw new \Exception("Gagal mengunduh file dari Google Drive. Pastikan file sudah dibagikan ke Service Account.");
        }
    }
}