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
        private $api_key;

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
            return $this->api_key;
        }

        /**
         * @param string $api_key
         *
         * @return $this;
         */
        public function setApiKey($api_key)
        {
            $this->api_key = $api_key;

            return $this;
        }
    }
}
