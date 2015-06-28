<?php

namespace GenADH\DAO;

use GenADH\Domain\AdhGroupe;

class AdhGroupeDAO extends DAO
{
	/**
	* Returns a user matching the supplied id.
	*
	* @param integer $id The user id.
	*
	* @return \MicroCMS\Domain\User|throws an exception if no matching user is found
	*/
	public function find($id) {
		$sql = "select * from t_adherent_groupe where adh_type_id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));

		if ($row)
			return $this->buildDomainObject($row);
		else
			throw new \Exception("Pas de groupe correspondant a l'id " . $id);
	}

	public function findAll() {
		$sql = "select * from t_adherent_groupe";
		$result = $this->getDb()->fetchAll($sql);
        
		// Convert query result to an array of domain objects
		$groupes = array();
		foreach ($result as $row) {
			$groupeId = $row['adh_type_id'];
			$groupes[$groupeId] = $this->buildDomainObject($row);
		}
		return $groupes;
	}
	
	public function save(AdhGroupe $groupe) {
			$groupeData = array(
				'adh_type_libelle' => $groupe->getLibelle(),
				'adh_type_cotisation' => $groupe->getTypeCotisation()
			);
			if ($groupe->getId()) {
				$this->getDb()->update('t_adherent_groupe', $groupeData, array('adh_type_id' => $groupe->getId()));
			} else {
				$this->getDb()->insert('t_adherent_groupe', $groupeData);
				
				$id = $this->getDb()->lastInsertId();
				$groupe->setId($id);
			}
	}
	public function del(AdhGroupe $adhgroupe) {
			$sql = "delete from t_adherent_groupe where adh_type_id=?";
			$this->getDb()->executeQuery($sql, array($adhgroupe->getId()));
	}

	public function total() {
		$sql = "select count(*) from t_adherent_groupe";
		$result = $this->getDb()->fetchAll($sql);
		
		$result = $result[0]["count(*)"];
		return $result;
	}
    
	protected function buildDomainObject($row) {
		$groupe = new AdhGroupe();
		$groupe->setId($row['adh_type_id']);
		$groupe->setLibelle($row['adh_type_libelle']);
		$groupe->setTypeCotisation($row['adh_type_cotisation']);
		return $groupe;
	}
}