<?php
use Symfony\Component\HttpFoundation\Request;
use GenADH\Form\Type\AdherentType;
use GenADH\Form\Type\AdhGroupeType;
use GenADH\Form\Type\UserType;
use GenADH\Form\Type\CotisationType;
use GenADH\Domain\Adherent;
use GenADH\Domain\AdhGroupe;
use GenADH\Domain\User;
use GenADH\Domain\Cotisation;

$app->get('/', function() use ($app) {
    return $app->redirect($app["url_generator"]->generate("ac"));
});

// Home page
$app->get('/logged', function () use ($app) {
	return $app['twig']->render('accueil.html.twig');
})->bind('ac');

$app->get('/logged/liste', function () use ($app) {
   $adherents = $app['dao.adherent']->findAllClassedByName();
	$users = $app['dao.user']->findAll();
	$groupes = $app['dao.groupe']->findAll();
	$cotisations = $app['dao.cotisation']->findAllByUser();
	//var_dump($cotisations);
	foreach ($adherents as $adhval) {
		if (isset($cotisations[$adhval->getId()])) {
		$cotisation = $cotisations[$adhval->getId()];
		$act = false;
		if (empty($cotisation)) {
			if ($adhval->getIsajour() == true) {
				$adhval->setIsajour(false);
				$act = true;
			}
		} else {
			$datecot = date_parse($cotisation->getDate());
			if ($datecot['year'] == date("Y") && $adhval->getIsajour() == false) {
				$adhval->setIsajour(true);
				$act = true;			
			} else if ($datecot['year'] < date("Y") && $adhval->getIsajour() == true) {
				$adhval->setIsajour(false);
				$act = true;
			}
		}
		if ($act) {
			$usernow = $app['security']->getToken()->getUser();
			$app['dao.adherent']->save($adhval, $usernow);
		}
	}}

   return $app['twig']->render('liste.html.twig', array('adherents' => $adherents, 'users' => $users, 'groupes' => $groupes));
})->bind('liste');

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');  // named route so that path('login') works in Twig templates

/*$app->get('/hashpwd', function() use ($app) {
    $rawPassword = 'louis1';
    $salt = '%qUgq3NAYfj1MiwqW?yevbE';
    $encoder = $app['security.encoder.digest'];
    return $encoder->encodePassword($rawPassword, $salt);
});*/

$app->get('/logged/adherent/{id}', function ($id) use ($app) {
   $adherent = $app['dao.adherent']->find($id);
	$users = $app['dao.user']->findAll();
	$groupes = $app['dao.groupe']->findAll();
	$cotisations = $app['dao.cotisation']->findByUser($id);

   return $app['twig']->render('adherent.html.twig', array('adherent' => $adherent, 'users' => $users, 'groupes' => $groupes, 'cotisations' => $cotisations));
})->bind('adherent');

$app->match('/logged/adherent-mod/{id}', function ($id, Request $request) use ($app) {
   $adherent = $app['dao.adherent']->find($id);
	$users = $app['dao.user']->findAll();
	$groupes = $app['dao.groupe']->findAll();
	
	$usernow = $app['security']->getToken()->getUser();
	$adherentFormView = null;
	if ($app['security']->isGranted('IS_AUTHENTICATED_FULLY')) {
		$adherentForm = $app['form.factory']->create(new AdherentType($groupes), $adherent);
		$adherentForm->handleRequest($request);
		if ($adherentForm->isSubmitted() && $adherentForm->isValid()) {
			$app['dao.adherent']->save($adherent, $usernow);
			$app['session']->getFlashBag()->add('success', 'L\'utilisateur a bien été modifié');
		}
		$adherentFormView = $adherentForm->createView();
	}
	return $app['twig']->render('adherentform.html.twig', array('adherent' => $adherent, 'users' => $users, 'groupes' => $groupes, 'adherentForm' => $adherentFormView));
	
})->bind("adherentmod");

