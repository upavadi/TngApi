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
        $this->userSubmission = (array) $userSubmission;
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
        $notes = $this->loadNotesChanges();
        $events = $this->loadEventsChanges();

        $this->changes = array(
            'people' => $people,
            'family' => $family,
            'children' => $children,
            'notes' => $notes,
            'events' => $events
        );
    }

    public function loadOriginals()
    {
        $people = $this->loadPeopleOriginals();
        $family = $this->loadFamilyOriginals();
        $children = $this->loadChildrenFamilyOriginals();
        $notes = $this->loadNotesOriginals();
        $events = $this->loadEventsOriginals();

        $this->originals = array(
            'people' => $people,
            'family' => $family,
            'children' => $children,
            'notes' => $notes,
            'events' => $events
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
        $rows = $this->wpdb->get_results("SELECT * FROM wp_tng_people where " . $this->getKey(), ARRAY_A);
        foreach ($rows as $row) {
            $people[$row['personID']] = $row;
        }
        return $people;
    }

    public function loadFamilyChanges()
    {
        $families = array();
        $rows = $this->wpdb->get_results("SELECT * FROM wp_tng_families where " . $this->getKey(), ARRAY_A);
        foreach ($rows as $row) {
            $families[$row['familyID']] = $row;
        }
        return $families;
    }

    public function loadChildrenFamilyChanges()
    {
        $children = array();
        $rows = $this->wpdb->get_results("SELECT * FROM wp_tng_children where " . $this->getKey(), ARRAY_A);
        foreach ($rows as $row) {
            $children[$row['personID']] = $row;
        }
        return $children;
    }

    public function getKey()
    {
        $userLogin = $this->userSubmission['tnguser'];
        $headPersonId = $this->userSubmission['personID'];
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
            $tree = $person['gedcom'];
            $people[$id] = $this->repo->getPerson($id, $tree);
        }
        return $people;
    }

    public function loadFamilyOriginals()
    {
        $families = array();
        foreach ($this->changes['family'] as $id => $family) {
            $tree = $family['gedcom'];
            $families[$id] = $this->repo->getFamily($id, $tree);
        }
        return $families;
    }

    public function loadChildrenFamilyOriginals()
    {
        $children = array();
        foreach ($this->changes['children'] as $id => $child) {
            $tree = $child['gedcom'];
            $children[$id] = $this->repo->getChildFamily($id, $child['familyID'], $tree);
        }
        return $children;
    }

    public function calcDiffEntities($newEntities, $oldEntities)
    {
        $changes = array();
        foreach ($newEntities as $id => $new) {
            if (!isset($oldEntities[$id])) {
                // new
                $new = $this->makeSafe($new);
                foreach ($new as $key => $value) {
                    $changes[$id][$key] = array(
                        'type' => 'add',
                        'old' => null,
                        'new' => $value
                    );
                }
            } else {
                // Existing entities changed
                $changes[$id] = $this->calcEditEntity($new, $oldEntities[$id]);
            }
        }
        return $changes;
    }

    public function calcEditEntity($new, $old)
    {
        $changes = array();
        $new = $this->makeSafe($new);
        $old = $this->makeSafe($old);
        foreach ($new as $key => $value) {
            if ($value === null) {
                $changes[$key] = array(
                    'type' => 'exclude',
                    'old' => $old[$key],
                    'new' => $value
                );
            } else if ($value !== $old[$key]) {
                $changes[$key] = array(
                    'type' => 'edit',
                    'old' => $old[$key],
                    'new' => $value
                );
            }
        }
        return $changes;
    }

    public function makeSafe($record)
    {
        if (!$record) {
            return array();
        }
        $exclude = array(
            'id', 'tnguser', 'headpersonid', 'personevent',
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
        return $this->userSubmission['personID'];
    }

    public function getHeadPersonTree()
    {
        return $this->userSubmission['gedcom'];
    }
    
    public function getFatherId()
    {
        $familyId = $this->userSubmission['famc'];
        if ($this->changes['family'][$familyId]['husband']) {
            return $this->changes['family'][$familyId]['husband'];
        }
    }

    public function getMotherId()
    {
        $familyId = $this->userSubmission['famc'];
        return $this->changes['family'][$familyId]['wife'];
    }

    public function getSpouseIds()
    {
        $personId = $this->getHeadPersonId();

        if ($this->originals['people'][$personId]['sex'] === 'M') {
            $person = 'husband';
            $spouse = 'wife';
            $order = 'husborder';
        } else {
            $person = 'wife';
            $spouse = 'husband';
            $order = 'wifeorder';
        }
        $spouses = array();
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
            if ($person['famc'] === $famC) {
                $childFamily = $this->changes['children'][$personId];
                $children[$childFamily['ordernum']] = $personId;
            }
        }

        ksort($children);
        return $children;
    }

    public function getId()
    {
        return $this->userSubmission['id'];
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

    public function applyChange($entity, $id, $key, $value, $updates)
    {
        $type = $this->getDiffType($entity, $id, $key);

        if ($type === 'edit') {
            $updates[] = array('update', $entity, $id, array($key => $value));
        }
        if ($type === 'add') {
            $update = array('insert', $entity, $id, array($key => $value));
            if (in_array($update, $updates)) {
                return $updates;
            }
            $updates[] = $update;
            $newIds = $this->updateIds($id, $entity, $updates);
            foreach ($newIds as $update) {
                $updates[] = $update;
            }
        }
        return $updates;
    }

    public function getDiffType($entity, $id, $key, $default = 'edit')
    {
        if (!isset($this->diff[$entity])) {
            return $default;
        }
        if (!isset($this->diff[$entity][$id])) {
            return $default;
        }
        if (!isset($this->diff[$entity][$id][$key])) {
            return $default;
        }
        return $this->diff[$entity][$id][$key]['type'];
    }

    public function apply($changes)
    {
        $headPerson = $this->originals['people'][$this->getHeadPersonId()];
        $ids = array();
        foreach ($changes as $change) {
            if ($change['op'] !== 'insert') {
                continue;
            }
            $newId = $this->applyInsert($change, $headPerson, $ids);
            $ids[$change['id']] = $newId;
        }
        foreach ($changes as $change) {
            if ($change['op'] !== 'update') {
                continue;
            }
            $this->applyUpdate($change, $headPerson, $ids);
        }
        
        $this->updateChangeIds($ids);
    }

    public function applyUpdate($change, $headPerson, $ids)
    {
        $id = $change['id'];
        if (isset($ids[$id])) {
            $id = $ids[$id];
        }
        $entity = $change['type'];
        $fields = $this->replaceIds($change['entity'], $ids);
//        print_r($id);
//        print_r($fields);
        $gedcom = $headPerson['gedcom'];
        switch ($entity) {
            case 'people':
                $fields['changedate'] = $this->userSubmission['datemodified'];
                $fields['changedby'] = $this->userSubmission['tnguser'];
                $this->repo->updatePerson($id, $fields, $gedcom);
                break;
            case 'family':
                $fields['changedate'] = $this->userSubmission['datemodified'];
                $fields['changedby'] = $this->userSubmission['tnguser'];
                $this->repo->updateFamily($id, $fields, $gedcom);
                break;
            case 'children':
                $this->repo->updateChildren($id, $fields, $gedcom);
                break;
            case 'notes':
                unset($fields['persfamID']);
                unset($fields['persfamid']);
                $this->repo->updateNote($id, $fields, $gedcom);
                break;
            case 'events':
                $this->repo->updateEvent($id, $fields, $gedcom);
                break;
        }
    }

    public function applyInsert($change, $headPerson, $ids)
    {
        $id = $change['id'];
        $entity = $change['type'];
        $fields = $this->replaceIds($change['entity'], $ids);
//        print_r($id);
//        print_r($fields);
        switch ($entity) {
            case 'people':
                if (!$fields['firstname']) {
                    $newId = null;
                    break;
                }
                $fields['changedate'] = $this->userSubmission['datemodified'];
                $fields['changedby'] = $this->userSubmission['tnguser'];
                $fields['gedcom'] = $headPerson['gedcom'];
                $newId = $this->repo->addPerson($fields);
                break;
            case 'family':
                $fields['changedate'] = $this->userSubmission['datemodified'];
                $fields['changedby'] = $this->userSubmission['tnguser'];
                $fields['gedcom'] = $headPerson['gedcom'];
                $newId = $this->repo->addFamily($fields);
                break;
            case 'children':
                $fields['gedcom'] = $headPerson['gedcom'];
                $newId = $this->repo->addChildren($fields);
                break;
            case 'notes':
                $fields['gedcom'] = $headPerson['gedcom'];
                $fields['ordernum'] = 999;
                if (!isset($fields['secret'])) {
                    $fields['secret'] = 0;
                }
                if (isset($fields['persfamID'])) {
                    unset($fields['persfamid']);
                }
                $newId = $this->repo->addNote($fields);
                break;
            case 'events':
                $fields['gedcom'] = $headPerson['gedcom'];
                if (isset($fields['persfamID'])) {
                    unset($fields['persfamid']);
                }
                $newId = $this->repo->addEvent($fields);
                break;
        }
        return $newId;
    }

    public function updateIds($newId, $entity, $updates)
    {
        foreach ($this->changes as $type => $entities) {
            foreach ($entities as $id => $fields) {
                if ($id === $newId && $type === $entity) {
                    continue;
                }
                foreach ($fields as $key => $value) {
                    if ($newId === $value) {
                        $updates = $this->applyChange($type, $id, $key, '::' . $value, $updates);
                    }
                }
            }
        }
        return $updates;
    }

    public function replaceIds($fields, $ids)
    {
        foreach ($fields as $key => $value) {
            if (preg_match('/^[^\s]+::[^\s]+$/', $value)) {
                if (!isset($ids[$value])) {
                    throw new RuntimeException('Id not found for ' . $value);
                }
                $fields[$key] = $ids[$value];
            }
        }
        return $fields;
    }

    public function updateChangeIds($newIds)
    {
        // var_dump($newIds);
        $ids = array();
        foreach ($newIds as $id => $newId) {
            list($entity, $id) = explode('::', $id);
            $ids[$id] = $newId;
        }
        // var_dump($ids);
        $updates = array();
        foreach ($this->changes as $entity => $entities) {
            $pkId = null;
            switch ($entity) {
                case 'people':
                    $table = $this->wpdb->prefix . 'tng_people';
                    $pk = 'personID';
                    break;
                case 'family':
                    $table = $this->wpdb->prefix . 'tng_families';
                    $pk = 'familyID';
                    break;
                case 'children':
                    $table = $this->wpdb->prefix . 'tng_children';
                    $pk = 'id';
                    $pkId = 'id';
                    break;
                case 'notes':
                    $table = $this->wpdb->prefix . 'tng_notes';
                    $pk = 'xnoteID';
                    break;
                case 'events':
                    $table = $this->wpdb->prefix . 'tng_events';
                    $pk = 'eventID';
                    break;
            }

            foreach ($entities as $id => $fields) {
                if ($pkId) {
                    $id = $fields[$pkId];
                }
                $id = serialize(array($pk, $id));
                foreach ($fields as $key => $value) {
                    if (!isset($ids[$value])) {
                        continue;
                    }

                    if (!isset($updates[$table])) {
                        $updates[$table] = array();
                    }
                    if (!isset($updates[$table][$id])) {
                        $updates[$table][$id] = array();
                    }
                    $updates[$table][$id][$key] = $ids[$value];
                }
            }
        }

        // var_dump($updates);
        foreach ($updates as $table => $records) {
            foreach ($records as $id => $fields) {
                list($pk, $id) = unserialize($id);
                $where = array(
                    'headpersonid' => $this->userSubmission['headpersonid'],
                    'tnguser' => $this->userSubmission['tnguser'],
                    'datemodified' => $this->userSubmission['datemodified'],
                    $pk => $id
                );
                $this->wpdb->update($table, $fields, $where);
            }
        }
    }

    public function discard()
    {
        $where = array(
            'headpersonid' => $this->userSubmission['headpersonid'],
            'tnguser' => $this->userSubmission['tnguser'],
            'datemodified' => $this->userSubmission['datemodified'],
        );
        $tables = array(
            $this->wpdb->prefix . 'tng_people',
            $this->wpdb->prefix . 'tng_families',
            $this->wpdb->prefix . 'tng_children',
            $this->wpdb->prefix . 'tng_events',
            $this->wpdb->prefix . 'tng_notes'
        );
        foreach ($tables as $table) {
            $this->wpdb->delete($table, $where);
        }
    }

    public function loadNotesChanges()
    {
        $notes = array();
        $rows = $this->wpdb->get_results("SELECT * FROM wp_tng_notes where " . $this->getKey(), ARRAY_A);
        $notes = array();
        $index = 0;

        foreach ($rows as $row) {
            $notes[$row['xnoteID']] = $row;
        }
        return $notes;
    }

    public function loadNotesOriginals()
    {
        $notes = array();
        foreach ($this->changes['notes'] as $id => $note) {
            $personId = $note['persfamID'];
            $tree = $note['gedcom'];
            $originalNote = $this->repo->getNote($personId, $id, $tree);
            if ($originalNote) {
                $notes[$id] = $originalNote;
            }
        }
        return $notes;
    }

    public function getNoteIds($personId)
    {
        $ids = array();
        foreach ($this->changes['notes'] as $id => $note) {
            if ($note['persfamID'] !== $personId) {
                continue;
            }
            $ids[] = $id;
        }
        return $ids;
    }

    public function loadEventsChanges()
    {
        $events = array();
        $rows = $this->wpdb->get_results("SELECT * FROM wp_tng_events where " . $this->getKey(), ARRAY_A);
        foreach ($rows as $row) {
            $events[$row['eventID']] = $row;
        }
        return $events;
    }

    public function loadEventsOriginals()
    {
        $events = array();
        foreach ($this->changes['events'] as $id => $event) {
            $tree = $event['gedcom'];
            $events[$id] = $this->repo->getEvent($id, $tree);
        }
        return $events;
    }

    public function getEventIds($personId)
    {
        $ids = array();
        foreach ($this->changes['events'] as $id => $note) {
            if ($note['persfamID'] !== $personId) {
                continue;
            }
            $ids[] = $id;
        }
        return $ids;
    }

}
