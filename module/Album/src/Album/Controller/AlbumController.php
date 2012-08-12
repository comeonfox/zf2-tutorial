<?php
//Footnotes: 
//1. m of p : member function of the parent class,
//   AbstractActionController here.
namespace Album\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Album\Form\AlbumForm;
use Zend\Authentication\AuthenticationService;
class AlbumController extends AbstractActionController
{
	protected $albumTable;
	protected $authService;
	public function indexAction()
	{
		if(null === $this->authService)
		{
			$this->authService = new AuthenticationService();
		}
		if($this->authService->hasIdentity())
			return new ViewModel(array(
				'albums' => $this->getAlbumTable()->fetchAll(),
			));
		else
			return $this->redirect()->toRoute('zfcuser');
	}
	public function addAction()
	{
		$form = new AlbumForm();
		$form->get('submit')->setAttribute('value', 'Add');
		$request = $this->getRequest(); //1
		if ($request->isPost()) {
			$album = new Album();
			$form->setInputFilter($album->getInputFilter());
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$album->exchangeArray($form->getData());
				$this->getAlbumTable()->saveAlbum($album);
				// Redirect to list of albums
				return $this->redirect()->toRoute('album');
				//Call the built-in plugin: redirect
			}
		}
		return array('form' => $form);

	}
	public function editAction()
	{
		$id = (int)$this->params('id');
		if(!$id){
			//Will this happen?
			return $this->redirect()->toRoute('album',array('action'=>'add'));
		}
		$album = $this->getAlbumTable()->getAlbum($id);

		$form = new AlbumForm();
		$form->bind($album);
		$form->get('submit')->setAttribute('value','Edit');
		$request = $this->getRequest();
		if($request->isPost()){
			//$form->setInputFilter($album->getInputFilter());
			$form->setData($request->getPost());
			//why don't set input filter first??
			//Maybe the bind() method has done this.
			if($form->isValid()){
			   //$album->exchangeArray($form->getData());
			   $this->getAlbumTable()->saveAlbum($album);
			   return $this->redirect()->toRoute('album');
			}
		}
		return array('form' => $form,
					 'id' => $id, 
					 );
	}
	public function deleteAction()
	{
		$id = (int)$this->params('id');
		if(!$id){
			throw new \Exception('Wrong id');
		}
		$this->getAlbumTable()->deleteAlbum($id);
		return $this->redirect()->toRoute('album');
	}
	public function upAction()
	{
		$aT = $this->getAlbumTable();
		$id = (int)$this->params('id');
		$idBak = $id;
		if(!$id){
			throw new \Exception('Wrong id');
		}
		do{
			$id--;
			if(!$id)
				return $this->redirect()->toRoute('album');
		}while(!$aT->getAlbum($id));
		$upper = $aT->getAlbum($id);
		$upper->id = $idBak;
		$belower = $aT->getAlbum($idBak);
		$belower->id = $id;
		$aT->saveAlbum($upper);
		$aT->saveAlbum($belower);
		return $this->redirect()->toRoute('album');
	}
	public function getAlbumTable()
	{
		if (!$this->albumTable) {
		  $sm = $this->getServiceLocator();//1
		  $this->albumTable = $sm->get('Album\Model\AlbumTable');
		  //$this->albumTable = new AlbumTable();
		}
	  return $this->albumTable;
	}

}
