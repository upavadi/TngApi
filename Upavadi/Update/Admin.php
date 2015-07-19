<?php

class Upavadi_Update_Admin
{

    /**
     *
     * @var wpdb
     */
    private $db;

    /**
     *
     * @var Upavadi_TngContent
     */
    private $content;

    public function __construct($db, Upavadi_TngContent $content)
    {
        $this->db = $db;
        $this->content = $content;
    }

    public function getSubmissionCount()
    {
        $table = $this->db->prefix . "tng_people";
        $sql = "SELECT count(*) FROM $table WHERE headpersonid = personid";
        $count = $this->db->get_var($sql);
        return $count;
    }

    public function getSubmissions()
    {
        $table = $this->db->prefix . "tng_people";
        $sql = "SELECT * FROM $table WHERE headpersonid = personid ORDER BY datemodified";
        return $this->db->get_results($sql, ARRAY_A);
    }

    public function initAdmin()
    {
        $count = $this->getSubmissionCount();
        $bubble = "<span class='update-plugins count-$count'><span class='update-count'>" . $count . "</span></span>";
        add_menu_page(
            "TNG Submissions", "TNG Submits " . $bubble, 'manage_options', 'tng_api_submissions', array($this, 'adminPage'), 'dashicons-media-spreadsheet'
        );
        add_submenu_page(
            'tng_api_submissions', "TNG Submissions", "Pending Submissions", 'manage_options', 'tng_api_submissions', array($this, 'adminPage')
        );
        add_submenu_page(
            'tng_api_submissions', "TNG Submissions", "View Submission", 'manage_options', 'tng_api_submission_view', array($this, 'viewPage')
        );
    }

    public function row($columns, $class = "", $tag = 'td')
    {
        echo "<tr>";
        foreach ($columns as $column) {
            echo "<$tag class='$class'>" . $column . "</$tag>";
        }
        echo "</tr>";
    }

    public function adminPage()
    {
        $repo = $this->content->getRepo();
        $submissions = $this->getSubmissions();
        echo "<table class='wp-list-table widefat'>";
        echo "<thead>";
        $this->row(array(
            'ID',
            'User',
            'Head Person',
            'Date',
            'Action'
            ), "manage_column", "th");
        echo "</thead>";
        echo "<tbody>";
        foreach ($submissions as $submission) {
            $person = $repo->getPerson($submission['personid']);
			$name = $person['firstname'] . ' ' . $person['lastname'];
            //var_dump($name);
			$viewUrl = admin_url('admin.php?page=tng_api_submission_view&id=' . $submission['id']);
            $this->row(array(
                $submission['id'],
                $submission['tnguser'],
                $name,
                $submission['datemodified'],
                "<a class='button' href='$viewUrl'>VIEW</a>"
            ));
        }
        echo "</tbody>";
        echo "</table>";
    }

