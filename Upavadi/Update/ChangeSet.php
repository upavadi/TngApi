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

        $this->changes = array(
            'people' => $people,
            'family' => $family,
            'children' => $children,
            'notes' => $notes
        );
    }

    public function loadOriginals()
    {
        $people = $this->loadPeopleOriginals();
        $family = $this->loadFamilyOriginals();
        $children = $this->loadChildrenFamilyOriginals();
        $notes = $this->loadNotesOriginals();

        $this->originals = array(
            'people' => $people,
            'family' => $family,
            'children' => $children,
            'notes' => $notes
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
            $people[$row['personid']] = $row;
        }
        return $people;
    }

    public function loadFamilyChanges()
    {
        $families = array();
        $rows = $this->wpdb->get_results("SELECT * FROM wp_tng_families where " . $this->getKey(), ARRAY_A);
        foreach ($rows as $row) {
            $families[$row['familyid']] = $row;
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
        $changess = array();
        $new = $this->makeSafe($new);
        $old = $this->makeSafe($old);
        foreach ($new as $key => $value) {
            if ($value === null) {
                $changess[$key] = array(
                    'type' => 'exclude',
                    'old' => $old[$key],
                    'new' => $value
                );
            } else if ($value !== $old[$key]) {
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
        if (!$record) {
            return array();
        }
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
            $updates[] = array('insert', $entity, $id, array($key => $value));
            $newIds = $this->updateIds($id, $entity);
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

        return array($additions, $edits);
    }

    public function apply($changes)
    {
        $ids = array();
        list($inserts, $updates) = $changes;
        $headPerson = $this->originals['people'][$this->getHeadPersonId()];

        $inserts = $this->sortInserts($inserts);

        foreach ($inserts as $insert) {
            list($entity, $id, $fields) = $insert;
            $fields = $this->replaceIds($fields, $ids);
            switch ($entity) {
                case 'people':
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
                    $fields['secret'] = 0;
                    if (isset($fields['persfamID'])) {
                        unset($fields['persfamid']);
                    }
                    $this->repo->addNote($fields);
                    break;
            }
            $ids[$id] = $newId;
        }

        foreach ($updates as $entity => $update) {
            foreach ($update as $id => $fields) {
                if (isset($ids[$id])) {
                    $id = $ids[$id];
                }
                $fields = $this->replaceIds($fields, $ids);
                switch ($entity) {
                    case 'people':
                        $fields['changedate'] = $this->userSubmission['datemodified'];
                        $fields['changedby'] = $this->userSubmission['tnguser'];
                        $this->repo->updatePerson($id, $fields);
                        break;
                    case 'family':
                        $fields['changedate'] = $this->userSubmission['datemodified'];
                        $fields['changedby'] = $this->userSubmission['tnguser'];
                        $this->repo->updateFamily($id, $fields);
                        break;
                    case 'children':
                        $this->repo->updateChildren($id, $fields);
                        break;
                    case 'notes':
                        $this->repo->updateNote($id, $fields);
                        break;
                }
            }
        }

        $this->updateChangeIds($ids);
    }

    public function updateIds($newId, $entity)
    {
        $updates = array();
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

    public function replaceIds($fields, $ids, $id = null)
    {
        foreach ($fields as $key => $value) {
            if (preg_match('/^::(.*)$/', $value, $m)) {
                if ($m[1] !== $id && !isset($ids[$m[1]])) {
                    return null;
                } else {
                    $fields[$key] = $ids[$m[1]];
                }
            }
        }
        return $fields;
    }

    public function sortInserts($inserts)
    {
        $orderedInserts = array();
        $unorderedInserts = array();
        $ids = array();
        foreach ($inserts as $entity => $insert) {
            foreach ($insert as $id => $fields) {
                $unorderedInserts[] = array($entity, $id, $fields);
            }
        }
        $attempts = 5 * count($unorderedInserts);

        while (count($unorderedInserts) && --$attempts) {
            $insert = array_shift($unorderedInserts);
            list($entity, $id, $fields) = $insert;
            $pk = null;
            if (in_array($entity, array('people', 'family'))) {
                $pk = $id;
            }
            if ($this->replaceIds($fields, $ids, $pk)) {
                $ids[$id] = '*' . $id;
                $orderedInserts[] = $insert;
            } else {
                $unorderedInserts[] = $insert;
            }
        }
        if (count($unorderedInserts) && $attempts <= 0) {
            var_dump($unorderedInserts);
            var_dump($orderedInserts);
            var_dump($ids);
            throw new RuntimeException('There is no good order to insert these records');
        }
        return $orderedInserts;
    }

    public function updateChangeIds($ids)
    {
        $updates = array();
        foreach ($this->changes as $entity => $entities) {
            switch ($entity) {
                case 'people':
                    $table = $this->wpdb->prefix . 'tng_people';
                    $pk = 'personid';
                    break;
                case 'family':
                    $table = $this->wpdb->prefix . 'tng_families';
                    $pk = 'familyid';
                    break;
                case 'children':
                    $table = $this->wpdb->prefix . 'tng_children';
                    $pk = 'personID';
                    break;
                case 'notes':
                    $table = $this->wpdb->prefix . 'tng_notes';
                    $pk = 'xnoteID';
                    break;
            }
            
            foreach ($entities as $id => $fields) {
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
            $types = array();
            foreach ($row as $key => $value) {
                if (preg_match('/^note(.*)ID$/', $key, $m)) {
                    $type = $m[1];
                    $types[] = $type;
                }
            }
            foreach ($types as $type) {
                $note = array();
                $note['persfamID'] = $row['persfamID'];
                $note['eventID'] = '';
                switch ($type) {
                    case 'birt':
                        $note['eventID'] = 'BIRT';
                        break;
                    case 'deat':
                        $note['eventID'] = 'DEAT';
                        break;
                    case 'name':
                        $note['eventID'] = 'NAME';
                        break;
                    case 'buri':
                        $note['eventID'] = 'BURI';
                        break;
                }
                $note['note'] = $row['note' . $type];
                $note['xnoteID'] = $row['note' . $type . 'ID'];

                if ($note['note'] || $note['xnoteID']) {
                    if (!$note['xnoteID']) {
                        $index++;
                        $note['xnoteID'] = 'NewNote' . $index;
                    }
                    $notes[$note['xnoteID']] = $note;
                }
            }
        }
        return $notes;
    }

    public function loadNotesOriginals()
    {
        $notes = array();
        foreach ($this->changes['notes'] as $id => $note) {
            $personId = $note['persfamID'];
            $originalNote = $this->repo->getNote($personId, $id);
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

}
