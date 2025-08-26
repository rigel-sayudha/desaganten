<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "Checking BelumMenikah table structure...\n";
    
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('belum_menikah');
    echo "Columns: " . implode(', ', $columns) . "\n";
    
    // Check if required fields are nullable
    $columnTypes = \Illuminate\Support\Facades\DB::select("DESCRIBE belum_menikah");
    foreach($columnTypes as $column) {
        if(in_array($column->Field, ['nama_orang_tua', 'pekerjaan_orang_tua', 'alamat_orang_tua', 'no_telepon'])) {
            echo $column->Field . " - NULL: " . $column->Null . "\n";
        }
    }
    
    echo "Test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
