<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Testing BelumMenikah model...\n";
    
    // Test if we can access the model
    $model = new \App\Models\BelumMenikah();
    echo "Model created successfully\n";
    
    // Test fillable fields
    echo "Fillable fields: " . implode(', ', $model->getFillable()) . "\n";
    
    // Test if we can query the table
    $count = \App\Models\BelumMenikah::count();
    echo "Current records in belum_menikah table: " . $count . "\n";
    
    echo "Test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
