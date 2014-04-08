<?php

namespace Stuart\HashtagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Stuart\HashtagBundle\Entity\Tweet;
use Symfony\Component\HttpFoundation\JsonResponse;
use Codebird\Codebird;
use Instagram\Instagram;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('StuartHashtagBundle:Default:index.html.twig', array());
    }
    
    public function viewAction($name)
    {
        $site = $this->getDoctrine()->getRepository('StuartHashtagBundle:Site')->findOneBySubdomain($name);
        
        $theme = "";
        if($site->getThemeId() != "") {
            $theme = $this->getDoctrine()->getRepository('StuartHashtagBundle:Theme')->find($site->getThemeId());
        }
        //set page vars
        $page = array(
            "title" => $site->getName(), 
            "hashtag" => $site->getHashtag(),
            "background" => $site->getBackgroundImage() == "" ? -1 : $site->getBackgroundImage(),
            "site" => $site->getId(),
            "theme" => $theme != "" ? $theme->getClass() : "empty"
            );
        
        return $this->render('StuartHashtagBundle:Default:view.html.twig', array('page' => $page));
    }
    
    public function pollAction($id)
    {
        $site = $this->getDoctrine()->getRepository('StuartHashtagBundle:Site')->find($id);

        Codebird::setConsumerKey('WQOK7uxRI60gsCTsHnAMw', 'wPlLms2qIeqUBCcdFo2vAOWCcPMUm3hzmgI43EzMs'); // static, see 'Using multiple Codebird instances'

        $cb = Codebird::getInstance();

        $reply = $cb->oauth2_token();
        $bearer_token = $reply->access_token;

        $reply = $cb->search_tweets('q='.$site->getHashtag().'&include_en&rpp=20&show_user=true&include_entities=true&with_twitter_user_id=true&result_type=recent', true);

        $response = array(
            'name' => $site->getName(),
            'hashtag' => $site->getHashtag(),
            'reply' => $reply,
        );
        return new JsonResponse($response);
    }    
    
    public function saveTweetAction(Request $request) {
        
        $tweet = $this->getDoctrine()->getRepository('StuartHashtagBundle:Tweet')->findByTweetId($request->get('tweet_id'));
        if($tweet) {
            $response = array(
                'exists' => 1
            );
        
            return new JsonResponse($response);
        }
        
        $body = $request->get('body');
        $tweetAuthor = $request->get('screen_name');
        $tweetAuthorPic = $request->get('profile_image');
        $tweetPic = $request->get('tweet_pic');
        $tweetId = $request->get('tweet_id');
        $tweetTime = $request->get('tweet_time');
        $siteId = $request->get('siteId');
        $tweetType = $request->get('tweet_type');
        
        
        $tweet = new Tweet();
        $tweet->setTweetBody($body);
        $tweet->setTweetAuthor($tweetAuthor);
        $tweet->setTweetAuthorPic($tweetAuthorPic);
        $tweet->setTweetId($tweetId);
        $tweet->setSiteId($siteId);
        $tweet->setTweetTime($tweetTime);
        $tweet->setTweetPic($tweetPic);
        $tweet->settweetType($tweetType);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($tweet);
        $em->flush();
                
                
        $response = array(
            'exists' => 0,
            'tweetId' => $tweet->getId()
        );
        
        return new JsonResponse($response);
    }
    
    public function getTweetsAction($id) {
        $tweets = $this->getDoctrine()->getRepository('StuartHashtagBundle:Tweet')->findBySiteId($id);
        $tweet_res = array();
        foreach($tweets as $tweet) {
            array_push($tweet_res, array(
                'tweetBody' => $tweet->getTweetBody(),
                'tweetAuthor' => $tweet->getTweetAuthor(),
                'tweetAuthorPic' => $tweet->getTweetAuthorPic(),
                'tweetTime' => $tweet->getTweetTime(),
                'tweetPic' => $tweet->getTweetPic() == "" ? "0" : $tweet->getTweetPic(),
                'tweetType' => $tweet->getTweetType()
            ));
        }
        shuffle($tweet_res);
        return new JsonResponse($tweet_res);
    }
    
    public function getInstaAction($id) {
        $auth_config = array(
            'client_id'         => 'bc7bb286c6834142af582ef9a6029279',
            'client_secret'     => '02bf67931e8b40509b463a63bd299733',
            'redirect_uri'      => 'http://hashtag.stuartfeldt.com',
            'scope'             => array( 'likes', 'comments', 'relationships' )
        );

        $auth = new \Instagram\Auth( $auth_config );
        $auth->authorize();
        return new JsonResponse(array('test' => $auth->getAccessToken($code)));
    }
}
