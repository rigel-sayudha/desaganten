<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Testing TidakMampu model...\n";
    
    // Test if we can access the model
    $model = new \App\Models\TidakMampu();
    echo "Model created successfully\n";
    
    // Test fillable fields
    echo "Fillable fields: " . implode(', ', $model->getFillable()) . "\n";
    
    // Test if we can query the table
    $count = \App\Models\TidakMampu::count();
    echo "Current records in tidak_mampu table: " . $count . "\n";
    
    echo "Test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
