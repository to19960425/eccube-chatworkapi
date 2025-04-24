<?php

namespace Plugin\ChatworkApi;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Email;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Plugin\ChatworkApi\Service\ChatworkApiService;
use Plugin\ChatworkApi\Entity\Config;
use Plugin\ChatworkApi\Repository\ConfigRepository;
use Psr\Log\LoggerInterface;

class Event implements EventSubscriberInterface
{
    /**
     * @var ChatworkApiService
     */
    private $chatworkApiService;

    /**
     * @var ConfigRepository
     */
    private $configRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        ChatworkApiService $chatworkApiService,
        ConfigRepository $configRepository,
        LoggerInterface $logger
    ) {
        $this->chatworkApiService = $chatworkApiService;
        $this->configRepository = $configRepository;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EccubeEvents::MAIL_CONTACT => 'notifyChatworkOnMailSend',
            EccubeEvents::MAIL_ORDER => 'notifyChatworkOnMailSend',
        ];
    }

    public function notifyChatworkOnMailSend(EventArgs $event)
    {
        $Config = $this->configRepository->get();
        if ($Config->isEnabled() === false) {
            return;
        }

        /** @var \Symfony\Component\Mime\Email $message */
        $message = $event->getArgument('message');
        try {
            $this->chatworkApiService->addMessage($this->createChatworkText($message));
        } catch (\Throwable $e) {
            $this->logger->warning('Chatwork通知失敗: ' . $e->getMessage());
        }
    }

    private function createChatworkText(Email $message)
    {
        $text = $message->getTextBody() ?? '(本文なし)';
        $subject = $message->getSubject();
        $from = $message->getFrom()[0]->getAddress();

        $chatworkText = <<<EOM
        [info]
        [title]ECCUBEからの通知[/title]
        件名: {$subject}
        送信者: {$from}
        [hr] 
        本文:
        {$text}
        [/info]
        EOM;

        return $chatworkText;
    }
}
