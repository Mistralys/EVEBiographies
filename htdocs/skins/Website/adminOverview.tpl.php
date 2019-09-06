<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend.php';

class Template_Website_adminOverview extends Skins_Skin_Template_Frontend
{
    protected function _renderContent()
    {
        ob_start();
        
        ?>
        	<h3><?php pt('Available admin functions') ?></h3>
        	<ul>
        		<li>
        			<a href="<?php echo APP_URL.'/storage/' ?>">
        				SQLite database admin
        			</a>
        		</li>
        	</ul>
        	<h3><?php pt('Registered administrator characters') ?></h3>
        	<table class="table table-hover">
        		<thead>
        			<tr>
        				<th><?php pt('Character') ?></th>
        				<th><?php pt('Email') ?></th>
        				<th class="align-center"><?php pt('Notifications?') ?></th>
        			</tr>
        		</thead>
        		<tbody>
					<?php 
					   $admins = Website::getAdmins();
					   foreach($admins as $admin) {
					       ?>
					       	<tr>
					       		<td><?php echo $admin->getName() ?></td>
					       		<td><?php echo $admin->getEmail() ?></td>
					       		<td class="align-center"><?php 
					       		  if( $admin->hasNotifications()) 
					       		  {  
					       		      ?><i class="fa fa-check"></i><?php 
					       		  } 
					       		  else 
					       		  {  
					       		      ?><i class="fa fa-times"></i><?php 
					       		  } 
				       		   ?></td>
					       	</tr>
					       <?php 
					   }
					?>	        		
        		</tbody>
        	</table>
        <?php
        
        return ob_get_clean();
    }
}