$app->match('/logged/adherent-add/', function (Request $request) use ($app) {
   $adherent = new Adherent();
   $adherent->setIsajour(false);
	$users = $app['dao.user']->findAll();
	$groupes = $app['dao.groupe']->findAll();
	
	$usernow = $app['security']->getToken()->getUser();
	$adherentFormView = null;
	if ($app['security']->isGranted('IS_AUTHENTICATED_FULLY')) {
		$adherentForm = $app['form.factory']->create(new AdherentType($groupes), $adherent);
		$adherentForm->handleRequest($request);
		if ($adherentForm->isSubmitted() && $adherentForm->isValid()) {
			$app['dao.adherent']->save($adherent, $usernow);
			$app['session']->getFlashBag()->add('success', 'L\'utilisateur a bien été ajouté');
			return $app->redirect($app["url_generator"]->generate("adherent", array('id' => $adherent->getId())));
		} else {
			$adherentFormView = $adherentForm->createView();
			return $app['twig']->render('adherentform.html.twig', array('adherent' => $adherent, 'users' => $users, 'groupes' => $groupes, 'adherentForm' => $adherentFormView));
		}
	}
})->bind("adherentadd");

$app->match('/logged/adherent-del/{id}', function ($id, Request $request) use ($app) {
	$adherent = $app['dao.adherent']->find($id);
	$cotisations = $app['dao.cotisation']->findByUser($id);
	foreach ($cotisations as $cot) {
		$app['dao.cotisation']->del($cot);
	}
	$app['dao.adherent']->del($adherent);
	
	return $app->redirect($app["url_generator"]->generate("liste"));
	
})->bind("adherentdel");

$app->get('/logged/groupe/{id}', function ($id) use ($app) {
   $adherents = $app['dao.adherent']->findByGroupe($id);
   $nbadh = count($adherents);
	$groupe = $app['dao.groupe']->find($id);

   return $app['twig']->render('adhgroup.html.twig', array('adherents' => $adherents, 'groupe' => $groupe, 'nbadh' => $nbadh));
})->bind('groupe');

$app->get('/logged/groupe-list/', function () use ($app) {
	$groupes = $app['dao.groupe']->findAll();

   return $app['twig']->render('groupeliste.html.twig', array('groupes' => $groupes));
})->bind('groupe-list');

$app->match('/logged/groupe-add/', function (Request $request) use ($app) {
   $groupe = new AdhGroupe();
	
	$groupeFormView = null;
	if ($app['security']->isGranted('IS_AUTHENTICATED_FULLY')) {
		$groupeForm = $app['form.factory']->create(new AdhGroupeType(), $groupe);
		$groupeForm->handleRequest($request);
		if ($groupeForm->isSubmitted() && $groupeForm->isValid()) {
			$app['dao.groupe']->save($groupe);
			$app['session']->getFlashBag()->add('success', 'Le groupe a bien été crée');
		}
		$groupeFormView = $groupeForm->createView();
	}
	return $app['twig']->render('adhgroupeform.html.twig', array('groupe' => $groupe, 'groupeForm' => $groupeFormView));
	
})->bind("groupe-add");

$app->match('/logged/groupe-mod/{id}', function ($id, Request $request) use ($app) {
   $groupe = $app['dao.groupe']->find($id);
	
	$groupeFormView = null;
	if ($app['security']->isGranted('IS_AUTHENTICATED_FULLY')) {
		$groupeForm = $app['form.factory']->create(new AdhGroupeType(), $groupe);
		$groupeForm->handleRequest($request);
		if ($groupeForm->isSubmitted() && $groupeForm->isValid()) {
			$app['dao.groupe']->save($groupe);
			$app['session']->getFlashBag()->add('success', 'Le groupe a bien été modifié');
		}
		$groupeFormView = $groupeForm->createView();
	}
	return $app['twig']->render('adhgroupeform.html.twig', array('groupe' => $groupe, 'groupeForm' => $groupeFormView));
	
})->bind("groupe-mod");

