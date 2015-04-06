<?php
$numChanges = count($changeSets);
foreach ($changeSets as $index => $change):
    /* @var Upavadi_Update_ChangeSet $change  */
    ?>
    <br/>
    <strong>Changes for the Family of <?php echo $new_firstname . " " . $new_lastname; ?>
        *** Submission <?php echo ($numChanges - $index) . "  of " . $numChanges; ?>
    </strong>
    <?php
    $diff = $change->getDiff();
    foreach ($diff as $entityName => $entities):
        ?>
        <h2><?php echo ucfirst($entityName); ?></h2>
        <?php
        foreach ($entities as $id => $entity):
            $empty = true;
            foreach ($entity as $field => $update):
                if ($update['type'] != 'exclude') {
                    $empty = false;
                    break;
                }
            endforeach;
            if ($empty) {
                continue;
            }
            ?>
            <table width="100%">
                <thead>
                    <tr>
                        <th width="15%">ID: <?php echo $id; ?></th>
                        <th width="10%">change</th>
                        <th width="40%">old value</th>
                        <th width="40%">new value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($entity as $field => $update):
                        if ($update['type'] == 'exclude') {
                            continue;
                        }
                        if (trim($update['old']) == trim($update['new'])) {
                            continue;
                        }
                        ?>
                        <tr>
                            <td><?php echo $field; ?></td>
                            <td><?php echo $update['type']; ?></td>
                            <td><?php echo $update['old']; ?></td>
                            <td><?php echo $update['new']; ?></td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
            <?php
        endforeach;
    endforeach;
endforeach;
