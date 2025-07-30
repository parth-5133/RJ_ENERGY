<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Helpers\ApiResponse;
use App\Helpers\AccessLevel;
use App\Constants\ResMessages;
use App\Http\Requests\StoreUpdateRoleRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreChannelPartnerRequest;
use App\Models\ChannelPartner;


class ChannelPartnersController extends Controller
{
    public function index()
    {
        $channelPartners = ChannelPartner::all();
        return ApiResponse::success($channelPartners, ResMessages::RETRIEVED_SUCCESS);
    }
    public function store(StoreChannelPartnerRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $Data = $request->all();
        $Data['created_by'] = $currentUser->id;

        if ($request->hasFile('logo_url')) {
            $Data['logo_url'] = '/storage/' . $request->file('logo_url')->store('channel_partners/logos', 'public');
        }

        $channelPartner = ChannelPartner::create($Data);

        return ApiResponse::success($channelPartner, ResMessages::CREATED_SUCCESS);
    }
    public function view(Request $request)
    {
        $channelPartnersId = $request->channelPartnersId;
        $channelPartner = ChannelPartner::find($channelPartnersId);
        if ($channelPartner) {
            return ApiResponse::success($channelPartner, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($channelPartner, ResMessages::NOT_FOUND);
        }
    }
    public function update(StoreChannelPartnerRequest $request)
    {
        $channelPartnersId = $request->channelPartnersId;

        $channelPartner = ChannelPartner::find($channelPartnersId);

        if (!$channelPartner) {
            return ApiResponse::error(ResMessages::NOT_FOUND, 404);
        }

        $channelPartner->fill($request->validated());
        $channelPartner->updated_at = now();

        if ($request->hasFile('logo_url')) {
            $channelPartner->logo_url = '/storage/' . $request->file('logo_url')->store('channel_partners/logos', 'public');
        }
        $channelPartner->save();

        return ApiResponse::success($channelPartner, ResMessages::UPDATED_SUCCESS);
    }
    public function delete($id)
    {
        $channelPartner = ChannelPartner::find($id);

        if ($channelPartner) {
            $channelPartner->delete();
            return ApiResponse::success($channelPartner, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($channelPartner, ResMessages::NOT_FOUND);
        }
    }
}
