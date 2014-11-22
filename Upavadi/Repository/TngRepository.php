<?php

class Upavadi_Repository_TngRepository
{
    /**
     * @var Upavadi_TngContent 
     */
    private $content;

    private $people = array();
    private $families = array();
    private $childFamilies = array();

    public function __construct(Upavadi_TngContent $content)
    {
        $this->content = $content;
    }
    
    public function getPerson($id)
    {
        if (!isset($this->people[$id])) {
            $this->people[$id] = $this->content->getPerson($id);
        }
        return $this->people[$id];
    }
    
    public function getFamily($id)
    {
        if (!isset($this->families[$id])) {
            $this->families[$id] = $this->content->getFamilyById($id);
        }
        return $this->families[$id];
    }

    public function getChildFamily($personID, $familyID)
    {
        if (!isset($this->childFamilies[$personID]) || (isset($this->childFamilies[$personID]) && !isset($this->childFamilies[$personID][$familyID]))) {
            $this->childFamilies[$personID][$familyID] = $this->content->getChildFamily($personID, $familyID);
        }
        return $this->childFamilies[$personID][$familyID];
    }

}
