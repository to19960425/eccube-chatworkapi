<?php

namespace Plugin\ChatworkApi\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Plugin\ChatworkApi\Entity\Config;
use Plugin\ChatworkApi\Repository\ConfigRepository;
use WpOrg\Requests\Response;

class ChatworkApiService
{
    private $httpClient;
    private $configRepository;

    public function __construct(
        HttpClientInterface $httpClient,
        ConfigRepository $configRepository
    ) {
        $this->httpClient = $httpClient;
        $this->configRepository = $configRepository;
    }

    /**
     * 自分の情報を取得する
     *
     * https://api.chatwork.com/v2/me
     * 自分自身の情報を取得します。
     *
     * @return ResponseInterface
     */
    public function getMe(): ResponseInterface
    {
        $Config = $this->configRepository->get();
        return $this->httpClient->request('GET', 'https://api.chatwork.com/v2/me', [
            'headers' => [
                'X-ChatWorkToken' => $Config->getApiKey(),
            ],
        ]);
    }

    /**
     * チャットの情報を取得する
     * 
     * https://api.chatwork.com/v2/rooms/{room_id}
     * チャットの情報（名前、アイコン、種類など）を取得します。
     *
     * @return ResponseInterface
     */
    public function getRoomInfo(): ResponseInterface
    {
        $Config = $this->configRepository->get();
        return $this->httpClient->request('GET', 'https://api.chatwork.com/v2/rooms/' . $Config->getRoomId(), [
            'headers' => [
                'accept' => 'application/json',
                'X-ChatWorkToken' => $Config->getApiKey(),
            ],
        ]);
    }

    /**
     * チャットにメッセージを投稿する
     *
     * https://api.chatwork.com/v2/rooms/{room_id}/messages
     * チャットに新しいメッセージを投稿します。
     *
     * @return ResponseInterface
     */
    public function addMessage(?string $message = 'Hello, this is a test message.'): ResponseInterface
    {
        $Config = $this->configRepository->get();
        return $this->httpClient->request('POST', 'https://api.chatwork.com/v2/rooms/' . $Config->getRoomId() . '/messages', [
            'body' => [
                'self_unread' => 1,
                'body' => $message,
            ],
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/x-www-form-urlencoded',
                'X-ChatWorkToken' => $Config->getApiKey(),
            ],
        ]);
    }

    /**
     * Chatwork APIのエラーレスポンスからメッセージを抽出します。
     *
     * レスポンスがJSON形式で `{"errors":["メッセージ"]}` の構造であることを前提に、
     * 最初のエラーメッセージを返します。
     * JSONのパースに失敗、または 'errors' キーが存在しない場合は
     * デフォルトのメッセージを返します。
     *
     * @param \Symfony\Contracts\HttpClient\ResponseInterface $response
     *     エラーを含むChatwork APIのレスポンスオブジェクト
     *
     * @return string
     *     エラーメッセージ（またはデフォルトの説明メッセージ）
     */
    public function parseError(ResponseInterface $response): string
    {
        $data = json_decode($response->getContent(false), true);

        $errorMessage = $data['errors'][0] ?? 'エラー内容を取得できませんでした';
        return $errorMessage;
    }
}
