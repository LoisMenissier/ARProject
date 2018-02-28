<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Model;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ModelController extends Controller
{
	/**
	*@Route("/model", name="view_model_route")
	*/
	public function viewModelAction(){
		$models = $this->getDoctrine()->getRepository('AppBundle:Model')->findAll();
		return $this->render("pages/index.html.twig", ['models' => $models]);
	}

	/**
	*@Route("/model/create", name="create_model_route")
	*/
	public function createModelAction(Request $request){
		$model = new Model;
		$form = $this->createFormBuilder($model)
			->add('title', TextType::Class, array('attr' => array('class' => 'form-control')))
			->add('description', TextareaType::Class, array('attr' => array('class' => 'form-control')))
			->add('category', TextType::Class, array('attr' => array('class' => 'form-control')))
			->add('save', SubmitType::Class, array('label' => 'Create Model', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top: 10px')))
			->getForm();
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$title = $form['title']->getData();
			$description = $form['description']->getData();
			$category = $form['category']->getData();

			$model->setTitle($title);
			$model->setDescription($description);
			$model->setCategory($title);

			$em = $this->getDoctrine()->getManager();
			$em->persist($model);
			$em->flush();
			$this->addFlash('message', 'Model saved successfully!');
			return $this->redirectToRoute('view_model_route');
		}
		return $this->render("pages/create.html.twig", ['form' => $form->createView()]);
	}

	/**
	*@Route("/model/update/{id}", name="update_model_route")
	*/
	public function updateModelAction($id, Request $request){
		$model = $this->getDoctrine()->getRepository('AppBundle:Model')->find($id);
		$model->setTitle($model->getTitle());
		$model->setDescription($model->getDescription());
		$model->setCategory($model->getCategory());

		$form = $this->createFormBuilder($model)
			->add('title', TextType::Class, array('attr' => array('class' => 'form-control')))
			->add('description', TextareaType::Class, array('attr' => array('class' => 'form-control')))
			->add('category', TextType::Class, array('attr' => array('class' => 'form-control')))
			->add('save', SubmitType::Class, array('label' => 'Update Model', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top: 10px')))
			->getForm();
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$title = $form['title']->getData();
			$description = $form['description']->getData();
			$category = $form['category']->getData();

			$em = $this->getDoctrine()->getManager();
			$model = $em->getRepository('AppBundle:Model')->find($id);

			$model->setTitle($title);
			$model->setDescription($description);
			$model->setCategory($category);

			$em->flush();
			$this->addFlash('message', 'Model saved successfully!');
			return $this->redirectToRoute('view_model_route');
		}
		return $this->render("pages/update.html.twig", ['form' => $form->createView()]);
	}

	/**
	*@Route("/model/view/{id}", name="show_model_route")
	*/
	public function showModelAction($id){
		$model = $this->getDoctrine()->getRepository('AppBundle:Model')->find($id);
		return $this->render("pages/view.html.twig", ['model' => $model]);
	}

	/**
	*@Route("/model/delete/{id}", name="delete_model_route")
	*/
	public function deleteModelAction($id){
		$em = $this->getDoctrine()->getManager();
		$model = $em->getRepository('AppBundle:Model')->find($id);
		$em->remove($model);
		$em->flush();
		$this->addFlash('message', 'Model deleted successfully!');
		return $this->redirectToRoute('view_model_route');
	}
}
