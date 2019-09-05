-------------------------------------------------------------------
UPDATING PHPLITEADMIN
-------------------------------------------------------------------
 
Replace the main phpliteadmin.php file, then replace this statement:

new Authorization(); 
 
with

new DummyAuthorization();
