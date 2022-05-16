<?php

namespace App\Models\Dataverse;

use CodeIgniter\Model;

class Dataview extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'dataviews';
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

    function apache_dataview()
        {
            $sx = '<pre>
            nano /etc/apache2/sites-available/dataview.conf
            <VirtualHost *:8010>
                ServerName localhost
                DocumentRoot /var/www/dataverse/branding
                </VirtualHost>
            </pre>';

            $sx .= '<tt>nano /etc/apache2/ports.conf</tt>'.cr();
            $sx .= 'Listen 8010'.cr();
            return $sx;
        }

    function index($d1,$d2,$d3)
        {
            $sx = '';
            $sx .= h('Dataview',1);

            $sx .= '<code>mkdir /home/dataverse/dataview/</code>';
            $sx .= '<code>cd  /home/dataverse/dataview/</code>';
            $sx .= '<br>';
            $sx .= '<br>';
            $sx .= '<code>echo "COPIA Arquivos de configuração"</code>';
            $sx .= '<code>cp /data/LattesData/Datasets/Dataview/*.json .</code>';
            $sx .= '<br>';
            $sx .= '<br>';

            $sx .= '<code>curl -X POST -H \'Content-type: application/json\' http://localhost:8080/api/admin/externalTools --upload-file <b>file_config</b>.json</code>';
            return $sx;
        }

    function json()
        {
            $sx = '
            {
                "displayName": "Brapci DataView - Dataverse",
                "description": "Visualizado de dados",
                "toolName": "brapci_dataview",
                "scope": "file",
                "types": [
                  "explore",
                  "preview"
                ],
                "toolUrl": "http://pocdadosabertos.inep.rnp.br/dataview",
                "contentType": "text/tab-separated-values",
                "toolParameters": {
                  "queryParameters": [
                       {"fileid": "{fileId}"},
                       {"siteUrl":"{siteUrl}"},
                       {"PID": "{datasetPid}"},
                       {"key": "{apiToken}"},
                       {"datasetId": "{datasetId}"},
                       {"localeCode":"{localeCode}"}
                  ]
                }
            }';
            return $sx;
        }

    function PDFView()
        {
            $sx = "curl -X POST -H 'Content-type: application/json' http://localhost:8080/api/admin/externalTools -d \ ";
            $sx .= '"{ \"displayName\":\"Read Document\", \"description\":\"Read a pdf document.\", \"scope\":\"file\", \"type\":\"explore\", \"hasPreviewMode\":\"true\", \"toolUrl\":\"https://globaldataversecommunityconsortium.github.io/dataverse-previewers/previewers/PDFPreview.html\", \"toolParameters\": { \"queryParameters\":[ {\"fileid\":\"{fileId}\"}, {\"siteUrl\":\"{siteUrl}\"}, {\"key\":\"{apiToken}\"}, {\"datasetid\":\"{datasetId}\"}, {\"datasetversion\":\"{datasetVersion}\"}, {\"locale\":\"{localeCode}\"} ] }, \"contentType\":\"application/pdf\" }';
            $sx .= '';            
            return $sx;
        }
}
