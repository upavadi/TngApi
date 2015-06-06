<?php
$tngcontent = Upavadi_TngContent::instance();
$numChanges = count($changeSets);
if ($numChanges == 0) {
    echo "<br /> There are no Pending Submissions";
}
$excludeFields = array(
    'gedcom',
    'personid', 
    'famc', 
    'familyid', 
    'wife', 
    'husband', 
    'husborder', 
    'wifeorder', 
    'xnoteid', 
    'ordernum', 
    'persfamid', 
    'eventid', 
    'eventtypeid', 
    'eventdatetr', 
    'living', 
    'parentorder', 
    'parenttag'
);
foreach ($changeSets as $index => $change):
    /* @var Upavadi_Update_ChangeSet $change  */
    $tree = $change->getHeadPersonTree();
    $headPerson = $tngcontent->getPerson(
        $change->getHeadPersonId(),
        $tree
    );
    ?>
    <br/>
    <strong>Changes for the Family of <?php echo $headPerson['firstname'] . " " . $headPerson['lastname']; ?>
        <br>Submission <?php echo ($numChanges - $index) . "  of " . $numChanges; ?>
        id <?php echo $change->getId(); ?>
    </strong>
    <?php
    $diff = $change->getDiff();
    //var_dump($changeSets);
    foreach ($diff as $entityName => $entities):
        ?>
        <h2><?php echo ucfirst($entityName); ?></h2>
        <?php
        // var_dump($diff);
        foreach ($entities as $id => $entity):
            //var_dump($entity);
            $empty = true;
            foreach ($entity as $field => $update):
                if ($update['type'] != 'exclude') {
                    $empty = false;
                    break;
                }
                //var_dump($entity);
            endforeach;
            if ($empty) {
                continue;
            }
            $person = $tngcontent->getPerson($id, $tree);
            $name = $person['firstname'] . " " . $person['lastname'];
            //var_dump($name);
            if ($name == " ") {
                $name = "New Ref: " . $id;
            }
            $fields = array();
            foreach ($entity as $field => $update) {
                if ($update['type'] == 'exclude') {
                    continue;
                }
                if (trim($update['old']) == trim($update['new'])) {
                    continue;
                }
                
                if (in_array($field, $excludeFields)) {
                    continue;
                }
                $fields[$field] = $update;
            }
            if (!count($fields)) {
                continue;
            }
            ?>
            <table width="100%" class="form-table">
                <thead>
                    <tr>
                        <th class="theader" width="20%">Name: <?php echo $name; ?></th>
                        <th class="theader" width="10%">change</th>
                        <th class="theader" width="40%">old value</th>
                        <th class="theader" width="40%">new value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($fields as $field => $update):
                        ?>
                        <tr>
                            <?php
                            $oldUpdate = $update['old'];
                            $newUpdate = $update['new'];
                                if ($field == 'husband') {
                                    $newhusbId = $update['new'];
                                    if ($newhusbId !== '') {
                                        $newhusb = $tngcontent->getPerson($newhusbId, $tree);
                                    }
                                    if ($newhusb != '') {
                                        $newUpdate = $newhusb['firstname'] . " " . $newhusb['lastname'];
                                    }
                                    $oldhusbId = $update['old'];
                                    if ($oldhusbId != '') {
                                        $oldhusb = $tngcontent->getPerson($oldhusbId, $tree);
                                    }

                                    if ($oldhusb !== '') {
                                        $oldUpdate = $oldhusb['firstname'] . " " . $oldhusb['lastname'];
                                    } else {
                                        $oldUpdate = "";
                                    }
                                }
                                if ($field == 'wife') {
                                    $newwifebId = $update['new'];
                                    $newwife = $tngcontent->getPerson($newwifebId, $tree);
                                    if ($newwife != '') {
                                        $newUpdate = $newwife['firstname'] . " " . $newwife['lastname'];
                                    }
                                    if ($newwife != '') {
                                        $newUpdate = $newwife['firstname'] . " " . $newwife['lastname'];
                                    }
                                    $oldwifeId = $update['old'];
                                    if ($oldwifebId != '') {
                                        $oldwife = $tngcontent->getPerson($oldwifeId, $tree);
                                        print_r($update['old']);
                                    }

                                    if ($oldwife !== '') {
                                        $oldUpdate = $oldwife['firstname'] . " " . $oldwife['lastname'];
                                    } else {
                                        $oldUpdate = "";
                                    }
                                }
                                ?> 

                                <td class="tdback"><?php echo $field; ?></td>
                                <td><?php echo $update['type']; ?></td>
                                <td><?php echo $oldUpdate; ?></td>
                                <td><?php echo $newUpdate; ?></td>
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
