

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
            //var_dump($ent0ity);
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
            $persFamID = null;
            $fields = array();
            foreach ($entity as $field => $update) {
                if ($field === 'persfamid') {
                    $persFamID = $update['new'];
                }
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
            
            $persFamName = null;
            if ($persFamID) {
                $persFam = $diff['people'][$persFamID];
                $persFamName = join(' ', array(
                    $persFam['firstname']['new'],
                    $persFam['lastname']['new']
                ));
                if ($persFamName !== ' ') {
                    $name .= '<br />for ' . $persFamName;
                }
            }
            if (count($fields) == 1) {
                $vals = array_values($fields);
                if ($vals[0]['type'] == 'add' &&
                    $persFamName === ' ') {
                    continue;
                }
            }
        ?>
         <div class="container-fluid col-sm-12 table-responsive">
			<table class="table table-bordered"> 
                <thead>
                    <tr class="row">
                        <th class="theader">Name: <?php echo $name; ?></th>
                        <th class="theader">change</th>
                        <th class="theader">old value</th>
                        <th class="theader">new value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($fields as $field => $update):
                        ?>
                        <tr class="row">
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

                                <td class="tdback col-sm-3"><?php echo $field; ?></td>
                                <td class="col-sm-1"><?php echo $update['type']; ?></td>
                                <td class="col-sm-4"><?php echo $oldUpdate; ?></td>
                                <td class="col-sm-4"><?php echo $newUpdate; ?></td>
                        </tr>
                            <?php
                        endforeach;
                        ?>
                </tbody>
            </table>
		</div>
            <?php
        endforeach;
    endforeach;
endforeach;
?>
	