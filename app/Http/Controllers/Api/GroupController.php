<?php

namespace App\Http\Controllers\Api;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GroupRequest;
use App\Http\Resources\Api\AlgResource;
use App\Http\Resources\Api\GroupResource;

class GroupController extends Controller
{
    public function index(GroupRequest $request)
    {
        if ($request->has('group_id')) {
            $groups = Group::whereHas('parents', function ($q) use ($request) {
                $q->where('parent_id', $request->input('group_id'));
            })->whereVisible()->get();
        } else {
            $groups = Group::has('parents', 0)->whereLeaf(false)->whereVisible()->get();
        }
        return GroupResource::collection($groups);
    }

    public function algs(Group $group)
    {
        $group->loadMissing('algs');
        return AlgResource::collection($group->algs);
    }
}
