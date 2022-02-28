<?php

    namespace App\Guestbook;

    use \DateTime;
    use \DateTimeZone;

    class Message{
        
        const LIMIT_USERNAME = 3;
        const LIMIT_MESSAGE = 10;

        public $username;
        public $message;
        public $date;

        public $pas_vide = true;
        
        public static function fromJSON(string $le_message_json = null): Message{
            $message_decode = json_decode($le_message_json, true);

            return new self($message_decode["username"], $message_decode["message"], new DateTime('@' . $message_decode["date"]));
        }

        public function __construct(string $username, string $message, ?DateTime $date = null)
        {
            $this->username = $username;
            $this->message = $message;
            $this->date = $date ?: new DateTime();
        }    

        public function isValid(): bool{
            if((int)strlen($this->message) > self::LIMIT_MESSAGE){
                $message_vide = str_replace("\n", "", $this->message);
                $message_vide = str_replace("\r", "", $message_vide);
                if(strlen($message_vide) === 0){
                    $this->pas_vide = false;
                }    
            }    
            return empty($this->getErrors()) && $this->pas_vide;
        }    

        public function getErrors(): array{
            $errors = [];

            if(strlen($this->username) <= self::LIMIT_USERNAME){
                $errors["username"] =  "<div class='invalid-feedback'>Your pseudo is too short.</div>";
            }    
            if(strlen($this->message) <= self::LIMIT_MESSAGE || $this->pas_vide == false){
                $errors["message"] = "<div class='invalid-feedback'>Your message is too short.</div>";
            }    
            
            return $errors;
        }    

        public function toHMTL(): string{

                $username = htmlentities($this->username);
                $message = nl2br(htmlentities($this->message));
                $this->date->setTimezone(new DateTimeZone("Europe/Paris"));
                $date = $this->date->format("d/m/Y à H:i");
            return "<p>    
                        <strong>{$username}</strong> <em>{$date}</em><br>
                        {$message}
                    </p>";    
        }            

        public function toJSON(): string{
            return (string)json_encode([
                "username" => $this->username, 
                "message" => $this->message,
                "date" => $this->date->getTimestamp()
            ]);    
        }    



    }