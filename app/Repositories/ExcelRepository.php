<?php namespace App\Repositories;

use Auth,storage,Excel;
class ExcelRepository extends BaseRepository{


    public function import($file)
    {
        $result = array('type'=>0,'error'=>'操作成功');
        $log = $this->log_gestion->createLog($file,1);
        // $this->log_gestion->addLog($log,trans('error.10002'));
        $progs = Excel::load($file)->sheet(0)->toArray();
        $tag = $progs[0];
        unset($progs[0]);

        $KEY = array('act'=>array_search('操作',$tag),
                     'name'=>array_search('用户名',$tag),
                     'email'=>array_search('邮箱',$tag),
                     'pwd'=>array_search('密码',$tag),
                     'realname'=> array_search('姓名',$tag),
                     'role'=> array_search('角色',$tag),
                     'department'=> array_search('组织ID',$tag),
                     'status'=> array_search('状态',$tag),
                     'tel'=> array_search('电话',$tag),
                     'address'=> array_search('地址',$tag),
                     'PS'=> array_search('备注',$tag),
                     'sex'=> array_search('性别',$tag),
        );


        
        foreach($progs as $prog)
        {
            if(!empty($prog[array_search('操作',$tag)]))
            {
                $name[] = trim($prog[$KEY['name']]);
                $act[] = trim($prog[$KEY['act']]);
                $email[] = trim($prog[$KEY['email']]);
                $pwd[] = trim($prog[$KEY['pwd']]);
                $department[] = trim($prog[$KEY['department']]);
                $role[] = trim($prog[$KEY['role']]);
                $realname[]= trim($prog[$KEY['realname']]);
                $data[] = array(
                    'act'=>trim($prog[$KEY['act']]),
                    'name'=>$KEY['name']?trim($prog[$KEY['name']]):"",
                    'email'=>$KEY['email']?trim($prog[$KEY['email']]):"",
                    'pwd'=>$KEY['pwd']?trim($prog[$KEY['pwd']]):"",
                    'realname'=> $KEY['realname']?trim($prog[$KEY['realname']]):"",
                    'role'=> $KEY['role']?trim($prog[$KEY['role']]):"",
                    'department'=> $KEY['department']?trim($prog[$KEY['department']]):"",
                    'status'=> $KEY['status']?trim($prog[$KEY['status']]):"",
                    'tel'=> $KEY['tel']?trim($prog[$KEY['tel']]):"",
                    'address'=> $KEY['address']?trim($prog[$KEY['address']]):"",
                    
                    'PS'=> $KEY['PS']?trim($prog[$KEY['PS']]):"",
                    'sex'=> $KEY['sex']?trim($prog[$KEY['sex']]):"",
                );
            }
            else
            {
                $result['type'] = 1;
                $error = '请填写操作类型';
                $this->log_gestion->addLog($log,$error);
            }

        }
        if((empty($name)) || (empty($department)))
        {
            $result['type'] = 1;
            $error = trans('error.00058');
            $this->log_gestion->addLog($log,$error);
        }
        $error = $this->ifFormat($name);
        if(!empty($error))
        {
            $result['type'] = 1;
            $error = implode(",",$error).trans('validation.regex');
            $this->log_gestion->addLog($log,$error);
        }
        $error = $this->FetchRepeatMemberInArray($name);
        if(!empty($error))
        {
            $result['type'] = 1;
            $error = implode(",",$error).trans('error.00008');
            $this->log_gestion->addLog($log,$error);
        }
        $error = $this->FetchRepeatMemberInArray($email);
        if(!empty($error))
        {
            $result['type'] = 1;
            $error = implode(",",$error).trans('error.00009');
            $this->log_gestion->addLog($log,$error);
        }

        if( SystemInfo::first()->user_email)
        {
            $res = $this->checkName($name,$email);
            if($res['type'])
            {

                $result['type'] = 1;
                $error = $res['type']==1?implode(",",$res['error']).trans('error.00048'):trans('error.00050');
                $this->log_gestion->addLog($log,$error);
            }

            $res = $this->checkEmail($email,$name);
            if($res['type'])
            {

                $result['type'] = 1;
                $error = $res['type']==1?trans('error.00049').implode(",",$res['error']):trans('error.00051');
                $this->log_gestion->addLog($log,$error);
            }
        }
        $error = $this->ifNameFormat($realname);
        if(!empty($error))
        {
            $result['type'] = 1;
            $error = implode(",",$error).trans('error.00125');
            $this->log_gestion->addLog($log,$error);
        }
        $error = $this->ifFormatPWD($pwd);
        if(!empty($error))
        {
            $result['type'] = 1;
            $error = implode(",",$error).trans('error.00081');
            $this->log_gestion->addLog($log,$error);
        }
        $error = $this->checkDepartment($department);
        if(!empty($error))
        {
            $result['type'] = 1;
            $error = implode(",",$error).trans('error.00010');
            $this->log_gestion->addLog($log,$error);
        }
        


        $error = $this->checkRole($role);
        if(!empty($error))
        {
            $result['type'] = 1;
            $error = implode(",",$error).trans('error.00018');
            $this->log_gestion->addLog($log,$error);
        }
        if($result['type'])
        {
            $result['error'] =  trans('error.00012');
            $this->log_gestion->insertLog($log,trans('error.00121'));
            return $result;
        }
        $success = 0;
        foreach($data as $info)
        {
            switch($info['act'])
            {
            case 'A':
            case 'a':
                $user = $this->model->whereName($info['name'])->first();
                if($user)
                    $this->update($user->id,$info,true);
                else
                {
                    $res = $this->create($info,true);

                }
                $error = trans('error.00015').$info['name'];
                $this->log_gestion->addLog($log,$error);
                break;
            case 'D':
            case 'd':

            case 'C':
            case 'c':
                $user = $this->model->whereName($info['name'])->first();
                if($user)
                {
                    $this->closeUser($user->id);
                    $error = trans('error.00071').$info['name'];
                    $this->log_gestion->addLog($log,$error);
                }
            }
            $success++;
        }
        $error = $success.trans('table.people').trans('error.10000');
        $this->log_gestion->addLog($log,$error);
        return $result;
    }

}
