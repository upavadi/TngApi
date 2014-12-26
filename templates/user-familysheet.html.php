<?php
$numChanges = count($changeSets);
foreach ($changeSets as $index => $change):
    /* @var Upavadi_Update_ChangeSet $change  */
?>
<br/>
<strong>Changes for the Family of <?php echo $new_firstname . " " . $new_lastname; ?>
    *** Submission <?php echo ($numChanges - $index) . "  of " . $numChanges; ?>
    <pre>
        <?php
        echo $change->getKey() . PHP_EOL;
        print_r($change->getDiff());
        //var_dump($change);
		?>
    </pre>
</strong>
<?php
endforeach;
