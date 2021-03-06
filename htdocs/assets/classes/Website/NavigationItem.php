<?php

namespace EVEBiographies;

class Website_NavigationItem
{
    protected $nav;
    
    protected $url;
    
    protected $label;
    
    protected $adminOnly;
    
    public function __construct(Website_Navigation $nav, $url, $label, $adminOnly=false)
    {
        $this->nav = $nav;
        $this->url = $url;
        $this->label = $label;
        $this->adminOnly = $adminOnly;
    }
    
    public function getURL()
    {
        return $this->url;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    
    public function isActive()
    {
        $infoMine = parse_url($this->getURL());
        $infoReq = parse_url($_SERVER['REQUEST_URI']);
        
        if($infoMine['path'] == $infoReq['path']) {
            return true;
        }
        
        return false;
    }
    
    public function isAdminOnly() : bool
    {
        return $this->adminOnly;
    }
}