<?php

namespace EVEBiographies;

class Mailer_Recipient
{
   /**
    * @var string
    */
    protected $email;
    
   /**
    * @var string
    */
    protected $name;
    
    public function __construct(string $email, string $name='')
    {
        $this->email = $email;
        $this->name = $name;
    }
    
    public function getName() : string
    {
        return $this->name;
    }
    
    public function getEmail() : string
    {
        return $this->email;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }
}