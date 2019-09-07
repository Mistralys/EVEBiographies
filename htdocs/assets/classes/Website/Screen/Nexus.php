<?php

namespace EVEBiographies;

class Website_Screen_Nexus extends Website_Screen
{
    protected function _start()
    {
    }
    
    public function requiresAuthentication()
    {
        return true;
    }
    
    protected function getSkinID()
    {
        return 'Website';
    }
  
    public function getPageTitle()
    {
        return t('Browse biographies');
    }
    
    public function getNavigationTitle()
    {
        return t('Browse');
    }
    
    public function getDispatcher()
    {
        return 'nexus.php';
    }
  
    public function getPrettyDispatcher()
    {
        return 'nexus';
    }
    
    protected function _render()
    {
        $perPage = 6;
        $page = intval($this->request->getParam('page'));
        if($page < 1) {
            $page = 1;
        }
        
        $offset = ($page-1) * $perPage;
        
        $collection = $this->website->createBiographies();
        $data = $collection->getPublished($offset, $perPage);
        $total = $data['total'];
        $published = $data['items'];
        $pages = ceil($total / $perPage);
        
        $start = $offset;
        if($start === 0) {
            $start = 1;
        }
        
        $next = null;
        $nextPage = $page + 1;
        if($nextPage <= $pages) {
            $next = $this->getURL(array('page' => $nextPage));
        }
        
        $prev = null;
        $prevPage = $page - 1;
        if($prevPage > 0) {
            $prev = $this->getURL(array('page' => $prevPage));
        }
        
        $end = $page * $perPage;
        if($end > $total) {
            $end = $total;
        }
        
        $tpl = $this->skin->createTemplate('nexus');
        $tpl->addVar('biographies', $published);
        $tpl->addVar('pages', $pages);
        $tpl->addVar('total', $total);
        $tpl->addVar('start', $start);
        $tpl->addVar('end', $end);
        $tpl->addVar('url-next', $next);
        $tpl->addVar('url-prev', $prev);
        
        return $tpl->render();
    }
}