<?php

namespace PrakashSinghGaur\PollBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PrakashSinghGaur\PollBundle\Entity\Question;
use Symfony\Component\BrowserKit\Response;
use \DateTime;

class QuestionController extends Controller
{
    /**
     * @Route("/pollmanage", name="poll_list")
     */
    public function indexAction(Request $request)
    {

        $questions = $this->getDoctrine()->getRepository('PrakashSinghGaurPollBundle:Question')->findAll();
        return $this->render('PrakashSinghGaurPollBundle:Question:index.html.twig', array(
            'questions' => $questions,
        ));
    }

    /**
     * @Route("/pollmanage/question/add", name="question_add")
     */
    public function createAction(Request $request)
    {

    	$question = new Question();
        $activeUser = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createFormBuilder($question)
            ->add('title')
            ->add('description', 'textarea')
            ->add('status')
            ->add('save','submit')
            ->getForm();
                
	     $form->handleRequest($request);
	     if ($form->isSubmitted() && $form->isValid()) {
        	    $em = $this->getDoctrine()->getManager();
                $question->setCreatedBy($activeUser);
                $question->setCreatedOn(new \DateTime('now'));
			    $em->persist($question);
    			$em->flush();
                $this->addFlash(
                'notice',
                'Poll Question Created'
                );

                 return $this->redirectToRoute('poll_list');
    }

        return $this->render('PrakashSinghGaurPollBundle:Question:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/pollmanage/question/edit/{id}", name="question_edit")
     */
    public function editAction($id,Request $request){

        $question = $this->getDoctrine()->getRepository('PrakashSinghGaurPollBundle:question')->find($id);

        $activeUser = $this->get('security.token_storage')->getToken()->getUser();

        if(!$question->getCreatedBy() == $activeUser){
            throw $this->createAccessDeniedException('You are not owner of this question');
        }

        $question->setTitle($question->getTitle());

        $form = $this->createFormBuilder($question)
                ->add('title')
                ->add('description', 'textarea')
                ->add('status')
                ->add('save','submit')
                ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $title = $form['title']->getData();
            $em = $this->getDoctrine()->getManager();
            $question->setTitle($title);            
            $em->flush();

            $this->addFlash(
                'notice',
                'Question Updated'
                );

            return $this->redirectToRoute('poll_list');
        }

        return $this->render('PrakashSinghGaurPollBundle:Question:edit.html.twig', array(
            'question' => $question,
            'form' => $form->createView()

            ));
    }

    /**
     * @Route("/pollmanage/question/delete/{id}", name="question_delete")
     */
    public function deleteAction($id, Request $request){


        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('PrakashSinghGaurPollBundle:Questions')->find($id);

        $activeUser = $this->get('security.token_storage')->getToken()->getUser();

        if(!$question->getCreatedBy() == $activeUser){
            throw $this->createAccessDeniedException('You are not owner of this Poll');
        }


            $em->remove($question);
            $em->flush();

            $this->addFlash(
                    'notice',
                    'Poll Question Deleted'
                    );
            return $this->redirectToRoute('poll_list');
    }

    /**
     * @Route("/pollmanage/question/results/{id}", name="question_results")
     */
    public function resultsAction($id, Request $request){

        $question = $this->getDoctrine()->getRepository('PrakashSinghGaurPollBundle:question')->find($id);

        $answers = $this->getDoctrine()->getRepository('PrakashSinghGaurPollBundle:Answer')
                ->findBy(array('question'=>$question));


        return $this->render('PrakashSinghGaurPollBundle:Answer:report.html.twig', array(
            'question' => $question,
            'answers' => $answers,

            ));

}

}
