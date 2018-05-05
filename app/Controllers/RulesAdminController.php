<?php

namespace App\Controllers;

use Illuminate\Database\Query\Builder;
use App\Models\VariableDefinition;
use App\Models\VariableGroup;

class RulesAdminController
{
    protected $ci;
    protected $_logger;
    protected $_db;

    //Constructor
    public function __construct(\Slim\Container $ci)
    {
        $this->ci       = $ci;
        $this->_logger  = $this->ci->get('logger');
        $this->_db      = $this->ci['db'];
    }
    public function fetchCompanies($request, $response)
    {
        $companies = $this->_db->table('company')->get();
        $result['success'] = 1;
        $result['message'] = $companies;
        return $response->withJson($result);
    }

    public function addVariable($request, $response)
    {
        $postData = json_decode($request->getParsedBody()['json'], true);
        if ($postData['varId'] != 0) {
            //edit mode
            try {
                $varDefination = $this->_db->table('variable_definition')
                                            ->where('variable_id', $postData['varId'])
                                            ->update([
                                                'variable_name' => $postData['varName'],
                                                'variable_description' => $postData['varDesc'],
                                                'variable_type' => $postData['varType'],
                                                'default_value' => $postData['varDefaultValue'],
                                            ]);
                $result['success'] = 1;
                $result['message'] = "Variable Saved Sucessfully!";
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {
            //check if the variable already exists
            $varDefination = $this->_db->table('variable_definition')
                                    ->where('variable_name', $postData['varName'])
                                    ->where('company_id', $postData['varCompanyId'])
                                    ->first();

            if (isset($varDefination) && count($varDefination) >=1) {
                $result['success'] = 0;
                $result['message'] = "Variable name already exists!";
            } else {
                try {
                    $varDefination = new VariableDefinition;
                    $varDefination->variable_name = $postData['varName'];
                    $varDefination->variable_description = $postData['varDesc'];
                    $varDefination->variable_type = $postData['varType'];
                    $varDefination->default_value = $postData['varDefaultValue'];
                    if ($postData['variableType'] == "mem") {
                        $varDefination->system_var = 1;
                    }
                    if ($postData['variableType'] == "in") {
                        $varDefination->system_var = 0;
                    }
                    if ($postData['variableType'] == "out") {
                        $varDefination->system_var = 2;
                    }
                    $varDefination->company_id = $postData['varCompanyId'];
                    $varDefination->save();
                    $result['success'] = 1;
                    $result['message'] = "Variable Saved Sucessfully!";
                } catch (Exception $e) {
                    $result['success'] = 0;
                    $result['message'] = $e->getMessage();
                }
            }
        }

        return $response->withJson($result);
    }
   

    public function deleteVariable($request, $response)
    {
        $postData = json_decode($request->getParsedBody()['json'], true);
        
        if ($postData['varId'] != 0) {
            try {
                $varDefination = $this->_db->table('variable_definition')
                                            ->where('variable_id', '=', $postData['varId'])
                                            ->where('company_id', '=', $postData['cid'])
                                            ->delete();
                $result['success'] = 1;
                $result['message'] = "Variable Deleted Sucessfully!";
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['message'] = $e->getMessage();
            }
        } else {
            $result['success'] = 0;
            $result['message'] = "Variable Not Found";
        }

        return $response->withJson($result);
    }
        

    public function getVariable($request, $response)
    {
        $postData = $request->getQueryParams();
        try {
            $query = $this->_db->table('variable_definition');
            if ($postData['varID']) {
                $query->where('variable_id', '=', $postData['varID']);
            } else {
                $result['success'] = 0;
                $result['message'] = "Variable ID missing or invalid";
                return $response->withJson($result);
            }
            if ($postData['cid']) {
                $query->whereIn('company_id', [$postData['cid']]);
            } else {
                $result['success'] = 0;
                $result['message'] = "Company ID missing or invalid";
                return $response->withJson($result);
            }

            $variables = $query->get();
            if (isset($variables) && count($variables) >=1) {
                $result['success'] = 1;
                $result['message'] = $variables;
            } else {
                $result['success'] = 0;
                $result['message'] = "Variable not found";
            }
        } catch (Exception $e) {
            $result['success'] = 0;
            $result['message'] = $e->getMessage();
        }
        return $response->withJson($result);
    }

    
}
