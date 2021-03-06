<?php

namespace GenADH\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use GenADH\Domain\User;

class UserDAO extends DAO implements UserProviderInterface
{
    /**
     * Returns a user matching the supplied id.
     *
     * @param integer $id The user id.
     *
     * @return \MicroCMS\Domain\User|throws an exception if no matching user is found
     */
    public function find($id) {
        $sql = "select * from t_user where usr_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No user matching id " . $id);
    }

    public function findAll() {
        $sql = "select * from t_user";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convert query result to an array of domain objects
        $users = array();
        foreach ($result as $row) {
            $userId = $row['usr_id'];
            $users[$userId] = $this->buildDomainObject($row);
        }
        return $users;
    }
    
    public function findByUsername($username) {
		$sql = "select * from t_user where usr_name=?";
		$result = $this->getDb()->fetchAll($sql, array($username));

      // Convert query result to an array of domain objects
      $users = array();
      foreach ($result as $row) {
          $userId = $row['usr_id'];
          $users[$userId] = $this->buildDomainObject($row);
      }
      return $users;
	}
    
	public function save(User $user, $modifypassword) {
			$userData = array(
				'usr_name' => $user->getUsername(),
				'usr_role' => $user->getRole(),
				'usr_prenom' => $user->getPrenom(),
				'usr_nom' => $user->getNom(),
				'usr_isadh' => $user->getIsadh(),
				'usr_idadh' => $user->getIdadh(),
			);
			$userData2 = array(
				'usr_password' => $user->getPassword(),
				'usr_salt' => $user->getSalt()
			);
			$userDatatot = $userData + $userData2;
			if ($user->getId()) {
				if ($modifypassword) {
					$this->getDb()->update('t_user', $userDatatot, array('usr_id' => $user->getId()));
				} else {
					$this->getDb()->update('t_user', $userData, array('usr_id' => $user->getId()));
				}
			} else {
				$this->getDb()->insert('t_user', $userDatatot);
				
				$id = $this->getDb()->lastInsertId();
				$user->setId($id);
			}
	}
    
	public function del(User $user) {
			$sql = "delete from t_user where usr_id=?";
			$this->getDb()->executeQuery($sql, array($user->getId()));
	}    
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $sql = "select * from t_user where usr_name=?";
        $row = $this->getDb()->fetchAssoc($sql, array($username));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'GenADH\Domain\User' === $class;
    }

    /**
     * Creates a User object based on a DB row.
     *
     * @param array $row The DB row containing User data.
     * @return \MicroCMS\Domain\User
     */
    protected function buildDomainObject($row) {
        $user = new User();
        $user->setId($row['usr_id']);
        $user->setUsername($row['usr_name']);
        $user->setPassword($row['usr_password']);
        $user->setSalt($row['usr_salt']);
        $user->setRole($row['usr_role']);
		$user->setNom($row['usr_nom']);
		$user->setPrenom($row['usr_prenom']);
		$user->setIsadh($row['usr_isadh']);
		$user->setIdadh($row['usr_idadh']);
        return $user;
    }
}