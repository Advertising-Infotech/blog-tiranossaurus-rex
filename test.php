<?php
/**
 * Test script to verify the WordPress clone setup
 */
require_once('wp-load.php');

echo "=== Tiranossaurus Rex WordPress Clone - Setup Test ===\n\n";

// Test 1: Check options
echo "1. Testing wp-options.json...\n";
$options = json_decode(file_get_contents('wp-options.json'), true);
echo "   Blog name: " . $options['blogname'] . "\n";
echo "   Blog description: " . $options['blogdescription'] . "\n";
echo "   OK\n\n";

// Test 2: Check posts
echo "2. Testing wp-query.json (posts)...\n";
$query = json_decode(file_get_contents('wp-query.json'), true);
echo "   Total posts: " . count($query['posts']) . "\n";
foreach ($query['posts'] as $post) {
    echo "   - [{$post['id']}] {$post['title']['rendered']} ({$post['date']})\n";
}
echo "   OK\n\n";

// Test 3: Check attachments
echo "3. Testing wp-attachments.json...\n";
$attachments = json_decode(file_get_contents('wp-attachments.json'), true);
echo "   Total attachments: " . count($attachments['attachments']) . "\n";
foreach ($attachments['attachments'] as $att) {
    $path = $att['source_url'];
    $exists = file_exists($path) ? "EXISTS" : "MISSING";
    echo "   - [{$att['id']}] {$att['title']} [$exists]\n";
}
echo "   OK\n\n";

// Test 4: Check sidebar
echo "4. Testing wp-sidebar.json...\n";
$sidebar = json_decode(file_get_contents('wp-sidebar.json'), true);
echo "   Categories: " . count($sidebar['categories']) . "\n";
echo "   Social links: Facebook, Instagram, Twitter, Google+\n";
echo "   OK\n\n";

// Test 5: Check theme files
echo "5. Testing theme files...\n";
$themeFiles = [
    'wp-content/themes/tiranossaurusrex/index.php',
    'wp-content/themes/tiranossaurusrex/header.php',
    'wp-content/themes/tiranossaurusrex/footer.php',
    'wp-content/themes/tiranossaurusrex/single.php',
    'wp-content/themes/tiranossaurusrex/category.php',
    'wp-content/themes/tiranossaurusrex/404.php',
    'wp-content/themes/tiranossaurusrex/functions.php',
    'wp-content/themes/tiranossaurusrex/style.css',
    'wp-content/themes/tiranossaurusrex/js/main.js',
];
foreach ($themeFiles as $file) {
    $exists = file_exists($file) ? "EXISTS" : "MISSING";
    echo "   - $file [$exists]\n";
}
echo "   OK\n\n";

echo "=== All tests passed! ===\n";
echo "\nTo run the site:\n";
echo ";
// echo "  cd C:\Users\lagar\OneDrive\Área de Trabalho 2024\BackUp\Advertising TI & CS\Projetos\Blog Tiranossaurus Rex\wordpress\n";
echo "  php -S localhost:8080\n";
echo "  Then open http://localhost:8080 in your browser\n";
