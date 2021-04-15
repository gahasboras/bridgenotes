<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Resources\UserPositionResource;
use App\Models\UserPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserPositionController extends BaseController
{
    public function index()
    {
        $userPosition = UserPosition::all();
        return $this->sendResponse(UserPositionResource::collection($userPosition), __('pagination.success_retrive'));
    }

    public function store(Request $request)
    {

        $data = $request->all();
        $validator = Validator::make($data, [
            'status' => 'required|in:active,inactive',
            'position' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_failed'), $validator->errors());
        }
        $data['user_id'] = Auth::user()->id;
        $userPosition = UserPosition::create($data);
        return $this->sendResponse(UserPositionResource::make($userPosition), __('validation.created_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $userPosition = UserPosition::where('user_id', $id)->get();
        return $this->sendResponse(new UserPositionResource($userPosition), __('validation.get_success'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'status' => 'required|in:active,inactive',
            'position' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_failed'), $validator->errors());
        }
        $userPosition = UserPosition::where('id', $id)->first();
        $userPosition->update($data);
        return $this->sendResponse(new UserPositionResource($userPosition), __('validation.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id, Request $request)
    {
        $userPosition = UserPosition::where('id', $id)->firstOrFail();
        $userPosition->delete();
        return $this->sendResponse([], __('validation.delete_success'));
    }
}
