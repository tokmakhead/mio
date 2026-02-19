<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all gateways
        $gateways = PaymentGateway::all();

        foreach ($gateways as $gateway) {
            // Check if config is already array (due to model casting)
            // If model still has 'array' cast, $gateway->config comes as array.
            // But we want to encrypt it so it stores as encrypted string in DB.

            // However, after this migration runs, we will change model cast to 'encrypted:array'.
            // For now, let's update bypassing model events/casts if possible or just use raw DB to be safe.

            $config = $gateway->config; // This is array due to existing cast

            // We need to store it as encrypt(json_encode($config)) basically?
            // No, 'encrypted:array' cast expects the column to be text, and value to be encrypted string. 
            // When decrypted, it is JSON string, which then casts to array.

            if (is_array($config)) {
                // We use DB facade to avoid model casting interference during update
                $encryptedValue = Crypt::encryptString(json_encode($config));

                \DB::table('payment_gateways')
                    ->where('id', $gateway->id)
                    ->update(['config' => $encryptedValue]);
            }
        }

        // Also ensure the column type is large enough for encrypted string if not already
        Schema::table('payment_gateways', function (Blueprint $table) {
            $table->text('config')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $gateways = \DB::table('payment_gateways')->get();

        foreach ($gateways as $gateway) {
            try {
                $decryptedValue = Crypt::decryptString($gateway->config);
                // $decryptedValue is a JSON string.
                // We want to store it as plain JSON string or however it was before.
                // The verified previous state was 'config' cast to 'array'.
                // So in DB it was a JSON string or Text.

                \DB::table('payment_gateways')
                    ->where('id', $gateway->id)
                    ->update(['config' => $decryptedValue]);
            } catch (\Exception $e) {
                // Already decrypted or invalid
            }
        }
    }
};
