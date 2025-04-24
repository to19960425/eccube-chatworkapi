<?php

namespace Plugin\ChatworkApi\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Plugin\ChatworkApi\Service\ChatworkApiService;
use Eccube\Controller\AbstractController;

class ApiTestController extends AbstractController
{
    private $chatworkApiService;

    public function __construct(
        ChatworkApiService $chatworkApiService
    ) {
        $this->chatworkApiService = $chatworkApiService;
    }

    /**
     * @Route("/%eccube_admin_route%/chatwork_api/test", name="chatwork_api_test")
     */
    public function testGetMe()
    {
        try {
            $response = $this->chatworkApiService->getMe();
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $data = $response->toArray();
                $this->addSuccess(sprintf('Chatworkとの接続に成功しました。アカウントID：%s', $data['account_id']), 'admin');
            } else {
                $this->addError("Chatwork APIエラー：" . trans($this->chatworkApiService->parseError($response)), 'admin');
            }
        } catch (\Exception $e) {
            $this->addError('Chatwork APIエラー: ' . $e->getMessage(), 'admin');
        }
        return $this->redirectToRoute('chatwork_api_admin_config');
    }

    /**
     * @Route("/%eccube_admin_route%/chatwork_api/test_room_info", name="chatwork_api_test_room_info")
     */
    public function testGetRoomInfo()
    {
        try {
            $response = $this->chatworkApiService->getRoomInfo();
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $data = $response->toArray();
                $this->addSuccess(sprintf('Chatworkとの接続に成功しました。ルームID：%s', $data['room_id']), 'admin');
            } else {
                $this->addError("Chatwork APIエラー：" . trans($this->chatworkApiService->parseError($response)), 'admin');
            }
        } catch (\Exception $e) {
            $this->addError('API呼び出しエラー: ' . $e->getMessage(), 'admin');
        }
        return $this->redirectToRoute('chatwork_api_admin_config');
    }

    /**
     * @Route("/%eccube_admin_route%/chatwork_api/test_message", name="chatwork_api_test_message")
     */
    public function testAddMessage()
    {
        try {
            $response = $this->chatworkApiService->addMessage();
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                $this->addSuccess('テストメッセージを送信しました。', 'admin');
            } else {
                $this->addError("Chatwork APIエラー：" . trans($this->chatworkApiService->parseError($response)), 'admin');
            }
        } catch (\Exception $e) {
            $this->addError('API呼び出しエラー: ' . $e->getMessage(), 'admin');
        }
        return $this->redirectToRoute('chatwork_api_admin_config');
    }
}