$app->match('/logged/groupe-del/{id}', function ($id, Request $request) use ($app) {
	$groupe = $app['dao.groupe']->find($id);
	$adherents = $app['dao.adherent']->findByGroupe($id);

	if (count($adherents) != '0') {	
		$app['session']->getFlashBag()->add('danger', 'Impossible de supprimer un groupe dans lequel il reste des adhérents');
	} else {
		$app['dao.groupe']->del($groupe);
		$app['session']->getFlashBag()->add('info', 'Suppression réussie.');
	}
	
	return $app->redirect($app["url_generator"]->generate("groupe-list"));
	
})->bind("groupe-del");

$app->get('/logged/adm/user-list', function () use ($app) {
	$users = $app['dao.user']->findAll();
	$adherents = $app['dao.adherent']->findAll();

   return $app['twig']->render('userlist.html.twig', array('users' => $users, 'adherents' => $adherents));
})->bind('user-list');

$app->match('/logged/adm/user-add/', function (Request $request) use ($app) {
   $user = new User();
   $adherents = $app['dao.adherent']->findAll();
	
	$userFormView = null;
	if ($app['security']->isGranted('IS_AUTHENTICATED_FULLY')) {
		$userForm = $app['form.factory']->create(new UserType($adherents, false), $user);
		$userForm->handleRequest($request);
		if ($userForm->isSubmitted() && $userForm->isValid()) {
			$userlist = $app['dao.user']->findByUsername($user->getUsername());

			if (count($userlist) != '0') {	
				$app['session']->getFlashBag()->add('danger', 'Le nom d\'utilisateur est déja pris');
			} else {
				if ($user->getIsAdh() == 0) {
					$user->setIdAdh(0);
				}						
				
				$plainPassword = $user->getPassword();
				if (empty($plainPassword)) {
					$app['session']->getFlashBag()->add('danger', 'Pas de mot de passe définit !!');
				} else {
					$salt = substr(md5(time()), 0, 23);
					$user->setSalt($salt);
				
					$encoder = $app['security.encoder.digest'];
    	    		// compute the encoded password
     	   		$password = $encoder->encodePassword($plainPassword, $user->getSalt());
    	    		$user->setPassword($password);
				
					$app['dao.user']->save($user, true);
				}
				$app['session']->getFlashBag()->add('success', 'L\'utilisateur a bien été crée');
			}
		}
		$userFormView = $userForm->createView();
	}
	return $app['twig']->render('userform.html.twig', array('user' => $user, 'userForm' => $userFormView, 'usernow' => false));
	
})->bind("user-add");

$app->match('/logged/adm/user-mod/{id}', function ($id, Request $request) use ($app) {
   $user = $app['dao.user']->find($id);
   $adherents = $app['dao.adherent']->findAll();
	
	$userFormView = null;
	if ($app['security']->isGranted('IS_AUTHENTICATED_FULLY')) {
		$userForm = $app['form.factory']->create(new UserType($adherents, false), $user);
		$userForm->handleRequest($request);
		if ($userForm->isSubmitted() && $userForm->isValid()) {

			if ($user->getIsAdh() == 0) {
				$user->setIdAdh(0);
			}						
				
			$plainPassword = $user->getPassword();
			if (empty($plainPassword)) {
				$app['dao.user']->save($user, false);
			} else {
				$salt = substr(md5(time()), 0, 23);
				$user->setSalt($salt);
				
				$encoder = $app['security.encoder.digest'];
    	    		// compute the encoded password
     	   	$password = $encoder->encodePassword($plainPassword, $user->getSalt());
    	    	$user->setPassword($password);
				
				$app['dao.user']->save($user, true);
			}
				$app['session']->getFlashBag()->add('success', 'L\'utilisateur a bien été modifié');
			}
		}
		$userFormView = $userForm->createView();
	return $app['twig']->render('userform.html.twig', array('user' => $user, 'userForm' => $userFormView, 'usernow' => false));
	
})->bind("user-mod");

