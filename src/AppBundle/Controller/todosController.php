<?php
namespace AppBundle\Controller;
use AppBundle\Entity\Todo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class todosController extends Controller
{
     /**
     * @Route("/old", name="login")
     */
    public function loginAction(Request $request)
    {
        //creation de formulaire pour le login
        $todo=new Todo;
        $form=$this->createFormBuilder($todo)
        //input username
        ->add('name',TextType::class,array('label'=>' ','attr'=>array('class'=>'input100','placeholder'=>'Username',
         'style'=>'margin-bottom:15px')))
        //input password
        ->add('category',TextType::class,array('label'=>' ','attr'=>array('class'=>'input100','placeholder'=>'Password', 'style'=>'margin-bottom:15px')))

        ->add('save',SubmitType::class,array('label'=>'connexion','attr'=>array('class'=>'btn btn-primary','style'=>'margin-bottom:15px')))
        ->getForm();
        $form->handleRequest($request);
        //redirection à la page login
        return $this->render('todo/login.html.twig',array('form'=>$form->createView()));
    }
  
    /**
     * @Route("/todos", name="todo_list")
     */
    public function listAction()
    {
        $todos=$this->getDoctrine()->getRepository('AppBundle:Todo')->findAll();
        return $this->render('todo/index.html.twig',array('todos'=>$todos));
    }
   /**
     * @Route("/todos/create", name="todo_create")
     */
    public function createAction(Request $request)
    {
        $todo=new Todo;
        $form=$this->createFormBuilder($todo)
        ->add('name',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('category',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('description',TextareaType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('priority',ChoiceType::class,array('choices'=>array('low'=>'low','normal'=>'normal'),'attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('due_date',DateTimeType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('save',SubmitType::class,array('label'=>'create Todo','attr'=>array('class'=>'btn btn-primary','style'=>'margin-bottom:15px')))
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //Get data
            $name=$form['name']->getData();
            $category=$form['category']->getData();
            $description=$form['description']->getData();
            $priority=$form['priority']->getData();
            $due_date=$form['due_date']->getData();
            $now=new\DateTime('now');

            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setDueDate($due_date);
            $todo->setName($name);
            $todo->setCreateDate($now);
            $em= $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            $this->addFlash(
                'notice',
                'Todo Added'
            );
            return $this->redirectToRoute('todo_list');
        }
        return $this->render('todo/create.html.twig',array('form'=>$form->createView()) );
    }
     /*
     * @Route("/todos/edit/{id}", name="todo_edit")
     */

  /*  public function editAction($id,Request $request)
    {
       $todo=$this->getDoctrine()->getRepository('AppBundle:Todo')->find($id);
        $now=new\DateTime('now');
      

       //text

        $form=$this->createFormBuilder($todo)
        ->add('name',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('category',TextType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('description',TextareaType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('priority',ChoiceType::class,array('choices'=>array('low'=>'low','normal'=>'normal'),'attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('due_date',DateTimeType::class,array('attr'=>array('class'=>'form-control','style'=>'margin-bottom:15px')))
        ->add('save',SubmitType::class,array('label'=>'update Todo','attr'=>array('class'=>'btn btn-primary','style'=>'margin-bottom:15px')))
        ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //Get data
            $name=$form['name']->getData();
            $category=$form['category']->getData();
            $description=$form['description']->getData();
            $priority=$form['priority']->getData();
            $due_date=$form['due_date']->getData();
            $now=new\DateTime('now');

             $em= $this->getDoctrine()->getManager();
             $todo=$em->getRepository('AppBundle:Todo')->find($id);
 

            $todo->setName($name);
            $todo->setCategory($category);
            $todo->setDescription($description);
            $todo->setDueDate($due_date);
            $todo->setPriority($priority);
            $todo->setCreateDate($now);
           
         
            $em->flush();
            $this->addFlash(
                'notice',
                'Todo Update'
            );
        return $this->redirectToRoute('todo_list'); 
            }
       //fin text

        return $this->render('todo/edit.html.twig',array('todo'=>$todo,'form'=>$form->createView()));
    
    }*/
    /**
     * @Route("/todos/details/{id}", name="todo_details")
     */
    public function detailsAction($id)
    {
         $todo=$this->getDoctrine()->getRepository('AppBundle:Todo')->find($id);
         return $this->render('todo/details.html.twig',array('todo'=>$todo));
    }
     /**
     * @Route("/todos/delete/{id}", name="todo_delete")
     */
    public function deleteAction($id)
    {       $em= $this->getDoctrine()->getManager();
            $todo=$em->getRepository('AppBundle:Todo')->find($id);
            $em->remove($todo);
            $em->flush();
            $this->addFlash('notice','Todo Removed');
            return $this->redirectToRoute('todo_list');
    }
}