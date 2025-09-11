<?php
$file = 'c:\\xampp\\htdocs\\app_toppdv\\app\\Views\\vendas.php';
$s = file_get_contents($file);
if ($s === false) { echo "READ_ERROR\n"; exit(1); }
// extract first script block
if (!preg_match('%<script[^>]*>([\s\S]*?)</script>%i', $s, $m)) { echo "NO_SCRIPT_BLOCK\n"; exit(0); }
$script = $m[1];
echo "SCRIPT_LENGTH: " . strlen($script) . "\n";
$counts = ['('=>0, ')'=>0, '{'=>0, '}'=>0, '['=>0, ']'=>0, "'"=>0, '"'=>0, '`'=>0];
$len = strlen($script);
for ($i=0;$i<$len;$i++) {
    $ch = $script[$i];
    if (isset($counts[$ch])) $counts[$ch]++;
}
foreach ($counts as $k=>$v) echo "$k => $v\n";
// quick parity checks
$parens = $counts['('] - $counts[')'];
$braces = $counts['{'] - $counts['}'];
$brackets = $counts['['] - $counts[']'];
$single = $counts["'"] % 2;
$double = $counts['"'] % 2;
$back = $counts['`'] % 2;
echo "PARITY paren:$parens brace:$braces bracket:$brackets single_unpaired:$single double_unpaired:$double backtick_unpaired:$back\n";
// print last 40 lines
$lines = preg_split('/\r?\n/', $script);
$tail = array_slice($lines, -40);
echo "---LAST 40 LINES OF SCRIPT---\n";
foreach ($tail as $ln) echo $ln."\n";
// print post-script content
$post = substr($s, $m[0] ? strpos($s, $m[0]) + strlen($m[0]) : strlen($s));
$posttrim = trim($post);
if ($posttrim !== '') {
    echo "AFTER_SCRIPT_NONEMPTY\n";
    echo "TAIL: " . substr($posttrim,0,200) . "\n";
} else {
    echo "AFTER_SCRIPT_EMPTY\n";
}
?>
