<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class PA_Field extends Model
{
    protected $DBGroup          = 'schema';
    protected $table            = 'dataverse_tsv_metadata';
    protected $primaryKey       = 'id_m';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'id_m','m_schema','m_name',
        'm_title','m_description','m_watermark',
        'm_fieldType','m_displayOrder','m_displayFormat',
        'm_advancedSearchField','m_allowControlledVocabulary','m_allowmultiples',
        'm_facetable','m_displayoncreate','m_required',
        'm_parent','m_termURI'
    ];

    protected $typeFields    = [
        'hidden','string:100','string:100',
        'string:100','string:100','string:100',
        'string:100','[1-100]','string:100',
        'string:100','sn','sn',
        'sn','sn','sn',
        'string:100','string:100'
    ];    

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

    function editar($id)
        {
            $this->id = $id;
            $this->path = PATH.'datafieldEd/'.$id;
            $this->path_back = PATH;
            $sx = form($this);

            $sx = bs(bsc($sx,12));
            return $sx;
        }

    function viewid($id)
    {
        $dt = $this->where('m_schema', $id)->orderBy('m_displayOrder')->findAll();
        $sx = '<table class="table table-sm table-striped">';
        $sx .= '<thead>';
        $sx .= '<tr>';
        $sx .= '<th width="2%">#</th>';
        $sx .= '<th width="15%">Name</th>';
        $sx .= '<th width="15%">Title</th>';
        $sx .= '<th width="30%">Descript</th>';
        $sx .= '<th width="10%">Watermark</th>';
        $sx .= '<th width="10%">Type</th>';
        $sx .= '<th width="10%">FormatView</th>';
        $sx .= '<th width="2%"><a href="#" class="link-secondary" title="' . lang('Dataverse.m_advancedSearchField') . '">ASF</a></th>';
        $sx .= '<th width="2%"><a href="#" class="link-secondary" title="' . lang('Dataverse.m_allowControlledVocabulary') . '">VC</a></th>';
        $sx .= '<th width="2%"><a href="#" class="link-secondary" title="' . lang('Dataverse.m_allowmultiples') . '">MUL</a></th>';
        $sx .= '<th width="2%"><a href="#" class="link-secondary" title="' . lang('Dataverse.m_facetable') . '">FTB</a></th>';
        $sx .= '<th width="2%"><a href="#" class="link-secondary" title="' . lang('Dataverse.m_displayoncreate') . '">DSC</a></th>';
        $sx .= '<th width="2%"><a href="#" class="link-secondary" title="' . lang('Dataverse.m_required') . '">R</a></th>';
        $sx .= '<th width="2%"><a href="#" class="link-secondary" title="' . lang('Dataverse.m_termURI') . '">URL</a></th>';
        $sx .= '<th width="2%"><a href="#" class="link-secondary" title="' . lang('Dataverse.edit') . '">ED</a></th>';
        $sx .= '</tr>';
        $sx .= '</thead>';

        for ($r = 0; $r < count($dt); $r++) {
            $ln = $dt[$r];
            $link = '<a href="'.PATH.MODULE.'/viewfieldid/'.$ln['id_m'].'">';
            $linka = '</a>';
            $link = '';
            $linka = '';
            $sx .= '<tr>';
            $sx .= '<td>' . ($r + 1) . '</td>';
            $sx .= '<td>' . $link.$ln['m_name'] .$linka. '</td>';
            $sx .= '<td>' . $ln['m_title'] . '</td>';
            $sx .= '<td>' . $ln['m_description'] . '</td>';
            $sx .= '<td>' . $ln['m_watermark'] . '</td>';
            $sx .= '<td  width="10%">' . $ln['m_fieldType'] . '</td>';
            $sx .= '<td  width="10%">' . $ln['m_displayFormat'] . '</td>';
            $sx .= '<td>' . $this->onoff($ln['m_advancedSearchField']) . '</td>';
            $sx .= '<td>' . $this->onoff($ln['m_allowControlledVocabulary']) . '</td>';
            $sx .= '<td>' . $this->onoff($ln['m_allowmultiples']) . '</td>';
            $sx .= '<td>' . $this->onoff($ln['m_facetable']) . '</td>';
            $sx .= '<td>' . $this->onoff($ln['m_displayoncreate']) . '</td>';
            $sx .= '<td>' . $this->onoff($ln['m_required']) . '</td>';
            //$sx .= '<td>'.$ln['m_parent'].'</td>';
            if ($ln['m_termURI'] != '') {
                $sx .= '<td><a href="' . $ln['m_termURI'] . '" class="link-secondary" target="_blank">' . bsicone('url', 16) . '</a></td>';
            } else {
                $sx .= '<td>-</td>';
            }
            $sx .= '<td>' . '<a href="' . PATH . MODULE . '/datafieldEd/' . $ln['id_m'].'">' . bsicone('edit', 16) . '</a></td>';
            $sx .= '</tr>';
        }
        $sx .= '</table>';

        $sx = bs(bsc($sx, 12));
        return $sx;
    }

    function onoff($v)
    {
        if (($v == true) or ($v == '1') or ($v == '1')) {
            $sx = bsicone('on');
        } else {
            $sx = bsicone('off');
        }
        return $sx;
    }
    function TrueFalse($v)
    {
        if (($v == 'TRUE') or ($v == 'true')) {
            return 1;
        }
        return 0;
    }

    function import($id, $ln)
    {
        $ln = troca($ln, chr(9), ';');
        $col = explode(';', $ln);

        $tcol = count($col);
        if ($tcol < 15) {
            echo '<pre>';
            echo '===========>' . count($col) . '<hr>';
            print_r($col);
            print_r($d);
            echo '</pre>';
        }

        $d['m_schema'] = $id;
        $d['m_name'] = $col[0];
        $d['m_title'] = $col[1];
        $d['m_description'] = $col[2];
        $d['m_watermark'] = $col[3];
        $d['m_fieldType'] = $col[4];
        $d['m_displayOrder'] = $col[5];
        $d['m_displayFormat'] = $col[6];
        $d['m_advancedSearchField'] = $this->TrueFalse($col[7]);
        $d['m_allowControlledVocabulary'] = $this->TrueFalse($col[8]);
        $d['m_allowmultiples'] = $this->TrueFalse($col[9]);
        $d['m_facetable'] = $this->TrueFalse($col[10]);
        $d['m_displayoncreate'] = $this->TrueFalse($col[11]);
        $d['m_required'] = $this->TrueFalse($col[12]);
        $d['m_parent'] = $col[14];
        if (isset($col[15])) {
            $d['m_termURI'] = $col[15];
        } else {
            $d['m_termURI'] = '';
        }
        $d['m_active'] = 1;

        $dt = $this->where('m_schema', $id)->where('m_name', $d['m_name'])->findAll();
        if (count($dt) == 0) {
            $idn = $this->insert($d);
        } else {
            $idn = $dt[0]['id_m'];
            $idn = $this->set($d)->where('id_m', $idn)->update();
        }
        /*
            echo '<pre>';
            print_r($col);
            print_r($d);
            echo '</pre>';
                exit;
            */
        return $idn;
    }
}
