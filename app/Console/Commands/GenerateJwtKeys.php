<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateJwtKeys extends Command
{
    protected $signature = 'jwt:generate-keys';
    protected $description = 'Generate JWT RSA keys';

    public function handle()
    {
        // Generate the private key
        $privateKey = openssl_pkey_new([
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);

        // Export the private key
        openssl_pkey_export($privateKey, $privateKeyOut);

        // Extract the public key
        $publicKey = openssl_pkey_get_details($privateKey)["key"];

        // Save the keys to .env or files
        file_put_contents(base_path('.env'), "\nJWT_PRIVATE_KEY=\"{$privateKeyOut}\"\n", FILE_APPEND);
        file_put_contents(base_path('.env'), "\nJWT_PUBLIC_KEY=\"{$publicKey}\"\n", FILE_APPEND);

        // Output the keys to the console
        $this->info("Private Key:\n$privateKeyOut\n");
        $this->info("Public Key:\n$publicKey\n");
    }
}
