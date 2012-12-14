<?php

namespace Blog\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Blog\Model\BlogEntry;
use Blog\Model\BlogTable;
use Blog\Form\BlogForm;

class BlogController extends AbstractActionController
{
    protected $blogTable;
    protected $authService;
    // List all entries by default.
    public function indexAction()
    {
		if(null === $this->authService)
		{
			$this->authService = new AuthenticationService();
		}
		if($this->authService->hasIdentity())
			return new ViewModel(array(
				'entries' => $this->getBlogTable()->fetchAll(),
			));
		else
			return $this->redirect()->toRoute('zfcuser');
    }
    // Add a entry.
    public function addAction()
    {
        $form = new BlogForm();
        $form->get('submit')->setAttribute('value', 'Add');
        $request = $this->getRequest();
        if($request->isPost()){
            $blog = new BlogEntry();
            $form->setInputFilter($blog->getInputFilter());
            $form->setData($request->getPost());
            // spare the validation part for now.
            if ($form->isValid()){
                $blog->exchangeArray($form->getData());
                $this->getBlogTable()->saveBlog($blog);
                return $this->redirect()->toRoute('blog',array('action'=>'index'));
            }

        }

        $view = new ViewModel(array('form'=>$form));
        return $view;
    }
    public function readAction()
    {
        $id = (int)$this->params('id');
        if(!$id){
            return $this->redirect()->toRoute('blog',array('action'=>'add'));
        }
        $blogEntry = $this->getBlogTable()->getBlog($id);
        return new ViewModel(array('blogEntry'=>$blogEntry));
    }
    public function editAction()
    {
        $id = (int)$this->params('id');
        if(!$id){
            return $this->redirect()->toRoute('blog',array('action'=>'add'));
        }
        $blogEntry = $this->getBlogTable()->getBlog($id);
        $title = $blogEntry->getArrayCopy()['title'];
        $content = $blogEntry->getArrayCopy()['body'];
        $form = new blogForm('edit_blog', $title, $content);
        $form->get('submit')->setAttribute('value', 'Add');
        return new ViewModel(array('form'=>$form, 'id'=>$id));
    }
    public function getBlogTable()
    {
        if(!$this->blogTable){
            $sm = $this->getServiceLocator();
            $this->blogTable = $sm->get('Blog\Model\BlogTable');
        }
        return $this->blogTable;
    }
}
