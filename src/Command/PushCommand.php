<?php

namespace App\Command;

use App\Repository\UserRepository;
use BenTools\WebPushBundle\Model\Message\PushNotification;
use BenTools\WebPushBundle\Model\Subscription\UserSubscriptionManagerRegistry;
use BenTools\WebPushBundle\Sender\PushMessageSender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PushCommand extends Command
{
    protected static $defaultName = 'app:push';
    /**
     * @var UserSubscriptionManagerRegistry
     */
    private $userSubscriptionManager;
    /**
     * @var PushMessageSender
     */
    private $sender;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        string $name = null,
        UserRepository $userRepository,
        UserSubscriptionManagerRegistry $userSubscriptionManager,
        PushMessageSender $sender
    ) {
        parent::__construct($name);
        $this->userSubscriptionManager = $userSubscriptionManager;
        $this->sender = $sender;
        $this->userRepository = $userRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('userId', InputArgument::REQUIRED, 'User id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $userId = (int) $input->getArgument('userId');
        $user = $this->userRepository->find($userId);

        if (null === $user) {
            $io->error('User with id '.$userId.' does not exist');
            return;
        }
        $subscriptions = $this->userSubscriptionManager->findByUser($user);
        $notification = new PushNotification(sprintf('Hello %s !', $user->getEmail()), [
            PushNotification::BODY => 'Push notifications works ! '.(new \DateTime())->format('H:i:s'),
            PushNotification::ICON => '/notification-icon.png',
        ]);

        $responses = $this->sender->push($notification->createMessage(), $subscriptions);

        foreach ($responses as $response) {
            if ($response->isExpired()) {
                $this->userSubscriptionManager->delete($response->getSubscription());
            }
        }

        $io->success('Notification pushed to '.$user->getEmail());
    }
}
