<?php

namespace GenADH\DAO;

use GenADH\Domain\Adherent;
use GenADH\Domain\User;

class AdherentDAO extends DAO
{
    public function findAll() {
        $sql = "SELECT * FROM `t_adherent`";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convert query result to an array of domain objects
        $adherents = array();
        foreach ($result as $row) {
            $adherentId = $row['adh_id'];
            $adherents[$adherentId] = $this->buildDomainObject($row);
        }
        return $adherents;
    }
    
	/**
	* Returns an article matching the supplied id.
	*
	* @param integer $id
	*
	* @return \MicroCMS\Domain\Article|throws an exception if no matching article is found
	*/
	public function find($id) {
		$sql = "select * from t_adherent where adh_id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));

		if ($row)
			return $this->buildDomainObject($row);
		else
			throw new \Exception("Aucun adhérent ne corrspond à l'id " . $id);
	}  
	
	public function findByGroupe($id) {
		$sql = "select * from t_adherent where adh_groupe=?";
		$result = $this->getDb()->fetchAll($sql, array($id));

      // Convert query result to an array of domain objects
      $adherents = array();
      foreach ($result as $row) {
          $adherentId = $row['adh_id'];
          $adherents[$adherentId] = $this->buildDomainObject($row);
      }
      return $adherents;
	}
	
	public function save(Adherent $adherent, User $usernow) {
			$datenow = date("Y-m-d H:i:s");
			$adherentData = array(
				'adh_civilite' => $adherent->getCivilite(),
				'adh_nom' => $adherent->getNom(),
				'adh_prenom' => $adherent->getPrenom(),
				'adh_groupe' => $adherent->getGroupe(),
				'adh_adresse' => $adherent->getAdresse(),
				'adh_codepostal' => $adherent->getCodePostal(),
				'adh_ville' => $adherent->getVille(),
				'adh_departement' => $adherent->getDepartement(),
				'adh_pays' => $adherent->getPays(),
				'adh_email' => $adherent->getEmail(),
				'adh_phone' => $adherent->getPhone(),
				'adh_mobile' => $adherent->getMobile(),
				'adh_datenaiss' => $adherent->getDateNaiss(),
				'adh_datelastmod' => $datenow,
				'int_user_lastmodauteur' => $usernow->getId(),
				'adh_isajour' => $adherent->getIsajour()
			);
			if ($adherent->getId()) {
				
				$this->getDb()->update('t_adherent', $adherentData, array('adh_id' => $adherent->getId()));
			} else {
				$adherentData2 = array(
					'adh_datecreation' => $datenow,
					'int_user_auteur' => $usernow->getId()
				);
				$adherentDatatot = $adherentData + $adherentData2;
				$this->getDb()->insert('t_adherent', $adherentDatatot);
				
				$id = $this->getDb()->lastInsertId();
				$adherent->setId($id);
			}
	}
	
	public function del(Adherent $adherent) {
			$sql = "delete from t_adherent where adh_id=?";
			$this->getDb()->executeQuery($sql, array($adherent->getId()));
	}

    /**
     * Creates an Article object based on a DB row.
     *
     * @param array $row The DB row containing Article data.
     * @return \MicroCMS\Domain\Article
     */
    protected function buildDomainObject($row) {
        $adherent = new Adherent();
        $adherent->setId($row['adh_id']);
        $adherent->setCivilite($row['adh_civilite']);
        $adherent->setNom($row['adh_nom']);
        $adherent->setPrenom($row['adh_prenom']);
        $adherent->setGroupe($row['adh_groupe']);
        $adherent->setAdresse($row['adh_adresse']);
        $adherent->setCodePostal($row['adh_codepostal']);
        $adherent->setVille($row['adh_ville']);
        $adherent->setDepartement($row['adh_departement']);
        $adherent->setPays($row['adh_pays']);
        $adherent->setEmail($row['adh_email']);
        $adherent->setPhone($row['adh_phone']);
        $adherent->setMobile($row['adh_mobile']);
        $adherent->setDateNaiss($row['adh_datenaiss']);
        $adherent->setDateCreation($row['adh_datecreation']);
        $adherent->setUserAuteur($row['int_user_auteur']);
        $adherent->setDateLastMod($row['adh_datelastmod']);
        $adherent->setUserLastModAuteur($row['int_user_lastmodauteur']);
        $adherent->setIsajour($row['adh_isajour']);

        return $adherent;
    }
}
