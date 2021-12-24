<?php

namespace App\Http\Controllers\API\v1\Channel;

use App\Helpers\ChannelReFactory;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ChannelController extends Controller
{
    public function getAllChannelsList()
    {
        return response()->json(Channel::all(), Response::HTTP_OK);
    }

    /**
     * Create New Channel 
     * @param Request $request
     * @return JsonResponse
     */
    public function createNewChannel(Request $request)
    {
        $data = $request->validate([
            'name' => 'required'
        ]);
        $data["slug"] = Str::slug($data["name"]);
        resolve(ChannelReFactory::class)->create($data);
        return response()->json(['message' => 'channel created successfuly'], Response::HTTP_CREATED);
    }

    public function editChannel(Request $request, Channel $channel)
    {
        $data = $request->validate([
            'name' => 'required',
            'slug' => Str::slug($request->name)
        ]);
        $channel->update($data);
        return response()->json(['message' => 'channel updated successful'], Response::HTTP_OK);
    }

    public function deleteChannel(Channel $channel)
    {
        $channel->delete();
        return response()->json(['message' => 'the channel deleted successfuly'], Response::HTTP_OK);
    }
}
