<?php

class Upavadi_Update_Builder
{

    /**
     *
     * @var Upavadi_Update_ChangeSet
     */
    private $changeSet;

    public function __construct(Upavadi_Update_ChangeSet $changeSet)
    {
        $this->changeSet = $changeSet;
    }

    public function build($accept, $changes)
    {
        $updates = $this->computeUpdates($accept, $changes);
        $updates = $this->simplifyChanges($updates);
        $updates = $this->fixIds($updates);
        $updates = $this->collectIds($updates);
        $updates = $this->updateIds($updates);
        $updates = $this->flatten($updates);
        $updates = $this->sort($updates);

        return $updates;
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
        } else {
            $newdate = "0000-00-00";
        }
        return( $newdate );
    }

    public function computeUpdates($accept, $changes)
    {
        $updates = array();

        foreach ($accept as $entity => $change) {
            $updates = $this->computeEntityUpdate($entity, $change, $changes[$entity], $updates);
        }
        return $updates;
    }

    public function computeEntityUpdate($entity, $change, $changes, $updates)
    {
        foreach ($change as $id => $fields) {
            foreach ($fields as $key => $type) {
                $value = $changes[$id][$key];
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
                        $updates = $this->applyChange($entity, $id, $trKey, $trValue, $updates);
                        break;
                }
                $updates = $this->applyChange($entity, $id, $key, $value, $updates);
            }
        }
        return $updates;
    }

    public function applyChange($entity, $id, $key, $value, $updates)
    {
        $type = $this->changeSet->getDiffType($entity, $id, $key);
        if ($type === 'edit') {
            $updates[] = array('update', $entity, $id, array($key => $value));
        }
        if ($type === 'add') {
            $update = array('insert', $entity, $id, array($key => $value));
            $updates[] = $update;
        }
        return $updates;
    }

    public function simplifyChanges($updates)
    {
        $additions = array();
        $edits = array();
        foreach ($updates as $update) {
            list($op, $entity, $id, $fields) = $update;
            if ($op !== 'insert') {
                continue;
            }
            if (!isset($additions[$entity])) {
                $additions[$entity] = array();
            }
            if (!isset($additions[$entity][$id])) {
                $additions[$entity][$id] = array();
            }
            $additions[$entity][$id] = array_merge($additions[$entity][$id], $fields);
        }

        foreach ($updates as $update) {
            list($op, $entity, $id, $fields) = $update;
            if ($op !== 'update') {
                continue;
            }
            if (isset($additions[$entity][$id])) {
                $additions[$entity][$id] = array_merge($additions[$entity][$id], $fields);
                continue;
            }
            if (!isset($edits[$entity])) {
                $edits[$entity] = array();
            }
            if (!isset($edits[$entity][$id])) {
                $edits[$entity][$id] = array();
            }

            $edits[$entity][$id] = array_merge($edits[$entity][$id], $fields);
        }

        return array(
            'insert' => $additions,
            'update' => $edits
        );
    }

    public function collectIds($updates)
    {
        $ids = array();
        foreach ($updates['insert'] as $type => $entity) {
            $idLabels = array_keys($entity);
            foreach ($idLabels as $idLabel) {
                $ids[$idLabel] = $type. '::' . $idLabel;
            }
        }
        $updates['ids'] = $ids;
        return $updates;
    }

    public function updateIds($updates)
    {
        $insert = $this->updateIdsForChange($updates['insert'], $updates['ids']);
        $update = $this->updateIdsForChange($updates['update'], $updates['ids']);
        $updates['insert'] = $insert;
        $updates['update'] = $update;
        return $updates;
    }

    public function updateIdsForChange($updates, $ids)
    {
        $newUpdates = array();
        foreach ($updates as $type => $entities) {
            foreach ($entities as $id => $entity) {
                $newId = $id;
                if (isset($ids[$id])) {
                    $newId = $ids[$id];
                }
                $newUpdates[$type][$newId] = $this->updateIdsForEntity($entity, $ids);
            }
        }
        return $newUpdates;
    }

    public function updateIdsForEntity($entity, $ids)
    {
        $newEntity = array();
        foreach ($entity as $key => $value) {
            if (isset($ids[$value])) {
                $newEntity[$key] = $ids[$value];
            } else {
                $newEntity[$key] = $value;
            }
        }
        return $newEntity;
    }

    public function flatten($updates)
    {
        $newUpdate = array();
        foreach ($updates as $op => $changes) {
            if ('ids' === $op) {
                continue;
            }
            foreach ($changes as $type => $entities) {
                foreach ($entities as $id => $entity) {
                    $newUpdate[] = array(
                        'op' => $op,
                        'type' => $type,
                        'id' => $id,
                        'entity' => $entity
                    );
                }
            }
        }
        return $newUpdate;
    }

    public function sort($updates)
    {
        $maxPasses = count($updates);
        $newUpdates = array();
        while (count($updates)) {
            $nextUpdate = array_shift($updates);
            if (!isset($nextUpdate['pass'])) {
                $nextUpdate['pass'] = 0;
            }
            $nextUpdate['pass'] += 1;
            if ($nextUpdate['pass'] > $maxPasses) {
                var_dump($nextUpdate);
                var_dump($updates);
                throw new RuntimeException('There is no good order to insert these records');
            }
            $dep = $this->updateDependsOn($nextUpdate, $updates);
            if ($dep) {
                list($depId, $depKey) = $dep;
                $depChange = $this->getUpdate($updates, $depId);
                if ($nextUpdate['pass'] > $depChange['pass']) {
                    $newUpdate = $nextUpdate;
                    unset($nextUpdate['entity'][$depKey]);
                    $newUpdate['op'] = 'update';
                    $newUpdate['entity'] = array($depKey => $depId);
                    $updates[] = $newUpdate;
                }
                if (count($nextUpdate['entity'])) {
                    $updates[] = $nextUpdate;
                }
            } else {
                $newUpdates[] = $nextUpdate;
            }
        }
        return $newUpdates;
    }

    public function updateDependsOn($nextUpdate, $updates)
    {
        $ids = array();
        foreach ($updates as $update) {
            if ($update['op'] === 'insert') {
                $ids[$update['id']] = true;
            }
        }
        foreach ($nextUpdate['entity'] as $key => $value) {
            if (isset($ids[$value])) {
                return array($value, $key);
            }
        }
        return false;
    }

    public function fixIds($updates)
    {
        $inserts = array();
        foreach ($updates['insert'] as $type => $entities) {
            foreach ($entities as $id => $entity) {
                $values = array_values($entity);
                if (!in_array($id, $values, true)) {
                    $inserts[$type][$id] = $entity;
                    continue;
                }
                $inserts[$type][$id . '$'] = $entity;
            }
        }
        $updates['insert'] = $inserts;
        return $updates;
    }

    public function getUpdate($updates, $id)
    {
        foreach($updates as $update) {
            if ($update['id'] === $id) {
                return $update;
            }
        }
    }

}
