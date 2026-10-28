<?php

const SITENAME = 'silkroute';

$productName = 'banana sweets';
$productPrice = 20;
$productLength = 100;
$currency = 'MDL';
$inStock = true;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
</head>

<body>
    <h1>
        <?php echo $productName; ?>
    </h1>
    <h4>
        Price: <?php print $productPrice . " " . $currency ?>
    </h4>
    <h4>
        Length: <?php echo $productLength; ?>
    </h4>
    <i>
        <?php echo $inStock ? 'In stock' : 'Not in stock'; ?>
    </i>
</body>

</html>