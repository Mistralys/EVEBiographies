<?php

namespace EVEBiographies;

class Backgrounds_Background
{
    protected $url;
    
    protected $path;
    
    public function __construct($url, $path)
    {
        $this->url = $url;
        $this->path = $path;
    }
    
    public function getID()
    {
        return md5(basename($this->path));
    }
    
    public function getLabel()
    {
        $this->load();
        
        return $this->info['label'];
    }
    
    public function getCredits()
    {
        $this->load();
        
        return $this->info['credit'];
    }
    
    public function getLicense()
    {
        return $this->info['license'];
    }
    
    public function getLink()
    {
        $this->load();
        
        return $this->info['link'];
    }
    
    public function getURL()
    {
        return $this->url;
    }
    
    public function getThumbnailURL()
    {
        $thumbFile = $this->getThumbnailPath();
        if(!file_exists($thumbFile)) {
            $this->createThumbnail();
        }
        
        return APP_URL.'/cache/thumbs/'.md5($this->path.$this->thumbnailWidth).'.jpg';
    }
    
    public function getThumbnailPath()
    {
        return APP_ROOT.'/cache/thumbs/'.md5($this->path.$this->thumbnailWidth).'.jpg';
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function getBlendMode() : ?string
    {
        $this->load();
        
        if(isset($this->info['blend'])) {
            return $this->info['blend'];
        }
        
        return null;
    }
    
    public function hasBlendMode()
    {
        return $this->getBlendMode() !== null;
    }
    
    protected function load()
    {
        if(isset($this->info)) {
            return;
        }
        
        $path = $this->path;
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $path = str_replace('.'.$ext, '.txt', $path);
        
        if(file_exists($path)) 
        {
            $this->info = parse_ini_file($path, false);
        } 
        else 
        {
            $name = pathinfo($this->path, PATHINFO_FILENAME);
            $name = str_replace('-', ' ', $name);
            
            $this->info = array(
                'link' => APP_URL,
                'label' => ucwords($name),
                'credit' => 'AeonOfTime'
            );
        }
    }
    
    protected $thumbnailWidth = 420;
    
    protected function createThumbnail()
    {
        if(!defined('APP_OPTIMIZE_IMAGES')) {
            define('APP_OPTIMIZE_IMAGES', false);
        }
        
        $img = \AppUtils\ImageHelper::createFromFile($this->getPath());
        $img->setQuality(98);
        $img->resampleByWidth($this->thumbnailWidth);
        $img->save($this->getThumbnailPath());
    }
    
    public function getThumbTags()
    {
        $tags = array();
        
        if($this->hasBlendMode()) {
            $tags[] = array(
                'label' => t('Blended'),
                'title' => t('This background adjusts to the skin\'s color theme.')
            );
        }
        
        return $tags;
    }
}