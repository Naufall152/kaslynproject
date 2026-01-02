<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Visitor;

echo '=== VISITOR DATA ANALYSIS ===' . PHP_EOL;
echo 'Total records: ' . Visitor::count() . PHP_EOL;
echo 'Unique sessions (30 days): ' . Visitor::getUniqueVisitorsBySession(30) . PHP_EOL;
echo 'Unique IPs (30 days): ' . Visitor::getUniqueVisitors(30) . PHP_EOL;
echo PHP_EOL;

$stats = Visitor::selectRaw('visit_date, COUNT(*) as count')
    ->where('visit_date', '>=', now()->subDays(30))
    ->groupBy('visit_date')
    ->orderBy('visit_date')
    ->get();

echo 'Daily breakdown (last 5 days):' . PHP_EOL;
$recent = $stats->take(-5);
foreach($recent as $stat) {
    echo $stat->visit_date . ': ' . $stat->count . ' visits' . PHP_EOL;
}

echo PHP_EOL . 'Total visits in last 30 days: ' . $stats->sum('count') . PHP_EOL;
echo 'Average daily visits: ' . round($stats->avg('count'), 1) . PHP_EOL;
