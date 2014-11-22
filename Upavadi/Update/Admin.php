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
        ?>
        <div class="wrap">
            <h2>View Submission</h2>
            <div class="metabox-holder columns-2">
                <div>
                    <div class="postbox-container">
                        <div class="meta-box-sortables">
                            <?php
                            $personId = $changeSet->getHeadPersonId();
                            $this->showPerson($changeSet, $personId, 'Person');
                            $personId = $changeSet->getFatherId();

                            $this->showPerson($changeSet, $personId, 'Father');
                            $personId = $changeSet->getMotherId();
                            $this->showPerson($changeSet, $personId, 'Mother');
                            
                            $familyId = $changeSet->getHeadPersonFamC();
                            $this->showPersonFamily($changeSet, $familyId, 'Family');
                            
                            $spouses = $changeSet->getSpouseIds();
                            foreach ($spouses as $index => $personId) {
                                $this->showPerson($changeSet, $personId, 'Spouse ' . $index);
                                $familyId = $changeSet->getSpouseFamilyId($personId);
                                $this->showPersonFamily($changeSet, $familyId, 'Spouse ' . $index . ' - Family');
                                $children = $changeSet->getChildrenIdsByParent($personId);
                                foreach ($children as $cIndex => $childId) {
                                    $this->showPerson($changeSet, $childId, 'Spouse ' . $index . ' - Child ' . $cIndex);
                                    $this->showChildFamily($changeSet, $childId, 'Spouse ' . $index . ' - Child ' . $cIndex . ' (cont)');
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function getSubmission($id)
    {
        $table = $this->db->prefix . "tng_people";
        $sql = "SELECT * FROM $table WHERE id = $id";
        $results = $this->db->get_results($sql, ARRAY_A);
        return array_shift($results);
    }

    public function showBox($title, $fields)
    {
        ?>
        <div class="postbox ">
            <h3 class="hndle"><span><?php echo $title; ?></span></h3>
            <div class="inside">
                <table class="form-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Original Value</th>
                            <th>New Value</th>
                            <th>Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($fields as $name => $data) {
                            $type = 'string';
                            if (isset($data['type'])) {
                                $type = $data['type'];
                            }
                            $change = null;
                            switch ($data['change']) {
                                case 'edit':
                                    $change = '<span class="dashicons dashicons-flag"></span>';
                                    break;
                            }
                            ?>
                            <tr>
                                <th scope="row"><?php echo $data['name']; ?></th>
                                <td><?php
                                    switch ($type) {
                                        case 'enum':
                                        case 'string':
                                            echo $data['old'];
                                            break;
                                        case 'boolean';
                                            if ($data['old']) {
                                                echo '<span class="dashicons dashicons-yes"></span>';
                                            } else {
                                                echo '<span class="dashicons dashicons-no"></span>';
                                            }
                                            break;
                                    }
                                    ?></td>
                                <td><?php
                                    switch ($type) {
                                        case 'string':
                                            ?><input type="text" size="80" name="<?php echo $name; ?>" value="<?php echo $data['new'] ?>"><?php
                                            break;
                                        case 'boolean';
                                            if ($data['old']) {
                                                ?><input type="checkbox" name="<?php echo $name; ?>" checked="checked"><?php
                                            } else {
                                                ?><input type="checkbox" name="<?php echo $name; ?>"><?php
                                            }
                                            break;
                                        case 'enum':
                                            ?>
                                            <select name="<?php echo $name; ?>">
                                                <?php
                                                foreach ($data['values'] as $value) {
                                                    $selected = null;
                                                    if ($value == $data['new']) {
                                                        $selected = "selected";
                                                    }
                                                    echo "<option value='$value' $selected>$value</option>";
                                                }
                                                ?>
                                            </select>
                                        <?php
                                    }
                                    ?>

                                </td>
                                <td><?php echo $change; ?></td>
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
            echo "<p class='error'>Could not find $title</p>";
            return;
        }
        $changes = $changeSet->getChangesFor('people', $personId);
        $names = array(
            'firstname' => array('name' => 'First Name'),
            'lastname' => array('name' => 'Last Name'),
            'birthdate' => array('name' => 'Born'),
            'birthplace' => array('name' => 'Place of Birth'),
            'deathdate' => array('name' => 'Died'),
            'deathplace' => array('name' => 'Place of Death'),
            'living' => array('name' => 'Living', 'type' => 'boolean'),
            'sex' => array('name' => 'Sex', 'type' => 'enum', 'values' => array('', 'M', 'F')),
        );
        
        $this->showChanges($changes, $names, $title);
    }

    
    public function showPersonFamily($changeSet, $familyId, $title)
    {
        if (!$familyId) {
            echo "<p class='error'>Could not find $title</p>";
            return;
        }
        $changes = $changeSet->getChangesFor('family', $familyId);
        $names = array(
           'marrdate' => array('name' => 'Marriage Date'),
           'marrplace' => array('name' => 'Marriage Place'),
           'husborder' => array('name' => 'Husband Order'),
           'wifeorder' => array('name' => 'Wife Order'),
        ); 
        $this->showChanges($changes, $names, $title);
    }

    public function showChanges($changes, $names, $title)
    {
        $fields = array();
        foreach ($names as $field => $data) {
            $change = null;
            if (isset($changes['diff'][$field])) {
                $change = $changes['diff'][$field]['type'];
            }
            $fields[] = array_merge($data, array(
                'old' => $changes['old'][$field],
                'new' => $changes['new'][$field],
                'change' => $change,
            ));
        }
        $this->showBox($title, $fields);
    }

    public function showChildFamily($changeSet, $childId, $title)
    {
        if (!$childId) {
            echo "<p class='error'>Could not find $title</p>";
            return;
        }
        $changes = $changeSet->getChangesFor('children', $childId);
        $names = array(
           'ordernum' => array('name' => 'Child Order'),
           'haskids' => array('name' => 'Has Kids', 'type' => 'boolean'),
           'parentorder' => array('name' => 'Parent Order'),
        ); 
        $this->showChanges($changes, $names, $title);
    }

}