<?php

require_once 'creatememberrelationships.civix.php';

/*Implementation of hook_civicrm_alterContent*/

function creatememberrelationships_civicrm_alterContent(  &$content, $context, $tplName, &$object){
  if ($context=='page'){
    if ($tplName == 'CRM/Member/Page/Tab.tpl'){
      if ($object->_action==2){
	            $marker1 = strpos($content, 'selector');    
	            $marker = strrpos(substr($content, 0, $marker1), '<fieldset'); 
	            $content1 = substr($content, 0, $marker);
	            $content3 = substr($content, $marker);
	            $memberid = $object->getVar('_id');
	            $cid = $object->_contactId;	 
	            $relationships = civicrm_api('Relationship', 'get', array('version' => 3, 'contact_id' => $cid));
	            $options = '';
	            foreach($relationships['values'] as $relationship){
	                $options .= '<option value='.$relationship['id'].'>'.$relationship['id'].'</option>';	                
	            }
	            $content2 = '
	            <script>
	                var relationship_id = cj("#relationship_id").val();
	                cj("#relationship_id").change(function(){
	                  relationship_id = cj(this).val();
	                  console.log(relationship_id);
	                });
	                cj("#new_mem_rel").submit(function(){
	                  relationship_create(relationship_id);
	                  return false;
	                });
	                
	                function relationship_create(relationship_id){

                    CRM.api("Relationship", "getSingle", {"sequential": 1, "id": relationship_id},
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
	              <input type="submit" class="validate form-submit" value="Create Relationship">
	            </form>
	           ';
	            $content = $content1.$content2.$content3;           
      }
    }
  }
}

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
