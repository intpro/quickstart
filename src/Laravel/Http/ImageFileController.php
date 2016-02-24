<?php

namespace Interpro\QuickStorage\Laravel\Http;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Interpro\ImageFileLogic\Concept\Exception\ImageFileSystemException;
use Interpro\ImageFileLogic\Concept\Report;
use Interpro\QuickStorage\Concept\Command\Image\RefreshAllGroupImageCommand;
use Interpro\QuickStorage\Concept\Command\Image\RefreshBlockImageCommand;
use Interpro\QuickStorage\Concept\Command\Image\RefreshOneGroupImageCommand;
use Interpro\QuickStorage\Concept\Command\Image\UpdateAllGroupImageCommand;
use Interpro\QuickStorage\Concept\Command\Image\UpdateBlockImageCommand;
use Interpro\QuickStorage\Concept\Command\Image\UpdateOneGroupImageCommand;

class ImageFileController extends Controller
{

    public function updateBlockImage(Request $request, Report $report){

        $validator = Validator::make(
            $request->all(),
            [
                'block_name' => 'required',
                'image_name' => 'required'
            ]
        );

        if($validator->fails()){
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        try{

            $block_name = $request->input('block_name');
            $image_name = $request->input('image_name');

            $this->dispatch(
                new UpdateBlockImageCommand(
                    $block_name,
                    $image_name,
                    $request->file('imagefile')));

        }catch(ImageFileSystemException $imFlexc){

            return ['status'=>'error', 'error'=>$imFlexc->getMessage()];
        }

        $resp_arr = [];
        $resp_arr['status'] = 'OK';
        $resp_arr['prefix'] = $block_name.'_'.$image_name.'_0';
        $resp_arr['sizes']  = $report->getImageReport($block_name.'_'.$image_name.'_0');

        return $resp_arr;
    }

    public function updateGroupImage(Request $request, Report $report){

        $validator = Validator::make(
            $request->all(),
            [
                'block_name' => 'required',
                'group_name' => 'required',
                'group_id' => 'required',
                'image_name' => 'required'
            ]
        );

        if($validator->fails()){
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        try{
            $block_name = $request->input('block_name');
            $group_name = $request->input('group_name');
            $group_id = $request->input('group_id');
            $image_name = $request->input('image_name');

            $this->dispatch(
                new UpdateOneGroupImageCommand(
                    $block_name,
                    $group_name,
                    $image_name,
                    $group_id,
                    $request->file('imagefile')));

        }catch(ImageFileSystemException $imFlexc){

            return ['status'=>'error', 'error'=>$imFlexc->getMessage()];
        }

        $resp_name = $group_name.'_'.$image_name.'_'.$group_id;

        $resp_arr = [];
        $resp_arr['status'] = 'OK';
        $resp_arr['prefix'] = $resp_name;
        $resp_arr['sizes']  = $report->getImageReport($resp_name);

        return $resp_arr;
    }

    public function refreshBlockImage(Request $request, Report $report) {

        $validator = Validator::make(
            $request->all(),
            [
                'block_name' => 'required',
                'image_name' => 'required'
            ]
        );

        if($validator->fails()){
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        try{

            $block_name = $request->input('block_name');
            $image_name = $request->input('image_name');

            $this->dispatch(
                new RefreshBlockImageCommand(
                    $block_name,
                    $image_name
                ));

        }catch(ImageFileSystemException $imFlexc){

            return ['status'=>'error', 'error'=>$imFlexc->getMessage()];
        }

        $resp_arr = [];
        $resp_arr['status'] = 'OK';
        $resp_arr['prefix'] = $block_name.'_'.$image_name.'_0';
        $resp_arr['sizes']  = $report->getImageReport($block_name.'_'.$image_name.'_0');

        return $resp_arr;
    }

    public function refreshGroupImage(Request $request, Report $report){

        $validator = Validator::make(
            $request->all(),
            [
                'block_name' => 'required',
                'group_name' => 'required',
                'group_id' => 'required',
                'image_name' => 'required'
            ]
        );

        if($validator->fails()){
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        try{
            $block_name = $request->input('block_name');
            $group_name = $request->input('group_name');
            $group_id = $request->input('group_id');
            $image_name = $request->input('image_name');

            $this->dispatch(
                new RefreshOneGroupImageCommand(
                    $block_name,
                    $group_name,
                    $image_name,
                    $group_id));

        }catch(ImageFileSystemException $imFlexc){

            return ['status'=>'error', 'error'=>$imFlexc->getMessage()];
        }

        $resp_name = $group_name.'_'.$image_name.'_'.$group_id;

        $resp_arr = [];
        $resp_arr['status'] = 'OK';
        $resp_arr['prefix'] = $resp_name;
        $resp_arr['sizes']  = $report->getImageReport($resp_name);

        return $resp_arr;
    }





    public function updateGroupImageMass(Request $request, Report $report){

        $validator = Validator::make(
            $request->all(),
            [
                'block_name' => 'required',
                'group_name' => 'required',
                'image_name' => 'required'
            ]
        );

        if($validator->fails()){
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        try{
            $block_name = $request->input('block_name');
            $group_name = $request->input('group_name');
            $image_name = $request->input('image_name');

            $this->dispatch(
                new UpdateAllGroupImageCommand(
                    $block_name,
                    $group_name,
                    $image_name,
                    $request->file('imagefile')));

        }catch(ImageFileSystemException $imFlexc){

            return ['status'=>'error', 'error'=>$imFlexc->getMessage()];
        }

        $resp_arr = [];
        $resp_arr['status'] = 'OK';
        $resp_arr['images'] = $report->getImageReportAll();

        return $resp_arr;
    }

    public function refreshGroupImageMass(Request $request, Report $report){

        $validator = Validator::make(
            $request->all(),
            [
                'block_name' => 'required',
                'group_name' => 'required',
                'image_name' => 'required'
            ]
        );

        if($validator->fails()){
            return ['status'=>'error', 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
        }

        try{
            $block_name = $request->input('block_name');
            $group_name = $request->input('group_name');
            $image_name = $request->input('image_name');

            $this->dispatch(
                new RefreshAllGroupImageCommand(
                    $block_name,
                    $group_name,
                    $image_name));

        }catch(ImageFileSystemException $imFlexc){

            return ['status'=>'error', 'error'=>$imFlexc->getMessage()];
        }

        $resp_arr = [];
        $resp_arr['status'] = 'OK';
        $resp_arr['images'] = $report->getImageReportAll();

        return $resp_arr;
    }


}

