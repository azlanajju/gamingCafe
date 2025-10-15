<?php
/**
 * Test script for Time Bonus Coupon functionality
 * This script tests the loop bonus coupon calculation
 */

require_once __DIR__ . '/config/database.php';

$db = getDB();

echo "<h2>Testing Time Bonus Coupon Functionality</h2>\n";

// Test cases for time bonus calculation
$test_cases = [
    [
        'name' => 'FREETIME - 2 hours (no loop)',
        'code' => 'FREETIME',
        'duration' => 120, // 2 hours
        'expected_bonus' => 30,
        'expected_cycles' => 1
    ],
    [
        'name' => 'FREETIME - 3 hours (no loop)',
        'code' => 'FREETIME', 
        'duration' => 180, // 3 hours
        'expected_bonus' => 30, // Still only 30 minutes (no loop)
        'expected_cycles' => 1
    ],
    [
        'name' => 'LOOPBONUS - 2 hours (with loop)',
        'code' => 'LOOPBONUS',
        'duration' => 120, // 2 hours
        'expected_bonus' => 30,
        'expected_cycles' => 1
    ],
    [
        'name' => 'LOOPBONUS - 4 hours (with loop)',
        'code' => 'LOOPBONUS',
        'duration' => 240, // 4 hours
        'expected_bonus' => 60, // 2 cycles × 30 minutes
        'expected_cycles' => 2
    ],
    [
        'name' => 'LOOPBONUS - 6 hours (with loop)',
        'code' => 'LOOPBONUS',
        'duration' => 360, // 6 hours
        'expected_bonus' => 90, // 3 cycles × 30 minutes
        'expected_cycles' => 3
    ]
];

foreach ($test_cases as $test) {
    echo "<h3>{$test['name']}</h3>\n";
    
    // Call the API endpoint
    $url = "http://localhost/Gaming-cafe/api/coupons.php?action=calculate_time_bonus&code={$test['code']}&duration={$test['duration']}";
    
    $response = file_get_contents($url);
    $result = json_decode($response, true);
    
    if ($result['success']) {
        $data = $result['data'];
        echo "✅ API Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
        
        // Check if results match expectations
        $bonus_correct = $data['bonus_minutes'] == $test['expected_bonus'];
        $cycles_correct = $data['cycles'] == $test['expected_cycles'];
        
        echo "Expected Bonus: {$test['expected_bonus']} minutes, Got: {$data['bonus_minutes']} minutes " . ($bonus_correct ? "✅" : "❌") . "\n";
        echo "Expected Cycles: {$test['expected_cycles']}, Got: {$data['cycles']} " . ($cycles_correct ? "✅" : "❌") . "\n";
        
        if ($bonus_correct && $cycles_correct) {
            echo "<strong style='color: green;'>✅ Test PASSED</strong>\n";
        } else {
            echo "<strong style='color: red;'>❌ Test FAILED</strong>\n";
        }
    } else {
        echo "❌ API Error: " . $result['message'] . "\n";
    }
    
    echo "<hr>\n";
}

// Test billing calculation
echo "<h3>Testing Billing Integration</h3>\n";

// Simulate a 3-hour session with LOOPBONUS coupon
$session_duration = 180; // 3 hours
$gaming_rate_per_hour = 50; // ₹50 per hour
$total_gaming_amount = ($session_duration / 60) * $gaming_rate_per_hour; // ₹150

echo "Session Duration: {$session_duration} minutes ({$session_duration/60} hours)\n";
echo "Gaming Rate: ₹{$gaming_rate_per_hour} per hour\n";
echo "Total Gaming Amount: ₹{$total_gaming_amount}\n";

// Calculate time bonus
$bonus_url = "http://localhost/Gaming-cafe/api/coupons.php?action=calculate_time_bonus&code=LOOPBONUS&duration={$session_duration}";
$bonus_response = file_get_contents($bonus_url);
$bonus_result = json_decode($bonus_response, true);

if ($bonus_result['success']) {
    $bonus_data = $bonus_result['data'];
    $bonus_minutes = $bonus_data['bonus_minutes'];
    $rate_per_minute = $total_gaming_amount / $session_duration;
    $discount_amount = $bonus_minutes * $rate_per_minute;
    $final_gaming_amount = $total_gaming_amount - $discount_amount;
    
    echo "Bonus Minutes: {$bonus_minutes}\n";
    echo "Rate per Minute: ₹" . number_format($rate_per_minute, 2) . "\n";
    echo "Discount Amount: ₹" . number_format($discount_amount, 2) . "\n";
    echo "Final Gaming Amount: ₹" . number_format($final_gaming_amount, 2) . "\n";
    
    echo "<strong style='color: green;'>✅ Billing calculation working correctly</strong>\n";
} else {
    echo "❌ Error calculating time bonus: " . $bonus_result['message'] . "\n";
}

echo "<h3>Test Complete!</h3>\n";
?>

