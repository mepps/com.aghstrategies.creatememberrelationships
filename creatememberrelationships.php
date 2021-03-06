<?php

require_once 'creatememberrelationships.civix.php';

/*Implementation of hook_civicrm_alterContent*/

function creatememberrelationships_civicrm_alterContent(  &$content, $context, $tplName, &$object){
  if ($context=='page'){
    if ($tplName == 'CRM/Member/Page/Tab.tpl'){
      if ($object->_action==2){
            $marker1 = strpos($content, 'end_date');          
            $marker = strpos($content, 'is_override', $marker1);    
	            if ($marker == 0){
              $content1 = substr($content, 0, $marker);
              $content3 = substr($content, $marker);
              $memberid = $object->getVar('_id');
              $cid = $object->_contactId;

              $membership = civicrm_api('Membership', 'getSingle', array('version' => 3, 'id' => $memberid));	            
              $relationships = civicrm_api('Relationship', 'get', array('version' => 3, 'contact_id_a' => $cid));

              $options = ''; 
              $content2 = '';
              $related_mem = FALSE;
              $url = CRM_Utils_System::crmURL(array('p' => 'civicrm/ajax/rest?entity=Membership&action=create&debug=1&sequential=1&json=1'));

              if ($relationships['count'] > 0 and !array_key_exists('owner_membership_id', $membership)){
                foreach($relationships['values'] as $relationship){
                    $related_membership = civicrm_api('Membership', 'getsingle', array('version' => 3, 'membership_contact_id' => $relationship['contact_id_b'], 'membership_type_id' => $membership['membership_type_id']));	   
                    if (array_key_exists('id', $related_membership)){
                      $related_name = civicrm_api('Contact', 'getSingle', array('version' => 3, 'contact_id' => $relationship['contact_id_b']));
                      $relationship_name = civicrm_api('RelationshipType', 'getSingle', array('version' => 3, 'id' => $relationship['relationship_type_id']));	
                      $options .= '<option value='.$related_membership['id'].'>'.$relationship_name['name_a_b']. " ". $related_name['display_name'].'</option>';	     
                      $related_mem = TRUE;
                  }                 
                }
                if ($related_mem){
                  $content2 .= '
                  <script type="text/javascript">
                      var membership_id = cj("#member_id").val();
                      var relationship_id = cj("#relationship_id").val();
                      cj("#relationship_id").change(function(){
                        relationship_id = cj(this).val();
                      });
                      cj("#new_mem_rel").submit(function(){

                        if (confirm("This cannot be undone. Information will be rewritten. When you renew or save the parent membership, membership start and end dates as well as join dates will change. By default the don\'t overwrite custom fields box will be selected. Uncheck this if you want your custom data to inherit from the primary membership. Are you sure you want to assign a primary membership?")){
                            relationship_create(relationship_id, membership_id);
                            cj(this).slideUp();      
                            return false;             
                        }
                          else{
                            return false;
                          }
                      });
                      function relationship_create(relationship_id, membership_id){

                        CRM.api("Membership", "create", {"sequential": 1, "id": membership_id, "owner_membership_id": relationship_id, "owner_membership_custom_override": 1},
                          {success: function(data) {
                              cj.each(data, function(key, value) {console.log(key, value) });
                            }
                          }
                        );
                      }
                  </script>
                  <form id="new_mem_rel">
                    <select name="relationship_id" id="relationship_id">'.
                      $options
                    .'</select>
                    <input type="hidden" name="member_id" id="member_id" value='.$memberid.'>
                    <input type="submit" class="validate form-submit" value="Create Relationship">
                  </form>
                 ';
               }
             }
           $content = $content1.$content2.$content3;       
         }      
      }
    }
  }
}
#function creatememberrelationships_civicrm_buildForm( $formName, &$form ){
#  if ($form instanceof CRM_Contribute_Form_Search & $form->getVar('_action')==2){
#  
#  //  print_r($form->_elements);
#  }
#  //$form['end_date'];

#}
/**
 * Implementation of hook_civicrm_config
 */
function creatememberrelationships_civicrm_config(&$config) {
  _creatememberrelationships_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function creatememberrelationships_civicrm_xmlMenu(&$files) {
  _creatememberrelationships_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function creatememberrelationships_civicrm_install() {
  return _creatememberrelationships_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function creatememberrelationships_civicrm_uninstall() {
  return _creatememberrelationships_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function creatememberrelationships_civicrm_enable() {
  return _creatememberrelationships_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function creatememberrelationships_civicrm_disable() {
  return _creatememberrelationships_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function creatememberrelationships_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _creatememberrelationships_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function creatememberrelationships_civicrm_managed(&$entities) {
  return _creatememberrelationships_civix_civicrm_managed($entities);
}
