<?php namespace App\Repositories;
use Auth;
use App\Models\User;
use App\Models\UserManage;
abstract class BaseRepository {

    /**
     * The Model instance.
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;
    protected $manage_model;
    protected $user_gestion;
    protected $organization_gestion;

    /**
     * Get number of records.
     *
     * @return array
     */
    public function getNumber()
    {
	$total = $this->model->count();

	$new = $this->model->whereSeen(0)->count();

	return compact('total', 'new');
    }
    public function getAll()
    {
        $models = $this->model->all();
        return $models;
    }

    /**
     * Destroy a model.
     *
     * @param  int $id
     * @return void
     */
    public function destroy($id)
    {
	$this->getById($id)->delete();
    }

    /**
     * Get Model by id.
     *
     * @param  int  $id
     * @return App\Models\Model
     */
    public function getById($id)
    {
	return $this->model->find($id);
    }

    public function getModifyModelList($user_id=null)
    {
        if($user_id)
        {
            $user = User::find($user_id);
        }
        else
        {
            $user = Auth::user();
        }
        foreach($user->manages as $manage)
        {
            $manageList[] = $manage->manage_id;
        }
        $manageArray = $this->organization_gestion->getUnderArray($manageList);
        $modelList = $this->manage_model->whereIn('manage_id',$manageArray)->lists('model_id')->all();
        $modelList = array_unique($modelList);
        return $modelList;
    }
    public function getUserModifyManageList($user_id=null)
    {
        if($user_id)
        {
            $user = User::find($user_id);
        }
        else
        {
            $user = Auth::user();
        }
        /*
        foreach($user->manages as $manage)
        {
            $manageList[] = $manage->manage_id;
            }*/
        $manageList = $user->manages()->lists('manage_id')->all();
        $manageArray = $this->organization_gestion->getUnderArray($manageList);
        
        return $manageArray;
    }

    


    public function getApplyModelList($user_id=null)
    {
        if($user_id)
        {
            $user = User::find($user_id);
        }
        else
        {
            $user = Auth::user();
        }
        foreach($user->manages as $manage)
        {
            $manageList[] = $manage->manage_id;
        }
        $manageList[] = $user->department_id;
        $manageArray = $this->organization_gestion->getUperArray($manageList);
        $modelList = $this->manage_model->whereIn('manage_id',$manageArray)->lists('model_id')->all();
        $modelList = array_unique($modelList);
        return $modelList;
    }

    public function getModifyUserList($data)
    {
        foreach($data->manages as $manage)
        {
            $manageList[] = $manage->manage_id;
        }
        $manageArray = $this->organization_gestion->getUperArray($manageList);
        $modelList = UserManage::whereIn('manage_id',$manageArray)->lists('model_id')->all();
        $modelList = array_unique($modelList);
        return $modelList;
    }
    public function getApplyUserList($data)
    {
        foreach($data->manages as $manage)
        {
            $manageList[] = $manage->manage_id;
        }
        $manageArray = $this->organization_gestion->getUnderArray($manageList);
        $modelList = UserManage::whereIn('manage_id',$manageArray)->lists('model_id')->all();
        $modelList = array_unique($modelList);
        return $modelList;
    }
    public function getApplyUserManageList($data)
    {
        /* foreach($data->manages as $manage)
        {
            $manageList[] = $manage->manage_id;
            }*/
        $manageList = $data->manages()->lists('manage_id')->all();
        $manageArray = $this->organization_gestion->getUnderArray($manageList);
        return $manageArray;
    }


    public function getUserApplyList($user_id=null)
    {
        if($user_id)
        {
            $user = User::find($user_id);
        }
        else
        {
            $user = Auth::user();
        }
        foreach($user->manages as $manage)
        {
            $manageList[] = $manage->manage_id;
        }
        $manageList[] = $user->department_id;
        $manageArray = $this->organization_gestion->getUperArray($manageList);
        return $manageArray;
    }


    public function setManageId($data,$manage_id,$user_id)
    {
        if(!empty($manage_id))
        {
            foreach($data->manages as $manage)
            {
                $manage->delete();
            }
            $manageIds = $this->organization_gestion->setManage($manage_id);
            foreach($manageIds as $manageId)
            {
                $manage = new $this->manage_model;
                $manage->model_id = $data->id;
                $manage->manage_id = $manageId;
                $manage->save();
            }
        }
        else
        {
            if($user_id)
            {
                $user = $this->user_gestion->getById($user_id);
                foreach($user->manages as $userManage)
                {
                    $manage = new $this->manage_model;
                    $manage->model_id = $data->id;
                    $manage->manage_id = $userManage->manage_id;
                    $manage->save();
                }
            }
        }
	
    }





}



