<?php

/**
 * /AccountsPermissionsTable.php
 */

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * Accounts Permissions Model
 *
 * @since 1.0
 * @version 1.0
 * @author Dinh Van Huong
 */
class AccountsPermissionsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('accounts_permissions');
        $this->displayField('id');
        $this->primaryKey('id');
        
        $this->belongsToMany('AccountsGroups', [
            'foreignKey'       => 'perms_id',
            'targetForeignKey' => 'group_id',
            'joinTable'        => 'accounts_group_permissions'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator->notEmpty('controller');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['controller']));
        return $rules;
    }
    
    /**
     * delete all account permissions by id
     * 
     * @param array $ids
     * @return boolean
     * 
     * @since 1.0
     * @version 1.0
     * @author Dinh Van Huong
     */
    public function deleteAll($ids) 
    {
        $ok     = true;
        $conn   = ConnectionManager::get('default');
        $conn->begin();
        
        foreach ($ids as $id) {
            $entity = $this->get($id);
            if (!$this->delete($entity)) {
                $ok = false;
                break;
            }
        }
        
        if ($ok) {
            $conn->commit();
            return true;
        } else {
            $conn->rollback();
            return false;
        }
    }
    
}
