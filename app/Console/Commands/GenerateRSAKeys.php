<?php

// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use Illuminate\Support\Facades\File;

// class GenerateJWTKeys extends Command
// {
//     protected $signature = 'jwt:generate-keys';
//     protected $description = 'Generate JWT RSA keys';

//     public function __construct()
//     {
//         parent::__construct();
//     }

//     public function handle()
//     {
//         // Define the path to save the keys
//         $privateKeyPath = storage_path('keys/private.key');
//         $publicKeyPath = storage_path('keys/public.key');

//         // Create the directory if it doesn't exist
//         if (!File::exists(dirname($privateKeyPath))) {
//             File::makeDirectory(dirname($privateKeyPath), 0755, true);
//         }

//         // Generate RSA private key
//         $privateKey = openssl_pkey_new([
//             'private_key_bits' => 2048,
//             'private_key_type' => OPENSSL_KEYTYPE_RSA,
//         ]);

//         // Export the private key
//         openssl_pkey_export_to_file($privateKey, $privateKeyPath, 'sO9sH6qT8jA0wV5gE5eT3kY3');

//         // Extract the public key from the private key
//         $publicKey = openssl_pkey_get_details($privateKey)['key'];
//         File::put($publicKeyPath, $publicKey);

//         $this->info('JWT keys generated successfully.');
//     }
// }
