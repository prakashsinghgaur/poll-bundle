<?php

namespace PrakashSinghGaur\PollBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PrakashSinghGaur\PollBundle\Entity\Question;
use PrakashSinghGaur\PollBundle\Entity\Answer;
use Symfony\Component\BrowserKit\Response;
use \DateTime;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;   

class AnswerController extends Controller
{
    /**
     * @Route("/polls", name="polluser_list")
     */
    public function indexAction(Request $request)
    {

        $questions = $this->getDoctrine()->getRepository('PrakashSinghGaurPollBundle:Question')
                    ->findBy(array('status'=>1));
        return $this->render('PrakashSinghGaurPollBundle:Answer:index.html.twig', array(
            'questions' => $questions,
        ));
    }

    /**
     * @Route("/polls/question/{id}", name="opinion_poll")
     */
    public function userAction($id, Request $request)
    {

        $question = $this->getDoctrine()->getRepository('PrakashSinghGaurPollBundle:Question')->find($id);
        $activeUser = $this->get('security.token_storage')->getToken()->getUser();

        $answer = $this->getDoctrine()->getRepository('PrakashSinghGaurPollBundle:Answer')
                        ->findOneBy(array('question'=>$question, 'createdBy'=>$activeUser));

        if($answer){

            $answers = $this->getDoctrine()->getRepository('PrakashSinghGaurPollBundle:Answer')
                        ->findBy(array('question'=>$question));

            $reportCols = array('cols'=>array(
                                0=>array(
                                  'id'=>'','label'=>'Options','pattern'=>'','type'=>'string',),
                                1=>array(
                                  'id'=>'','label'=>'Answers','pattern'=>'','type'=>'number',),
                                )
                        );

            $results = array();
            $results['Yes'] = 0;
            $results['No'] = 0;
            $results['Not Sure'] = 0;

            if($answers){
            foreach($answers as $answer){
                if($answer->getYes()){
                    $results['Yes'] +=  1;
                }elseif($answer->getNo()){
                    $results['No'] += 1;
                }else{
                    $results['Not Sure'] += 1;
                }


            }
        }
        $reportRows = array();

        foreach($results as $key => $value){
          $reportRows['rows'][] = array(
                                    'c'=>array(
                                        0=>array('v'=>$key, 'f'=>NULL,),
                                        1=>array('v'=>$value,'f'=>NULL,),
                                            )
                                    );
      }

          $reportData = $reportCols + $reportRows;

          $encoder = new JsonEncoder();
          $normalizer = new ObjectNormalizer();
          $serializer = new Serializer(array($normalizer), array($encoder));
          $jsonData = $serializer->serialize($reportData, 'json');
          

            return $this->render('PrakashSinghGaurPollBundle:Answer:results.html.twig', array(
                'question' => $question,
                'answers' => $answers,
                'jsonData' => $jsonData,
            ));
        }

        $answer = new Answer();

        $form = $this->createFormBuilder($answer)
            ->add('yes')
            ->add('no')
            ->add('notSure')
            ->add('save','submit')
            ->getForm();
                
	     $form->handleRequest($request);
	     if ($form->isSubmitted() && $form->isValid()) {
        	    $em = $this->getDoctrine()->getManager();
                $answer->setQuestion($question);
                $answer->setCreatedBy($activeUser);
                $answer->setCreatedOn(new \DateTime('now'));
			    $em->persist($answer);
    			$em->flush();
                $this->addFlash(
                'notice',
                'Your opinion submitted'
                );

                 return $this->redirectToRoute('opinion_poll', array('id'=>$question->getId()));
    }

        return $this->render('PrakashSinghGaurPollBundle:Answer:form.html.twig', array(
            'question' => $question,
            'answer' => $answer,
            'form' => $form->createView(),
        ));
    }

}
