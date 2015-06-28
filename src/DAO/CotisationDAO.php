<?php

namespace GenADH\DAO;

use GenADH\Domain\Cotisation;

class CotisationDAO extends DAO
{
    public function findAll() {
        $sql = "SELECT * FROM `t_adherent_cotisation`";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convert query result to an array of domain objects
        $cotisations = array();
        foreach ($result as $row) {
            $cotisationId = $row['cot_id'];
            $cotisations[$cotisationId] = $this->buildDomainObject($row);
        }
        return $cotisations;
    }
    
	/**
	* Returns an article matching the supplied id.
	*
	* @param integer $id
	*
	* @return \MicroCMS\Domain\Article|throws an exception if no matching article is found
	*/
	public function find($id) {
		$sql = "select * from t_adherent_cotisation where cot_id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));

		if ($row)
			return $this->buildDomainObject($row);
		else
			throw new \Exception("Aucune cotisation ne corrspond à l'id " . $id);
	}  
	
	public function findByUser($userid) {
		$sql = "select * from t_adherent_cotisation where cot_adh=? order by cot_date desc";
		$result = $this->getDb()->fetchAll($sql, array($userid));

      // Convert query result to an array of domain objects
      $cotisations = array();
      foreach ($result as $row) {
          $cotisationId = $row['cot_id'];
          $cotisations[$cotisationId] = $this->buildDomainObject($row);
      }
      return $cotisations;
	}
	
	public function findAllByUser() {
		$sql = "SELECT * FROM `t_adherent_cotisation` order by cot_date asc";
		$result = $this->getDb()->fetchAll($sql);
		
		$cotisations = array();
		foreach ($result as $row) {
			$cotisationUser = $row['cot_adh'];
			$cotisations[$cotisationUser] = $this->buildDomainObject($row);
		}
		return $cotisations;
	}
	
	public function save(Cotisation $cotisation) {
			$cotisationData = array(
				'cot_date' => $cotisation->getDate(),
				'cot_adh' => $cotisation->getAdhid(),
				'cot_montant' => $cotisation->getMontant(),
				'int_user_auteur' => $cotisation->getAuteur()
			);
			if ($cotisation->getId()) {
				$this->getDb()->update('t_adherent_cotisation', $cotisationData, array('cot_id' => $cotisation->getId()));
			} else {
				$this->getDb()->insert('t_adherent_cotisation', $cotisationData);
				
				$id = $this->getDb()->lastInsertId();
				$cotisation->setId($id);
			}
	}
	
	public function del(Cotisation $cotisation) {
			$sql = "delete from t_adherent_cotisation where cot_id=?";
			$this->getDb()->executeQuery($sql, array($cotisation->getId()));
	}

    /**
     * Creates an Article object based on a DB row.
     *
     * @param array $row The DB row containing Article data.
     * @return \MicroCMS\Domain\Article
     */
    protected function buildDomainObject($row) {
        $cotisation = new Cotisation();
        $cotisation->setId($row['cot_id']);
        $cotisation->setDate($row['cot_date']);
        $cotisation->setAdhId($row['cot_adh']);
        $cotisation->setMontant($row['cot_montant']);
        $cotisation->setAuteur($row['int_user_auteur']);

        return $cotisation;
    }
}
