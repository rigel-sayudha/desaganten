<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Testing SuratKtp model...\n";
    
    // Test if we can access the model
    $model = new \App\Models\SuratKtp();
    echo "Model created successfully\n";
    
    // Test if we can query the table
    $count = \App\Models\SuratKtp::count();
    echo "Current records in surat_ktp table: " . $count . "\n";
    
    // Test fillable fields
    echo "Fillable fields: " . implode(', ', $model->getFillable()) . "\n";
    
    echo "Test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
