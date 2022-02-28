<?php

namespace App\Guestbook;

use App\Guestbook\Message;

    class GuestBook{

        private $fichier = null;

        public function __construct($fichier)
        {   
            $directory = dirname($fichier);
            if(!is_dir($directory)){
                mkdir($directory, 0777, true);
            }
            if(file_exists($fichier)){
                touch($fichier);
            }

            $this->fichier = $fichier;
        }

        public function addMessage(Message $message): void
        {
            file_put_contents($this->fichier, $message->toJSON() . PHP_EOL, FILE_APPEND);
        }
        
        public function getMessages(): array
        {
            $content = trim(file_get_contents($this->fichier));
            return array_reverse(file($this->fichier));  

        }

    }