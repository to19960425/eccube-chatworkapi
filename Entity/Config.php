<?php

namespace Plugin\ChatworkApi\Entity;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Plugin\ChatworkApi\Entity\Config', false)) {
    /**
     * Config
     *
     * @ORM\Table(name="plg_chatwork_api_config")
     * @ORM\Entity(repositoryClass="Plugin\ChatworkApi\Repository\ConfigRepository")
     */
    class Config
    {
        /**
         * @var int
         *
         * @ORM\Column(name="id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var string
         *
         * @ORM\Column(name="api_key", type="string", length=255, nullable=true)
         */
        private $apiKey;

        /**
         * @var string
         *
         * @ORM\Column(name="room_id", type="string", length=255, nullable=true)
         */
        private $roomId;

        /**
         * @ORM\Column(name="enabled", type="boolean", options={"default": false})
         */
        private $enabled = false;

        /**
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @return string
         */
        public function getApiKey()
        {
            return $this->apiKey;
        }

        /**
         * @param string $apiKey
         *
         * @return $this;
         */
        public function setApiKey($apiKey)
        {
            $this->apiKey = $apiKey;

            return $this;
        }

        /**
         * @return string
         */
        public function getRoomId()
        {
            return $this->roomId;
        }

        /**
         * @param string $roomId
         *
         * @return $this;
         */
        public function setRoomId($roomId)
        {
            $this->roomId = $roomId;

            return $this;
        }

        /**
         * 通知を有効にするかどうかの設定を取得します。
         *
         * @return bool 通知が有効な場合は true、無効な場合は false
         */
        public function isEnabled(): bool
        {
            return $this->enabled;
        }

        /**
         * 通知を有効にするかどうかを設定します。
         *
         * @param bool $enabled 通知を有効にする場合は true、無効にする場合は false
         * @return self
         */
        public function setEnabled(bool $enabled): self
        {
            $this->enabled = $enabled;
            return $this;
        }
    }
}
