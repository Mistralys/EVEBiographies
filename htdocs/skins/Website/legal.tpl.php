<?php

namespace EVEBiographies;

require_once 'Skins/Skin/Template/Frontend.php';

class Template_Website_legal extends Skins_Skin_Template_Frontend
{
    protected function _renderContent()
    {
        ob_start();
        
        ?>
        	<h2 id="contents_visibility"><?php pt('Visibility of contents') ?></h2>
        	<p>
        		<?php 
        		    pts('The published biographies are not publicly visible:');
        		    pts('To read them, users must sign in with an existing EVE Online account.');
        		    pts('None of the publicly accessible pages contain any EVE Online related material.');
        		?>
        	</p>
        	<h2 id="content_policy"><?php pt('Content policy') ?></h2>
        	<p>
        		<?php 
        		  pts(
        		      '%1$s is a free service intended to be a useful and fun extension to your EVE Online gameplay.',
        		      Website::getName()
    		      );
        		  pts('We would only ask you to respect a few guidelines when writing your biographies.');
        		  pts('We will remove any content if it matches any of the following criteria:');
        		?>
        	</p>
        	<ul>
        		<li><?php pt('Contains illegal content') ?></li>
        		<li><?php pt('Is Involuntary pornography') ?></li>
        		<li><?php pt('Is sexual or suggestive content involving minors') ?></li>
        		<li><?php pt('Threatens, harasses or bullies, or encourages others to do so') ?></li>
        		<li><?php pt('Is personal or confidential information') ?></li>
        		<li><?php pt('Impersonates someone in a misleading or deceptive manner') ?></li>
        		<li><?php pt('Uses %1$s to solicit or facilitate any transaction or gift involving out of game goods and services', Website::getName()) ?></li>
        		<li><?php pt('Is spam') ?></li>
        	</ul>
        	<h2 id="privacy_policy"><?php pt('Privacy policy') ?></h2>
        	<p>
        		<?php 
        		    pts('%1$s collects no personal user data whatsoever.', Website::getName()); 
        		    pts('Through EVE Online\'s single sign on service, only a character\'s ID and ingame name are stored in the local database.');
        		    pts('At no point in the communication with the EVE Online servers is any information accessible on the person owning the EVE Online account, thus guaranteeing complete anonymity.');
        		?>
        	</p>
        	<p>
        		<?php 
        		    pts('The character name is used to be able to access the character\'s biography, the ID is only used internally.');
        		    pts('The character name and its biography text are stored indefinitely, until the user chooses to remove it, or %1$s decides to stop its service.', Website::getName());
        		    pts('Only the EVE Online character name and the biography text the user enter are shared publicly on the website.');
        		?>
        	</p>
        	<p>
        		<?php 
        		    pts('Cookie usage:'); pts('%1$s uses session cookies to allow users to log in and edit their biography text.', Website::getName());
        		    pts('Data safety:'); pts('The database is subject to regular backups, and cannot be accessed externally.');
        		    pts('Policy changes:'); pts('Changes to this privacy policy will be announced on %1$s\'s landing page.', Website::getName());
        		?>
        	</p>
        	<h2 id="content_age_rating"><?php pt('Age rating for contents') ?></h2>
        	<p>
        		<?php 
        		  pts('The published biographies are only accessible using EVE Online\'s single sign on service.');
        		  pts('As a result, %1$s inherits EVE Online\'s age rating of PEGI 13.', Website::getName());
        		  pts('Any EVE character posting a biography to %1$s thus automatically fulfills the age requirements to read other users\' biographies, or post their own biography.', Website::getName());
        		?>
        	</p>
        	<h2 id="inappropriate_content"><?php pt('Reporting inappropriate content') ?></h2>
            <p>
            	<?php 
            	   pts(
            	       'If you spot any user generated content on the %1$s website that you think breaches any intellectual property rights, please use the report link on the target biography page to alert the moderators.', 
            	       Website::getName()
        	       );
            	   pts(
            	       'The same link can be used to report offensive or inappropriate content that violates %1$s\' content policy as described %2$shere%3$s.',
            	       Website::getName(),
            	       '<a href="#content_policy">',
            	       '</a>'
        	       );
            	   pts('Sometimes things slip through, but we will always act swiftly to remove unauthorised or inappropriate material.');
            	?>
            </p>
            <h2 id="content_moderation"><?php pt('Content moderation') ?></h2>
            <p>
            	<?php 
            	   pts(
            	       'All content posted on %1$s is subject to moderation by our staff.', Website::getName()
        	       );
            	   pts('We reserve the right to remove any content we deem inappropriate according to our content policy, without warning.');
            	   pts(
            	       'Repeat offenders will be banned from using the %1$s services altogether.', 
            	       Website::getName()
        	       );
            	   pts(
        	           'For any recourse or additional information on banned accounts, please contact %1$s.',
            	       '<a href="mailto:'.APP_EMAIL_LEGAL.'">'.APP_EMAIL_LEGAL.'</a>'
        	       );
            	?>
            </p>
            <h2 id="copyright_license"><?php pt('Copyright license grant to us') ?></h2>
            <p>
            	<?php
            	   pts('We need the legal right to to host, publish and share your biography.');
            	   pts('You grant us and our legal successors the right to store, parse and display your biography, and make incidental copies to render the website and provide the service.');
            	   pts('This includes the right to do things like copy it to our database and make backups;');
            	   pts('show it to you or those you choose to show it to;'); 
            	   pts('parse it into a search index or otherwise analyze it on our servers;');
            	   pts('share it with other users you choose to share it with.');
    	       ?>
    	       </p>
    	       <p>
    	       </p>
    	       <p>
    	       	<?php 
            	   pts(
            	       'This license does not grant %1$s the right to sell your content or otherwise distribute or use it outside of the service.', 
            	       Website::getName()
    	           );
            	  pts('You own all intellectual property in your own original content you contribute.');
            	  pts('You must not publish or contribute any content not originally created by you, or any content which is not properly licensed to you by someone else for uploading or contributing.');
        	  ?>
            </p>
            <h2 id="idemnification"><?php pt('Indemnification') ?></h2>
            <p>
            	<?php 
            	   pts(
            	       'You agree to defend, indemnify, and hold harmless %1$s and each of their respective staff from any and all claims, liabilities, costs, and expenses, including, but not limited to, attorneys’ fees and expenses, arising out a breach by you or any user of your account of these terms and conditions or privacy policy or arising out of a breach of your obligations, representation and warranties under these terms and conditions.', 
            	       Website::getName()
        	       );
            	
            	?>
            </p>
            <h2 id="contact"><?php pts('Contacting the staff') ?></h2>
            <p>
            	<?php 
            	    pts(
            	        'You may contact the %1$s team at %2$s for general purpose requests or information.',
            	        Website::getName(),
            	        '<a href="mailto:'.APP_EMAIL_CONTACT.'">'.APP_EMAIL_CONTACT.'</a>'
        	        );
            	    
            	    pts(
            	       'To report inappropriate contents or copyright infringements, use %1$s.',
            	        '<a href="'.APP_EMAIL_LEGAL.'">'.APP_EMAIL_LEGAL.'</a>'
        	        );
            	?>
            </p>
            <h2 id="ccpcopy">CCP Copyright Notice</h2>
            <p>
				<?php 
				    pts('EVE Online and the EVE logo are the registered trademarks of CCP hf.'); 
				    pts('All rights are reserved worldwide.');
				    pts('All other trademarks are the property of their respective owners.');
				    pts('EVE Online, the EVE logo, EVE and all associated logos and designs are the intellectual property of CCP hf.');
				    pts('All artwork, screenshots, characters, vehicles, storylines, world facts or other recognizable features of the intellectual property relating to these trademarks are likewise the intellectual property of CCP hf.');
				    pts('CCP hf. has granted permission to %1$s to use EVE Online and all associated logos and designs for promotional and information purposes on its website but does not endorse, and is not in any way affiliated with, %1$s.', APP_DOMAIN);
				    pts('CCP hf is in no way responsible for the content on or functioning of this website, nor can it be liable for any damage arising from the use of this website.');
			     ?>
            </p>
        <?php 
        
		return ob_get_clean();
	}
}        