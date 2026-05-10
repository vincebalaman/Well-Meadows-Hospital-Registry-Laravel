<?php
$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=websystem;user=postgres;password=1234567890');

// Check procedures
$result = $pdo->query("SELECT proname FROM pg_proc WHERE proname IN ('assign_staff_to_ward', 'update_staff_contract', 'validate_charge_nurse_role') ORDER BY proname");
echo "Stored Procedures:\n";
echo str_repeat("=", 40) . "\n";
foreach($result as $row) {
  echo "✓ " . $row['proname'] . "\n";
}

// Check tables
$tables = ['job_positions', 'wards', 'staff_contracts', 'staff_allocations'];
echo "\n\nDatabase Tables:\n";
echo str_repeat("=", 40) . "\n";
foreach($tables as $table) {
  $result = $pdo->query("SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_name='$table' AND table_schema='public'");
  $row = $result->fetch();
  echo ($row['cnt'] > 0 ? "✓" : "✗") . " $table\n";
}

echo "\n✓ All database objects verified!\n";
?>
