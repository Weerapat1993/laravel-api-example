<?php

namespace App\Http\Controllers;

use Validator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private function codeStatus($code)
    {
        switch ($code) {
            case 200: return 'OK';
            case 201: return 'Created';
            case 202: return 'Accepted';
            case 204: return 'No Content';
            case 400: return 'Bad Request';
            case 401: return 'Unauthorized';
            case 403: return 'Forbidden';
            case 404: return 'Not Found';
            case 500: return 'Internal Server Error';
            case 502: return 'Bad Gateway';
            case 503: return 'Service Unavailable';
            case 504: return 'Gateway Timeout';
            default: return 'Error';
        }
    }

    /**
     * response json `success` case
     * @param Number $code
     * @param Any $data
     * @return Object
     */
    public function getSuccess($code, $data)
    {
        return response()->json([
            'data' => $data,
            'code' => $code,
            'status' => $this->codeStatus($code),
        ]);
    }

    /**
     * response json `failure` case
     * @param Number $code
     * @param Any $data
     * @return Object
     */
    public function getFailure($code, $error)
    {
        return response()->json([
            'error' => $error,
            'code' => $code,
            'status' => $this->codeStatus($code),
        ]);
    }

    /**
     * Create `Validator`
     * 
     * @param Object $credentials
     * @param Object $rules
     * @return Validator
     */
    public function Validator($credentials, $rules)
    {
        $validator = Validator::make($credentials, $rules);
        return $validator;
    }

    // CRUD Function -------------------------------------------

    /**
     * Get `Primary Key`
     * @return String
     */
    public function pk()
    {
        if($this->isJoin) {
            $pk = $this->tableName.'.'.$this->primaryKey;
        } else {
            $pk = $this->primaryKey;
        }
        return $pk;
    }

    /**
     * Get Data Model By ID
     * @param Number $id
     * @return Object
     */
    public function dataByID($id)
    {
        $pk = $this->pk();
        $article = $this->Model()
                ->where($pk, $id)
                ->firstOrFail();
        return $article;
    }

    /**
     * Response Data Model Lists
     * @return Array
     */
    public function getList() {
        $articles = $this->Model()->get();
        return $this->getSuccess(200, $articles);
    }

    /**
     * Response Data Model By ID
     * @return Array
     */
    public function getByID($id, Request $request)
    {
        try {
            $article = $this->dataByID($id);
            return $this->getSuccess(200, $article);
        } catch(Exception $e) {
            return $this->getFailure(404, 'Data is not found.');
        }
        
    }

    /**
     * Delete Data By ID
     * @param Request $request
     * @return Object
     */
    public function delete(Request $request)
    {
        $credentials = $request->only('id');
        $rules = ['id' => 'required'];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return $this->getFailure(400, $validator->messages());
        }
        $id = $request->id;
        $pk = $this->pk();
        try {
            if($id) {
                $count = $this->Model()->where($pk, $id)->count();
                if($count) {
                    $this->Model()->where($pk, $id)->delete();
                    return $this->getSuccess(200, [ 'id' => $id ]);
                }
                return $this->getFailure(404, 'Data is not found.');
            }
            return $this->getFailure(400, 'ID is required.');
        } catch(Exception $e) {
            return $this->getFailure(500, $e->getMessage());
        }
    }
}