$app->match('/logged/myuser-mod', function (Request $request) use ($app) {
	$id = $app['security']->getToken()->getUser()->getId();
   $user = $app['dao.user']->find($id);
   $adherents = $app['dao.adherent']->findAll();
	
	$userFormView = null;
	if ($app['security']->isGranted('IS_AUTHENTICATED_FULLY')) {
		$userForm = $app['form.factory']->create(new UserType($adherents, true), $user);
		$userForm->handleRequest($request);
		if ($userForm->isSubmitted() && $userForm->isValid()) {

			if ($user->getIsAdh() == 0) {
				$user->setIdAdh(0);
			}						
				
			$plainPassword = $user->getPassword();
			if (empty($plainPassword)) {
				$app['dao.user']->save($user, false);
			} else {
				$salt = substr(md5(time()), 0, 23);
				$user->setSalt($salt);
				
				$encoder = $app['security.encoder.digest'];
    	    		// compute the encoded password
     	   	$password = $encoder->encodePassword($plainPassword, $user->getSalt());
    	    	$user->setPassword($password);
				
				$app['dao.user']->save($user, true);
			}
				$app['session']->getFlashBag()->add('success', 'L\'utilisateur a bien été modifié');
			}
		}
		$userFormView = $userForm->createView();
	return $app['twig']->render('userform.html.twig', array('user' => $user, 'userForm' => $userFormView, 'usernow' => true));
	
})->bind("myuser-mod");

$app->match('/logged/adm/user-del/{id}', function ($id, Request $request) use ($app) {
	$user = $app['dao.user']->find($id);
	
	
	$app['dao.user']->del($user);
	
	return $app->redirect($app["url_generator"]->generate("user-list"));
	
})->bind("user-del");

$app->match('/logged/cot-add/{idadh}', function ($idadh, Request $request) use ($app) {
	$usernow = $app['security']->getToken()->getUser();
	$cotisation = new Cotisation();
	$cotisation->setAdhid($idadh);
	$cotisation->setAuteur($usernow->getId());
   $adherent = $app['dao.adherent']->find($idadh);
	
	$userFormView = null;
	if ($app['security']->isGranted('IS_AUTHENTICATED_FULLY')) {
		$cotisationForm = $app['form.factory']->create(new CotisationType(), $cotisation);
		$cotisationForm->handleRequest($request);
		if ($cotisationForm->isSubmitted() && $cotisationForm->isValid()) {
			$cotisation->setDate(date("Y-m-d H:i:s"));
			$app['dao.cotisation']->save($cotisation);
			$app['session']->getFlashBag()->add('success', 'Cotisation ajoutée');
		}
		$cotisationFormView = $cotisationForm->createView();
	}
	return $app['twig']->render('cotisationform.html.twig', array('adherent' => $adherent, 'cotisation' => $cotisation, 'cotisationForm' => $cotisationFormView));
	
})->bind("cot-add");

$app->match('/logged/cot-mod/{id}', function ($id, Request $request) use ($app) {
	$usernow = $app['security']->getToken()->getUser();
	$cotisation = $app['dao.cotisation']->find($id);
   $adherent = $app['dao.adherent']->find($cotisation->getAdhid());
	
	$userFormView = null;
	if ($app['security']->isGranted('IS_AUTHENTICATED_FULLY')) {
		$cotisationForm = $app['form.factory']->create(new CotisationType(), $cotisation);
		$cotisationForm->handleRequest($request);
		if ($cotisationForm->isSubmitted() && $cotisationForm->isValid()) {
			$cotisation->setDate(date("Y-m-d H:i:s"));
			$app['dao.cotisation']->save($cotisation);
			$app['session']->getFlashBag()->add('success', 'Cotisation modifiée');
		}
		$cotisationFormView = $cotisationForm->createView();
	}
	return $app['twig']->render('cotisationform.html.twig', array('adherent' => $adherent, 'cotisation' => $cotisation, 'cotisationForm' => $cotisationFormView));
	
})->bind("cot-mod");

$app->match('/logged/cot-del/{id}', function ($id, Request $request) use ($app) {
	$cotisation = $app['dao.cotisation']->find($id);
	
	
	$app['dao.cotisation']->del($cotisation);
	
	return $app->redirect($app["url_generator"]->generate("adherent", array('id' => $cotisation->getAdhid())));
	
})->bind("cot-del");
