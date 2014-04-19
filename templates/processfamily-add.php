<?php
header('Location: /thank-you');
//this the original
$date = date('c');
$msg = <<<MSG
New Person Added ({$date}):

MSG;
$msg .= print_r($_REQUEST, true);
//echo "<pre>{$msg}</pre>";
mail('mahesh@upavadi.net', 'New data', $msg);
?>
<html>
<head>
</head>
<body>
</body>
</html>