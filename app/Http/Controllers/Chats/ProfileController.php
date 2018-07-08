<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chats\ChangePasswordPost;
use App\Http\Requests\Chats\UpdateProfileImgPost;
use App\Http\Requests\Chats\UpdateProfilePost;
use App\Library\ImgFileUpload;
use App\Models\Images;
use App\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Storage;
use Image;
use Illuminate\Support\Facades\Redis;


class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        $userArr = $user->toArray();
        $userArr['path'] = $user->images[0]->path;
        $userArr['chatID'] = $user->chatID;

    	return view('profile/index', ['authUser' => $userArr]);
    }

    public function updateProfile(UpdateProfilePost $request)
    {
    	$validated = $request->validated();

    	$validated['hide_account'] = empty($validated['hide_account'])?0:1;
    	$validated['password'] = Hash::make($validated['password']);

        $user = User::where('id', Auth::id())->first();

        $user->fill($validated)->save();

        $this->updateRedisUserinfo($user);

    	return redirect()->route('profile.profile')
    					->with('succeed', 'Succeed profile update')
    					->with('componentName', 'infoFormComp');
    }

    public function changePassword(ChangePasswordPost $request)
    {
    	$validated = $request->validated();

        $user = User::where('id', Auth::id())->first();

        $user->fill([
            'password' => Hash::make($validated['new_password']),
        ]);
        $user->save();

    	return redirect()->route(
							'profile.profileTabChoice' ,
							['tab' => 'change-password']
						)
						->with('succeed', 'Succeed change password')
						->with('componentName', 'changePassFormComp');
    }

    public function updateImage(UpdateProfileImgPost $request)
    {
    	$validated = $request->validated();

    	$filters = config('parameters.userProfileImage');

    	/* New images will be saved to storage */
    	$imgFileUpload = new ImgFileUpload(
    		$request->file('profileImg'), 
    		$filters
    	);
    	$imgFileUpload->saveImg();

    	$imagesArr = array_map(function($path){

    		$path = str_replace('public', 'storage', $path);
    		return new Images(['path' => $path]);

    	}, $imgFileUpload->getSavePath());

    	$user = Auth::user();

    	/* Images will be deleted */
    	$oldImagePath = $user->images[0]->path;

    	$deleteImages = array_map(function($filt) use ($oldImagePath){
    		return "/public/upload/imgs/{$filt}/{$oldImagePath}";
    	}, $filters);

    	Storage::delete($deleteImages);
    	Images::where('owner_id', $user->id)->first()->delete();

    	/* New images will be saved to databse */
    	$user->images()->saveMany($imagesArr);

    	return redirect(url()->previous())
					->with('succeed', 'Succeed upload image')
					->with('componentName', 'userInfoComp');
    }

    private function updateRedisUserinfo($oldUser){

        $chatID = $oldUser->chatID;
        $user = $oldUser->toArray();
        $user['chatID'] = $chatID;
        $user['path'] = $oldUser->images[0]->path;

        Redis::hmset('chat_unique_id:'.$chatID, [
            'user' => json_encode($user),
        ]);
    }
}