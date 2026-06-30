<?php
$migrationsDir = __DIR__ . '/database/migrations';
$modelsDir = __DIR__ . '/app/Models';

echo "Mengubah file Migrations...\n";
$migrations = glob($migrationsDir . '/*.php');
foreach ($migrations as $file) {
    $content = file_get_contents($file);
    $original = $content;
    
    // Replace standard $table->id()
    $content = str_replace('$table->id();', '$table->uuid(\'id\')->primary();', $content);
    
    // Replace $table->foreignId('...')->...
    $content = preg_replace('/\$table->foreignId\((.*?)\)/', '$table->foreignUuid($1)', $content);
    
    // Special handling for permission tables
    if (strpos($file, 'create_permission_tables.php') !== false) {
        $content = str_replace('$table->bigIncrements(\'id\');', '$table->uuid(\'id\')->primary();', $content);
        $content = str_replace('$table->unsignedBigInteger($columnNames[\'model_morph_key\']);', '$table->uuid($columnNames[\'model_morph_key\']);', $content);
        $content = str_replace('$table->unsignedBigInteger($pivotPermission);', '$table->uuid($pivotPermission);', $content);
        $content = str_replace('$table->unsignedBigInteger($pivotRole);', '$table->uuid($pivotRole);', $content);
        $content = str_replace('$table->unsignedBigInteger($columnNames[\'team_foreign_key\'])', '$table->uuid($columnNames[\'team_foreign_key\'])', $content);
    }
    
    if ($original !== $content) {
        file_put_contents($file, $content);
        echo " - Updated: " . basename($file) . "\n";
    }
}

echo "Mengubah file Models...\n";
$models = glob($modelsDir . '/*.php');
foreach ($models as $file) {
    $content = file_get_contents($file);
    $original = $content;
    
    // Check if it already has HasUuids to avoid duplicates
    if (strpos($content, 'use HasUuids;') === false) {
        // 1. Add 'use Illuminate\Database\Eloquent\Concerns\HasUuids;' at the top
        $content = preg_replace('/namespace App\\\\Models;/', "namespace App\\Models;\n\nuse Illuminate\Database\Eloquent\Concerns\HasUuids;", $content);
        
        // 2. Add 'use HasUuids;' inside the class declaration
        $content = preg_replace('/(class\s+[a-zA-Z0-9_]+\s+extends\s+[a-zA-Z0-9_\\\\]+[^\{]*\{)/', "$1\n    use HasUuids;", $content, 1);
    }
    
    if ($original !== $content) {
        file_put_contents($file, $content);
        echo " - Updated: " . basename($file) . "\n";
    }
}

echo "Berhasil!\n";
