<?php
namespace App\Services;
use Helpers;

use App\Interfaces\ResourceInterface;
use Throwable;


class ResourceService implements ResourceInterface
{
    public function read($model,$data=null,$relations=null,$conditions=null){
        try {
            $query = $model::query();
            if($data != null){
                foreach ($data as $key => $value) {
                    $query->select($value);
                    // $query->select('column1','column2');
                }
            }

            if($relations != null){
                $query->with($relations);
                // $query->with('approver_ordinates','approver_ordinates.user');
            }

            if($conditions != null){
                foreach ($conditions as $key => $value) {
                    $query->where($key, $value);
                    // $query->where('column1'=>'1');
                    // $query->where('column2'=>'2');
                }
            }

            $query->whereNull('deleted_at');
            // return $model;
            return $query->get();
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
