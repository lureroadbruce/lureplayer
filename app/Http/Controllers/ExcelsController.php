<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\ExcelRepository;
use Input,Gate,Auth,Redirect;
class ExcelsController extends Controller
{
    protected $excel_gestion;
    public function __construct(
        ExcelRepository $excel_gestion
    )
    {
        $this->excel_gestion = $excel_gestion;
    }
    public function index()
    {
        return view('testQR');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function importView()
    {
        return view('excel_import');
    }
    public function excelImport(Request $request)
    {
        if($request->hasFile('file'))
        {
            $extension = $request->file('file')->getClientOriginalExtension();
            if($extension != 'xls' && $extension != 'xlsx')
            {
                return redirect()->back()->withInput()->withErrors(array('fail'=>'上传格式错误！'));
            }
            $rslt =$this->excel_gestion->import($request->file('file'));
            if($rslt['type']>0)
                return redirect()->back()->withInput()->withErrors(array('fail'=>$rslt['error']));
            return redirect()->back()->withErrors(array('fail'=>$rslt['error']));
        }
        else
        {
            return redirect()->back()->withInput()->withErrors(array('fail'=>'请上传文件！'));
        }
    }

}
