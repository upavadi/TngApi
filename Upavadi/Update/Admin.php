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

                                $noteIds = $changeSet->getNoteIds($personId);
                                foreach ($noteIds as $noteId) {
                                    $this->showNotes($changeSet, $noteId, 'Person (' . $personId . ') - Notes');
                                }
                                $personId = $changeSet->getFatherId();

                                $this->showPerson($changeSet, $personId, 'Father (' . $personId . ')');
                                $personId = $changeSet->getMotherId();
                                $this->showPerson($changeSet, $personId, 'Mother (' . $personId . ')');

                                $familyId = $changeSet->getHeadPersonFamC();
                                $this->showPersonFamily($changeSet, $familyId, 'Family (' . $familyId . ')');

                                $spouses = $changeSet->getSpouseIds();
                                foreach ($spouses as $index => $personId) {
                                    $this->showPerson($changeSet, $personId, 'Spouse ' . $index . ' (' . $personId . ')');
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
                                                ?><textarea cols="50" rows="10" name="changes<?php echo $inputName; ?>"><?php echo $data['new'] ?></textarea><?php
                                                break;
                                            case 'date':
                                            case 'string':
                                                ?><input type="text" size="50" name="changes<?php echo $inputName; ?>" value="<?php echo $data['new'] ?>"><?php
                                                break;
                                            case 'boolean';
                                                if ($data['new']) {
                                                    ?><input type="checkbox" value="1" name="changes<?php echo $inputName; ?>" checked="checked"><?php
                                                } else {
                                                    ?><input type="checkbox" value="1" name="changes<?php echo $inputName; ?>"><?php
                                                }
                                                break;
                                            case 'enum':
                                                ?>
                                                <select name="changes<?php echo $inputName; ?>">
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
            header('Location: ' . $url);
            exit;
        }
        if (!isset($post['accept'])) {
            return;
        }
        $updates = array();
        foreach ($post['accept'] as $entity => $changes) {
            foreach ($changes as $id => $fields) {
                foreach ($fields as $key => $type) {
                    $value = $post['changes'][$entity][$id][$key];
                    switch ($type) {
                        case 'boolean':
                            if ($value) {
                                $value = true;
                            } else {
                                $value = false;
                            }
                            break;
                        case 'date':
                            $trKey = $key . 'tr';
                            $trValue = $this->convertDate($value);
                            $updates = $changeSet->applyChange($entity, $id, $trKey, $trValue, $updates);
                            break;
                    }
                    $updates = $changeSet->applyChange($entity, $id, $key, $value, $updates);
                }
            }
        }
        $updates = $changeSet->simplifyChanges($updates);
        $changeSet->apply($updates);
        $url = admin_url('admin.php?page=tng_api_submission_view&id=' . $changeSet->getId());
        //header('Location: ' . $url);
        exit;
    }

    private function convertDate($olddate)
    {
        //additional month names (ie, different languages) may be added with same values in case multiple languages are used in the same database
        $months = array("JAN" => 1, "FEB" => 2, "MAR" => 3, "APR" => 4, "MAY" => 5, "JUN" => 6, "JUL" => 7, "AUG" => 8, "SEP" => 9, "OCT" => 10, "NOV" => 11, "DEC" => 12);
        $hebrewmonths = array("TIS" => 1, "CHE" => 2, "HES" => 2, "KIS" => 3, "TEV" => 4, "TEB" => 4, "SHV" => 5, "SHE" => 5, "ADA" => 6, "VEA" => 7, "NIS" => 8, "IYA" => 9, "SIV" => 10, "TAM" => 11, "AB" => 12, "AV" => 12, "ELU" => 13);
        //alternatives for "BEF" and "AFT" should be entered in these lists separated by commas
        $befarray = array("BEF");
        $aftarray = array("AFT");
        $lastday = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $preferred_separator = "/";  //this character separates the components in a numeric date, as in "MM/DD/YYYY"
        $numeric_date_order = 0;  //0 = MM/DD/YYYY; 1 = DD/MM/YYYY

        if ($olddate) {
            $olddate = strtoupper(trim($olddate));
            $dateparts = array();
            $dateparts = explode(" ", $olddate);

            $found = array_search("TO", $dateparts);
            if (!$found)
                $found = array_search("AND", $dateparts);
            $ptr = $found ? $found - 1 : count($dateparts) - 1;

            $newparts = array();
            $newparts = explode($preferred_separator, $dateparts[$ptr]);
            //if number of parts is 3, insert them into array at $ptr, move $ptr up
            if (count($newparts) == 3) {
                $dateparts[$ptr++] = $newparts[0];
                $dateparts[$ptr++] = $newparts[1];
                $dateparts[$ptr] = $newparts[2];
                $reversedate = $numeric_date_order;
            } else
                $reversedate = 0;

            $slashpos = strpos($dateparts[$ptr], "/");
            if ($slashpos) {
                $wholeyear1 = strtok($dateparts[$ptr], "/");
                $wholeyear2 = strtok("/");
                $len = -1 * strlen($wholeyear2);
                $len1 = strlen($wholeyear1);
                $century = substr($wholeyear1, 0, $len1 + $len);
                $year1 = substr($wholeyear1, $len1 + $len);
                $year2 = $wholeyear2;
                if ($year1 > $year2)
                    $century++;
                $tempyear = $century . $year2;
            }
            else {
                $len = -1 * strlen($dateparts[$ptr]);
                if ($len < -4)
                    $len = -4;
                $tempyear = trim(substr($dateparts[$ptr], $len));
                $dash = strpos($tempyear, "-");
                if ($dash !== false)
                    $tempyear = substr($tempyear, $dash + 1);
            }
            if (is_numeric($tempyear)) {
                $newyear = $tempyear;
                $ptr--;

                $tempmonth = trim(substr(strtoupper($dateparts[$ptr]), 0, 3));
                //if it's in $months, or it's numeric and we're doing dd-mm-yyyy, proceed. If it's numeric and we're doing mm-dd-yyyy, then flip day and month
                $foundit = 0;
                if ($months[$tempmonth]) {
                    $newmonth = $months[$tempmonth];
                    $foundit = 1;
                } elseif ($hebrewmonths[$tempmonth]) {
                    $newmonth = $hebrewmonths[$tempmonth];
                    $foundit = 2;
                } elseif (is_numeric($tempmonth) && strlen($tempmonth) <= 2) {
                    $newmonth = intval($tempmonth);
                    $foundit = 1;
                }
                if ($foundit) {
                    $ptr--;

                    $tempday = $dateparts[$ptr];
                }

                if ($foundit == 1) {
                    //if we're doing mm/dd/yyyy, we need to switch month and day here
                    //it could be numeric, or it could be in $months, if we've switched.
                    if ($reversedate) {
                        $temppart = $newmonth;
                        $newmonth = $tempday;
                        $tempday = $temppart;
                    }
                    if (is_numeric($tempday) && strlen($tempday) <= 2) {
                        $newday = sprintf("%02d", $tempday);
                        $ptr--;
                        $str = substr(strtoupper($dateparts[$ptr]), 0, 3);
                        if (in_array($str, $aftarray)) {
                            $newday++;
                            if ($newday > $lastday[$newmonth]) {
                                $newday = 0;
                                if ($newmonth == 12)
                                    $newyear++;
                                $newmonth = $newmonth < 12 ? $newmonth + 1 : 1;
                            }
                        }
                        else if (in_array($str, $befarray)) {
                            $newday --;
                        }
                    } else {
                        $tempday2 = substr(strtoupper($tempday), 0, 3);
                        $newday = 0;
                        if (in_array($tempday2, $aftarray)) {
                            if ($newmonth == 12)
                                $newyear++;
                            $newmonth = $newmonth < 12 ? $newmonth + 1 : 1;
                        }
                    }
                }
                elseif ($foundit == 2) {
                    //Hebrew
                    if (!$tempday)
                        $tempday = 1;
                    $gregoriandate = JDtoGregorian(JewishToJD($newmonth, $tempday, $newyear));
                    $newdate = explode("/", $gregoriandate);
                    $newyear = $newdate[2];
                    $newmonth = $newdate[0];
                    $newday = $newdate[1];
                }
                else {
                    $newmonth = 0;
                    $newday = 0;
                    if (in_array($tempmonth, $aftarray)) {
                        $newyear++;
                    }
                }
            }
            $newdate = sprintf("%04d-%02d-%02d", $newyear, $newmonth, $newday);
        } else
            $newdate = "0000-00-00";
        return( $newdate );
    }

    public function showNotes(Upavadi_Update_ChangeSet $changeSet, $noteId, $title)
    {
        $changes = $changeSet->getChangesFor('notes', $noteId);
        $names = array(
            'persfamid' => array('name' => 'Person ID'),
            'note' => array('name' => 'Note', 'type' => 'text'),
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
        $this->showChanges($changes, $names, $title, 'notes', $noteId);
    }

}
