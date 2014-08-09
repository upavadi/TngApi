<?php
require_once '../../../../wp-load.php';
header('Location: /thank-you');
//this the original
$date = date('c');
$email = esc_attr(get_option('tng-api-email'));
$msg = <<<MSG
Person Details Updated({$date}):

MSG;
$msg .= print_r($_REQUEST, true);
//echo "<pre>{$msg}</pre>";
mail($email, 'New data', $msg);
?>
<html>
<head>
</head>
<body>
</body>
</html>