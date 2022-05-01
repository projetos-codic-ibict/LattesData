<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Checklist extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'checklists';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

function index($d1,$d2,$d3)
		{
			
			$sx = h('Checklist',1);
			if (strlen($d1) > 0)
				{
					$sx .= h('dataverse.checklist_'.$d1,4);
				}
			
			$cmd = '';
			
			$file = false;
			switch($d1)
				{
					case 'copyright':
						$sx .= '<code>curl -X PUT -d ", CNPq/Ibict" http://localhost:8080/api/admin/settings/:FooterCopyright</code>';
						break;
                    default:
                        $sx .= $this->checklist();
                        break;
                }
			return $sx;
		}    

        function checklist()
            {


                $item = array();
                $item['postgresql'] = 'postgresql_install';
                $item['solr'] = 'solr_install';
                $item['DOI'] = 'doi_install';
                $item['email'] = 'email_install';
                if (get("action") != '')
                {
                foreach($item as $key => $value)
                    {
                        if (get($key) == '1')
                            {
                                $_SESSION['checklist'][$key] = $value;
                            } else {
                                $_SESSION['checklist'][$key] = '';
                            }
                        
                    }                
                }

                $sx = '';
                $sx .= form_open();
                $sx .= '<table class="table table-sm table-striped">';
                $sx .= '<tr>';
                $sx .= '<th width="3%">'.lang('dataverse.checked').'</th>';
                $sx .= '<th width="97%">'.lang('dataverse.description').'</th>';
                $sx .= '</tr>';

                foreach($item as $it=>$txt)
                    {
                        $sx .= '<tr>';
                        $sx .= '<td class="text-center">';
                        $sx .= $this->checked($it);
                        $sx .= '</td>';

                        $sx .= '<td>';
                        $sx .= h(lang('dataverse.checkit_'.$it),5);
                        $sx .= '</td>';

                        $sx .= '</tr>';
                    }
                $sx .= '</table>';
                $sx .= form_submit(array('name'=>'action','class'=>'btn btn-outline-primary btn-sm','value'=>lang('dataverse.checklist_send')));
                $sx .= form_close();
                return $sx;
            }

            function checked($key='')
                {
                    $checked = '';
                    if (isset($_SESSION['checklist'][$key]))
                    {
                        $check = $_SESSION['checklist'][$key];
                        if ($check != '')
                            {
                                $checked = 'checked';
                            }
                    }
                    
                    $sx = '';
                    //$sx .= '<img src="'.URL.'img/icons/unchecked.png" width="30" height="30" border=0>';
                    $sx .= '<input type="checkbox" name="'.$key.'" value="1" '.$checked.'>';
                    return $sx;
                }
}
