<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StartTweetPull
 *
 * @author stuartfeldt
 */

namespace Stuart\HashtagBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Server\IoServer;
use Stuart\HashtagBundle\TweetPull;

//not sure if this is right??
use Doctrine\Tests\Common\Annotations\Fixtures\Annotation\Autoload;

class StartTweetPullCommand extends ContainerAwareCommand {
    
    protected function configure()
    {
        $this
            ->setName('htme:start')
            ->setDescription('Greet someone')
            ->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Chat()
                )
            ),
            8080
        );

    $server->run();
    }
}

?>
