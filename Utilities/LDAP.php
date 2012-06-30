<?php
$ldapconfig['host'] = 'ldap.iitb.ac.in';
$ldapconfig['port'] = NULL;
$ldapconfig['basedn'] = 'ou=people,dc=iitb,dc=ac,dc=in';

function do_ldap_search($LDUSER) {	
	global $ldapconfig;

   $ds = @ldap_connect($ldapconfig['host'],$ldapconfig['port']);
   $r = @ldap_search( $ds, $ldapconfig['basedn'], 'uid=' . $LDUSER);
   if ($r) {
       $result = @ldap_get_entries( $ds, $r);
       if($result['count'] < 1)
       	return false;
       
       if ($result[0]['uid'][0] == $LDUSER) {
       	return $result[0];
       }
   }
   return false;
}

function ldap_authenticate($LDUSER, $LDPASS) {
	global $ldapconfig;

   $ds = @ldap_connect($ldapconfig['host'],$ldapconfig['port']);
   $r = @ldap_search( $ds, $ldapconfig['basedn'], 'uid=' . $LDUSER);
   if ($r) {
       $result = @ldap_get_entries( $ds, $r);
       if ($result[0]) {
           if (@ldap_bind( $ds, $result[0]['dn'], $LDPASS) ) {
               return true;
       	  }
       }
   }
   return false;
}
?>