    public function viewPage()
    {
        $id = intval(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS));
        if ($id) {
            $submission = $this->getSubmission($id);
        } else {
            $submissions = $this->getSubmissions();
            $submission = array_shift($submissions);
        }
        $changeSet = new Upavadi_Update_ChangeSet($this->content->getRepo(), $this->db, $submission);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processSubmission($changeSet);
        }
        ?>
        <div class="wrap">
            <h2>View Submission</h2>
            <div class="metabox-holder columns-2">
                <div>
                    <div class="postbox-container">
                        <div class="meta-box-sortables">
                            <form method="POST">
                                <?php
                               	$personId = $changeSet->getHeadPersonId();
                                $this->showPerson($changeSet, $personId, 'Person (' . $personId . ')');
                                $eventIds = $changeSet->getEventIds($personId);
                                foreach ($eventIds as $eventId) {
                                    $this->showEvent($changeSet, $eventId, 'Person (' . $personId . ') - Event - Accept ALL to Update');
                                }
                                $noteIds = $changeSet->getNoteIds($personId);
                                foreach ($noteIds as $noteId) {
                                    $this->showNotes($changeSet, $noteId, 'Person (' . $personId . ') - Notes');
                                }
                                $personId = $changeSet->getFatherId();
                                $this->showPerson($changeSet, $personId, 'Father (' . $personId . ')');
                                
                                $eventIds = $changeSet->getEventIds($personId);
                                foreach ($eventIds as $eventId) {
                                    $this->showEvent($changeSet, $eventId, 'Father (' . $personId . ') - Event - Accept ALL to Update');
                                }
                                
                                $personId = $changeSet->getMotherId();
                                $this->showPerson($changeSet, $personId, 'Mother (' . $personId . ')');
                                
                                $eventIds = $changeSet->getEventIds($personId);
                                foreach ($eventIds as $eventId) {
                                    $this->showEvent($changeSet, $eventId, 'Mother (' . $personId . ') - Event - Accept ALL to Update');
                                }
                                
                                $familyId = $changeSet->getHeadPersonFamC();
                                $this->showPersonFamily($changeSet, $familyId, 'Family (' . $familyId . ')');

                                $spouses = $changeSet->getSpouseIds();
                                foreach ($spouses as $index => $personId) {
                                    $this->showPerson($changeSet, $personId, 'Spouse ' . $index . ' (' . $personId . ')');
                                    $eventIds = $changeSet->getEventIds($personId);
                                    foreach ($eventIds as $eventId) {
                                        $this->showEvent($changeSet, $eventId, 'Spouse ' . $index . ' (' . $personId . ') - Event - Accept ALL to Update');
                                    }
                                    $noteIds = $changeSet->getNoteIds($personId);
                                    foreach ($noteIds as $noteId) {
                                        $this->showNotes($changeSet, $noteId, 'Spouse ' . $index . ' (' . $personId . ') - Notes');
                                    }
                                    $familyId = $changeSet->getSpouseFamilyId($personId);
                                    $this->showPersonFamily($changeSet, $familyId, 'Spouse ' . $index . ' - Family (' . $familyId . ')');
                                    $children = $changeSet->getChildrenIdsByParent($personId);
                                    foreach ($children as $cIndex => $childId) {
                                        $this->showPerson($changeSet, $childId, 'Spouse ' . $index . ' - Child ' . $cIndex . ' (' . $childId . ')');
                                        $this->showChildFamily($changeSet, $childId, 'Spouse ' . $index . ' - Child ' . $cIndex . ' (' . $childId . ') (cont)');
                                        $eventIds = $changeSet->getEventIds($childId);
                                        foreach ($eventIds as $eventId) {
                                            $this->showEvent($changeSet, $eventId, 'Spouse ' . $index . ' - Child ' . $cIndex . ' (' . $childId . ') - Event - Accept ALL to Update');
                                        }
                                    }
                                }
                                ?>
                                <div class="postbox ">
                                    <h2><span>Actions</span></h2>
                                    <div class="inside">
                                        <input class="button" type="submit" value="Save Accepted Changes" />
                                        <input class="button" name="discard" type="submit" value="Discard Submission" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            (function($) {
                function check(value) {
                    return function(e) {
                        var target = $(this).data('target');
                        $('.input_accept').filter(function(i, e) {
                            return target === $(e).data('target');
                        }).each(function(i, e) {
                            $(e).prop('checked', value);
                        });
                        e.preventDefault();
                        e.stopPropagation();
                    };
                }
                $(function() {
                    $('.accept_all').click(check(true));

                    $('.accept_none').click(check(false));
                });
            })(jQuery);
        </script>
        <?php
    }

    public function getSubmission($id)
    {
        $table = $this->db->prefix . "tng_people";
        $sql = "SELECT * FROM $table WHERE id = $id";
        $results = $this->db->get_results($sql, ARRAY_A);
        return array_shift($results);
    }

    public function showBox($title, $fields, $entity, $id)
    {
        $prefix = '[' . $entity . '][' . $id . ']';
        $targetName = preg_replace('/[\]\[]/', '_', $prefix);
        $all = '<a class="button accept_all" href="#" data-target="' . $targetName . '">all</a>';
        $none = '<a class="button accept_none" href="#" data-target="' . $targetName . '">none</a>';
        $allNone = $all . ' ' . $none;
        ?>
        <div class="postbox ">
            <h2><span> <?php echo $title; ?></span></h2>
            <div class="inside">
                <table class="form-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Original Value</th>
                            <th>New Value</th>
                            <th>Change</th>
                            <th>Accept<br /><?php echo $allNone; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($fields as $name => $data) {
                            $type = 'string';
                            $inputName = $prefix . '[' . $name . ']';
                            $idName = preg_replace('/[\]\[]/', '_', $inputName);
                            if (isset($data['type'])) {
                                $type = $data['type'];
                            }
                            $disabled = null;
                            if ($data['disabled']) {
                                $disabled = "readonly";
                            }
                            $change = null;
                            switch ($data['change']) {
                                case 'edit':
                                    $change = '<span class="dashicons dashicons-flag"></span>';
                                    break;
                                case 'add':
                                    $change = '<span class="dashicons dashicons-plus"></span>';
                                    break;
                            }
                            ?>
                            <tr>
                                <th scope="row"><?php echo $data['name']; ?></th>
                                <td><?php
                                    switch ($type) {
                                        case 'date':
                                        case 'enum':
                                        case 'string':
                                        case 'int':
                                        case 'text':
                                            echo $data['old'];
                                            break;
                                        case 'boolean';
                                            if ($data['old']) {
                                                echo '<span class="dashicons dashicons-yes"></span>';
                                            } else if ($data['old'] !== null) {
                                                echo '<span class="dashicons dashicons-no"></span>';
                                            }
                                            break;
                                    }
                                    ?></td>
                                <td><?php
                                    if ($data['change'] !== 'exclude') {
                                        switch ($type) {
                                            case 'text':
                                                ?><textarea <?php echo $disabled; ?> cols="40" rows="10" name="changes<?php echo $inputName; ?>"><?php echo $data['new'] ?></textarea><?php
                                                break;
                                            case 'int':
                                                $data['new'] = intval($data['new']);
                                            case 'date':
                                            case 'string':
                                                ?><input <?php echo $disabled; ?> type="text" size="40" name="changes<?php echo $inputName; ?>" value="<?php echo $data['new'] ?>"><?php
                                                break;
                                            case 'boolean';
                                                if ($data['new']) {
                                                    ?><input <?php echo $disabled; ?> type="checkbox" value="1" name="changes<?php echo $inputName; ?>" checked="checked"><?php
                                                } else {
                                                    ?><input <?php echo $disabled; ?> type="checkbox" value="1" name="changes<?php echo $inputName; ?>"><?php
                                                }
                                                break;
                                            case 'enum':
                                                ?>
                                                <select <?php echo $disabled; ?> name="changes<?php echo $inputName; ?>">
                                                    <?php
                                                    foreach ($data['values'] as $index => $value) {
                                                        $selected = null;
                                                        if (is_int($index)) {
                                                            $index = $value;
                                                        }
                                                        if ($index == $data['new']) {
                                                            $selected = "selected";
                                                        }
                                                        echo "<option value='$index' $selected>$value</option>";
                                                    }
                                                    ?>
                                                </select>
                                            <?php
                                        }
                                    }
                                    ?>

                                </td>
                                <td>
                                    <label for="accept<?php echo $idName; ?>"><?php echo $change; ?></label>

                                </td>
                                <td>
                                    <?php
                                    if ($data['change'] !== 'exclude') {
                                        ?>
                                        <input type="checkbox" class="input_accept" value="<?php echo $type; ?>" data-target="<?php echo $targetName; ?>" id="accept<?php echo $idName; ?>" name="accept<?php echo $inputName; ?>">
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    public function showPerson(Upavadi_Update_ChangeSet $changeSet, $personId, $title)
    {
        if (!$personId) {
            //echo "<p class='error'>Could not find $title</p>";
            return;
        }
        $changes = $changeSet->getChangesFor('people', $personId);
        $names = array(
            'firstname' => array('name' => 'First Name'),
            'lastname' => array('name' => 'Last Name'),
            'famc' => array('name' => 'Family', 'disabled' => true),
            'birthdate' => array('name' => 'Born', 'type' => 'date'),
            'birthplace' => array('name' => 'Place of Birth'),
            'deathdate' => array('name' => 'Died', 'type' => 'date'),
            'deathplace' => array('name' => 'Place of Death'),
            'living' => array('name' => 'Living', 'type' => 'boolean'),
            'sex' => array('name' => 'Sex', 'type' => 'enum', 'values' => array('', 'M', 'F')),
        );

        $this->showChanges($changes, $names, $title, 'people', $personId);
    }

    public function showPersonFamily($changeSet, $familyId, $title)
    {
        if (!$familyId) {
            //echo "<p class='error'>Could not find $title</p>";
            return;
        }
        $changes = $changeSet->getChangesFor('family', $familyId);
        $names = array(
            'husband' => array('name' => 'Husband', 'disabled' => true),
            'wife' => array('name' => 'Wife', 'disabled' => true),
            'marrdate' => array('name' => 'Marriage Date', 'type' => 'date'),
            'marrplace' => array('name' => 'Marriage Place'),
            'husborder' => array('name' => 'Husband Order'),
            'wifeorder' => array('name' => 'Wife Order'),
        );
        $this->showChanges($changes, $names, $title, 'family', $familyId);
    }

    public function showChanges($changes, $names, $title, $entity, $id)
    {
        $fields = array();
        foreach ($names as $field => $data) {
            $change = null;
            if (isset($changes['diff'][$field])) {
                $change = $changes['diff'][$field]['type'];
            }
            $fields[$field] = array_merge($data, array(
                'old' => $changes['old'][$field],
                'new' => $changes['new'][$field],
                'change' => $change,
            ));
        }
        $this->showBox($title, $fields, $entity, $id);
    }

    public function showChildFamily($changeSet, $childId, $title)
    {
        if (!$childId) {
            echo "<p class='error'>Could not find $title</p>";
            return;
        }
        $changes = $changeSet->getChangesFor('children', $childId);
        $names = array(
            'personid' => array('name' => 'Child', 'disabled' => true),
            'familyid' => array('name' => 'Family', 'disabled' => true),
            'ordernum' => array('name' => 'Child Order'),
            'haskids' => array('name' => 'Has Kids', 'type' => 'boolean'),
            'parentorder' => array('name' => 'Parent Order'),
        );
        $this->showChanges($changes, $names, $title, 'children', $childId);
    }

    public function processSubmission(Upavadi_Update_ChangeSet $changeSet)
    {
//        echo "<pre>";
//        print_r($changeSet->getDiff());
//        echo "</pre>";
        $post = filter_input_array(INPUT_POST);
        if (isset($post['discard'])) {
            $changeSet->discard();
            $url = admin_url('admin.php?page=tng_api_submissions');
            if (!headers_sent()) {
                header('Location: ' . $url);
            } else {
            ?>
<script>
window.location.assign("<?php echo $url; ?>");
</script>
        <?php
            }
            exit;
        }
        if (!isset($post['accept'])) {
            return;
        }
//        print_r($post);
//        echo "<pre>";
        $builder = new Upavadi_Update_Builder($changeSet);
        $updates = $builder->build($post['accept'], $post['changes']);
//        print_r($updates);
//        exit;
        $changeSet->apply($updates);
//        echo "</pre>";
        $url = admin_url('admin.php?page=tng_api_submission_view&id=' . $changeSet->getId());
        if (!headers_sent()) {
                header('Location: ' . $url);
        } else {
        ?>
<script>
window.location.assign("<?php echo $url; ?>");
</script>
    <?php
        }
        exit;
    }

    public function showNotes(Upavadi_Update_ChangeSet $changeSet, $noteId, $title)
    {
        $changes = $changeSet->getChangesFor('notes', $noteId);
        $names = array(
            'eventid' => array('name' => 'Event ID', 'disabled' => true),
            'persfamid' => array('name' => 'Person ID', 'disabled' => true),
            'note' => array('name' => 'Note', 'type' => 'text'),
            'secret' => array('name' => 'Secret?', 'type' => 'boolean'),
        );

        switch ($changes['new']['eventid']) {
            case '':
                $title .= ' General';
                break;
            case 'BIRT':
                $title .= ' Birth';
                break;
            case 'NAME':
                $title .= ' Name';
                break;
            case 'DEAT':
                $title .= ' Death';
                break;
            case 'BURI':
                $title .= ' Funeral';
                break;
            case 'BIRT':
                $title .= ' Birth';
                break;
        }
        $this->showChanges($changes, $names, $title. " - Accept ALL To Update ", 'notes', $noteId);
    }

    public function showEvent($changeSet, $eventId, $title)
    {
        $changes = $changeSet->getChangesFor('events', $eventId);
        $eventTypeId = intval($changes['new']['eventtypeid']);
        $events = $this->content->getEventList();
        $eventEnum = array(
            0 => 'Cause of death'
        );
        foreach ($events as $event) {
            $eventEnum[intval($event['eventtypeID'])] = $event['display'];
        }
        
        $field = 'cause';
        $fieldName = 'Cause of Death';
        if ($eventTypeId > 0) {
            $field = 'info';
            $fieldName = $eventEnum[$eventTypeId];
        }
        $names = array(
            $field => array('name' => $fieldName),
            'parenttag' => array('name' => 'Parent Tag', 'disabled' => true),
            'persfamid' => array('name' => 'Person ID', 'disabled' => true),
            'eventtypeid' => array('name' => 'Event Type ID', 'type' => 'int', 'disabled' => true),
        );
        $this->showChanges($changes, $names, $title, 'events', $eventId);
    }

}
