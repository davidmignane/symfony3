<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;




class TodoController extends Controller
{
    /**
     * Lists all todo entities.
     *
     * @Route("/", name="todo_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $tod = $em->getRepository('AppBundle:Todo')->findAll();
        $todos= $this->get('knp_paginator')->paginate($tod,$request->query->get('page',1),6);

        return $this->render('todo/index.html.twig', array
        (
            'todos' => $todos,
        ));
    }
    /**
     * Creates a new todo entity.
     *
     * @Route("/new", name="todo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $todo = new Todo();
        $form = $this->createForm('AppBundle\Form\TodoType', $todo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $file = $todo->getUrl();
             // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
             // Move the file to the directory where brochures are stored
            $file->move
            (
                $this->getParameter('image_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $todo->setUrl($fileName);

            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_show', array('id' => $todo->getId()));
        }

        return $this->render('todo/new.html.twig', array
        (
            'todo' => $todo,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a todo entity.
     *
     * @Route("/{id}",name="todo_show")
     * @Method("GET")
     */
    public function showAction(Todo $todo)
    {
        $deleteForm = $this->createDeleteForm($todo);
        return $this->render('todo/show.html.twig', array
        (
            'todo' => $todo,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing todo entity.
     *
     * @Route("/{id}/edit",name="todo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Todo $todo)
    {
        $deleteForm = $this->createDeleteForm($todo);
        $editForm = $this->createForm('AppBundle\Form\TodoType', $todo);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) 
        {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('todo_index');
        }
        return $this->render('todo/edit.html.twig', array
            (
            'todo' => $todo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            ));
    }

    /**
     * Displays a form to edit an existing todo entity.
     *
     * @Route("/{id}/editImage",name="todo_editImage")
     * @Method({"GET", "POST"})
     */
    public function editImageAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $todo= $em->getRepository('AppBundle:Todo')->findBy($id);
        
        if ($editForm->isSubmitted() && $editForm->isValid()) 
        {
            $this->flush();
            return $this->redirectToRoute('todo_index');
        }
        return $this->render('todo/edit.html.twig', array
            (
            'todo' => $todo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            ));
    }
    /**
     * Deletes a todo entity.
     *
     * @Route("/{id}",name="todo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Todo $todo)
    {
        $form = $this->createDeleteForm($todo);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($todo);
            $em->flush();
        }
        return $this->redirectToRoute('todo_index');
    }
    /**
     * Creates a form to delete a todo entity.
     *
     * @param Todo $todo The todo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Todo $todo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('todo_delete', array('id' => $todo->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
