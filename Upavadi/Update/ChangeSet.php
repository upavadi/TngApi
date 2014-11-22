<?php

class Upavadi_Update_ChangeSet
{
    private $userSubmission;

    /**
     * @var Upavadi_Repository_TngRepository 
     */
    private $repo;
    private $wpdb;
    private $originals;
    private $changes;
    private $diff;

    public function __construct(Upavadi_Repository_TngRepository $repo, $wpdb, $userSubmission)
    {
        $this->repo = $repo;
        $this->wpdb = $wpdb;
        $this->userSubmission = (array)$userSubmission;
        $this->init();
    }

    public function init()
    {
        $this->loadChanges();
        $this->loadOriginals();
        $this->diff = $this->calcDiff();
    }

    public function loadChanges()
    {
        $people = $this->loadPeopleChanges();
        $family = $this->loadFamilyChanges();
        $children = $this->loadChildrenFamilyChanges();
        
        $this->changes = array(
            'people' => $people,
            'family' => $family,
            'children' => $children
        );
    }

    public function loadOriginals()
    {
        $people = $this->loadPeopleOriginals();
        $family = $this->loadFamilyOriginals();
        $children = $this->loadChildrenFamilyOriginals();
        
        $this->originals = array(
            'people' => $people,
            'family' => $family,
            'children' => $children
        );
    }

    public function calcDiff()
    {
        $changes = array();
        foreach ($this->changes as $entityName => $entities) {
            $changes[$entityName] = $this->calcDiffEntities($entities, $this->originals[$entityName]);
        }
        return $changes;
    }

    public function loadPeopleChanges()
    {
        $people = array();
        $rows =  $this->wpdb->get_results("SELECT * FROM wp_tng_people where " . $this->getKey(), ARRAY_A);
        foreach ($rows as $row) {
            $people[$row['personid']] = $row;
        }
        return $people;
    }

    public function loadFamilyChanges()
    {
        $families = array();
        $rows =  $this->wpdb->get_results("SELECT * FROM wp_tng_families where " . $this->getKey(), ARRAY_A);
        foreach ($rows as $row) {
            $families[$row['familyid']] = $row;
        }
        return $families;
    }

    public function loadChildrenFamilyChanges()
    {
        $children = array();
        $rows =  $this->wpdb->get_results("SELECT * FROM wp_tng_children where " . $this->getKey(), ARRAY_A);
        foreach ($rows as $row) {
            $children[$row['personID']] = $row;
        }
        return $children;
    }

    public function getKey()
    {
        $userLogin = $this->userSubmission['tnguser'];
        $headPersonId = $this->userSubmission['personid'];
        $dateModified = $this->userSubmission['datemodified'];
        return "tnguser = '$userLogin' AND headpersonid = '$headPersonId' AND datemodified = '$dateModified'";
    }

    public function getChanges()
    {
        return $this->changes;
    }

    public function getOriginals()
    {
        return $this->originals;
    }
    
    public function getDiff()
    {
        return $this->diff;
    }

    public function loadPeopleOriginals()
    {
        $people = array();
        foreach ($this->changes['people'] as $id => $person) {
            $people[$id] = $this->repo->getPerson($id);
        }
        return $people;
    }

    public function loadFamilyOriginals()
    {
        $families = array();
        foreach ($this->changes['family'] as $id => $family) {
            $families[$id] = $this->repo->getFamily($id);
        }
        return $families;
    }

    public function loadChildrenFamilyOriginals()
    {
        $children = array();
        foreach ($this->changes['children'] as $id => $child) {
            $children[$id] = $this->repo->getChildFamily($id, $child['familyID']);
        }
        return $children;
    }

    public function calcDiffEntities($newEntities, $oldEntities)
    {
        $changes = array();
        
        foreach ($newEntities as $id => $new) {
            if (!isset($oldEntities[$id])) {
                // new
                continue;
            }
            // Existing entities changed
            $changes[$id] = $this->calcEditEntity($new, $oldEntities[$id]);
        }
        return $changes;
    }

    public function calcEditEntity($new, $old)
    {
        $changess = array();
        $new = $this->makeSafe($new);
        $old = $this->makeSafe($old);
        foreach ($new as $key => $value) {
            if ($value !== $old[$key]) {
                $changess[$key] = array(
                    'type' => 'edit',
                    'old' => $old[$key],
                    'new' => $value
                );
            }
        }
        return $changess;
    }

    public function makeSafe($record)
    {
        $exclude = array(
            'id', 'tnguser', 'headpersonid', 'cause', 'personevent',
            'datemodified', 'birthdatetr', 'deathdatetr', 'marrdatetr'
        );
        $newRecord = array();
        foreach ($record as $key => $value) {
            $key = strtolower($key);
            if (in_array($key, $exclude)) {
                continue;
            }
            if (empty($value)) {
                $newRecord[$key] = null;
            } else {
                $newRecord[$key] = $value;
            }
        }
        return $newRecord;
    }

    public function getChangesFor($type, $id)
    {
        return array(
            'old' => $this->makeSafe($this->originals[$type][$id]),
            'new' => $this->makeSafe($this->changes[$type][$id]),
            'diff' => $this->diff[$type][$id]
        );
    }

    public function getHeadPersonId()
    {
        return $this->userSubmission['personid'];
    }


    public function getFatherId()
    {
        $familyId = $this->userSubmission['famc'];
        return $this->changes['family'][$familyId]['husband'];
    }

    public function getMotherId()
    {
        $familyId = $this->userSubmission['famc'];
        return $this->changes['family'][$familyId]['wife'];
    }

    public function getSpouseIds()
    {
        if ($this->userSubmission['sex'] === 'M') {
            $person = 'husband';
            $spouse = 'wife';
            $order = 'husborder';
        } else {
            $person = 'wife';
            $spouse = 'husband';
            $order = 'wifeorder';
        }
        $spouses = array();
        $personId = $this->getHeadPersonId();
        foreach ($this->changes['family'] as $family) {
            if ($family[$person] === $personId) {
                $spouses[$family[$order]] = $family[$spouse];
            }
        }
        ksort($spouses);
        return $spouses;
    }

    public function getChildrenIdsByParent($parentId)
    {
        $famC = $this->getSpouseFamilyId($parentId);
        $children = array();
        foreach ($this->changes['people'] as $personId => $person) {
            if (false === strpos($personId, 'I')) {
                continue;
            }
            if ($person['famc'] === $famC) {
                $childFamily = $this->changes['children'][$personId];
                $children[$childFamily['ordernum']] = $personId;
            }
        }
        
        ksort($children);
        return $children;
    }

    public function getHeadPersonFamC()
    {
        return $this->userSubmission['famc'];
    }

    public function getSpouseFamilyId($personId)
    {
        foreach ($this->changes['family'] as $familyId => $family) {
            if ($family['husband'] === $personId) {
                return $familyId;
            }
            if ($family['wife'] === $personId) {
                return $familyId;
            }
        }
        return null;
    }

}
