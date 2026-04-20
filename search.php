<?php
header("Content-Type: application/json; charset=UTF-8");

$MAX_RESULTS = 20;

function normalizeArabic($text) {
    $text = mb_strtolower($text, 'UTF-8');

    $search  = ['أ','إ','آ','ٱ','ة','ى'];
    $replace = ['ا','ا','ا','ا','ه','ي'];

    $text = str_replace($search, $replace, $text);

    // حذف التشكيل
    $text = preg_replace('/[\x{064B}-\x{065F}]/u', '', $text);

    // حذف المسافات
    $text = preg_replace('/\s+/', '', $text);

    return $text;
}

$query = $_GET['q'] ?? '';
$query = trim($query);

if (!$query) {
    echo json_encode(["results" => []]);
    exit;
}

$queryNorm = normalizeArabic($query);

$data = json_decode(file_get_contents("students.json"), true);

$results = [];
$count = 0;

$queryRaw = $query; // بدون تطبيع
$queryNorm = normalizeArabic($query);

$isNumberSearch = preg_match('/^\d+$/', $queryRaw);

foreach ($data as $number => $student) {

    if ($count >= $MAX_RESULTS) break;

    if ($isNumberSearch) {
        if (strpos($number, $queryRaw) !== false) {
            $results[] = [
                "number" => $number,
                "name" => $student['name'],
                "courses" => $student['courses']
            ];
            $count++;
        }
    } else {
        $nameNorm = normalizeArabic($student['name']);

        if (strpos($nameNorm, $queryNorm) !== false) {
            $results[] = [
                "number" => $number,
                "name" => $student['name'],
                "courses" => $student['courses']
            ];
            $count++;
        }
    }
}

echo json_encode(["results" => $results], JSON_UNESCAPED_UNICODE);