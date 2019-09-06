<?php

namespace EVEBiographies;

class Website_AdminCharacter
{
    protected $charName;
    
    protected $email;
    
    protected $notifications;
    
    public function __construct(string $charName, string $email, bool $notifications)
    {
        $this->charName = $charName;
        $this->email = $email;
        $this->notifications = $notifications;
    }
    
    public function getName() : string
    {
        return $this->charName;
    }
    
    public function getEmail() : string
    {
        return $this->email;
    }
    
    public function hasNotifications() : bool
    {
        return $this->notifications;
    }
    
    public function enableNotification(bool $enable=true) : Website_AdminCharacter
    {
        $this->notifications = $enable;
        
        return $this;
    }
}