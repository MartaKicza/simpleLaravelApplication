<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\User;
use App\Address;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\UsersListGet;
use App\Http\Requests\UserCreatePost;
use App\Http\Requests\UserUpdatePut;

class UserController extends Controller
{
    /**
     * view users list.
     *
     * @param \App\Http\Requests\UsersListGet $request
     * @return \Illuminate\Http\Response
     */
    public function index(UsersListGet $request)
    {
        $data = $request->validated();
		$users = User::with(['address','correspondal_address']);
		if (isset($data['order_by'])) {
			if (!isset($data['order'])) {
				$data['order'] = 'ASC';
			}
			$users->orderBy($data['order_by'], $data['order']);
		}
		if (isset($data['search'])) {
			$users->searchByTerm($data['search']);
		}
    	return new UserResource($users->simplePaginate(config('test.pagination')));
    }

    /**
     * Create a user.
     *
     * @param \App\Http\Requests\UserCreatePost $request
     * @return \Illuminate\Http\Response
     */
    public function create(UserCreatePost $request)
    {
        $data = $request->validated();
		$data['password'] = Hash::make($data['password']);
		$user = new User($data);

		if ($user->isAdministrationWorker()) {
			$address = Address::create($data['address']);
			$c_address = Address::create($data['correspondal_address']);
			$user->address_id = $address->id;
			$user->correspondal_address_id = $c_address->id;
		}
		$user->save();

		return response()->json([
			"created" => true,
			"message" => "User record created",
			"id" => $user->id
		], 201);
    }

    /**
     * Get a user.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
		if ($user->isAdministrationWorker()) {
			$user->load('address', 'correspondal_address');
		}
		return response()->json([
			"user" => $user
		], 200);
    }

    /**
     * Update a user.
     *
     * @param \App\User $user
     * @param \App\Http\Requests\UserUpdatePut $request
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UserUpdatePut $request)
    {
        $data = $request->validated();
		if (isset($data['password'])) {
			$data['password'] = Hash::make($data['password']);
		}
		$user->fill($data);

		DB::transaction(function () use ($user, $data) {
			if ($user->isAdministrationWorker()) {
				if ($user->isDirty('type_aw')) {
					// user changed to administration worker
					// need to add new addresses
					$address = Address::create($data['address']);
					$c_address = Address::create($data['correspondal_address']);
					$user->address_id = $address->id;
					$user->correspondal_address_id = $c_address->id;
				} else {	
					// just update addresses
					$user->load('address', 'correspondal_address'); 
					$user->address->fill($data['address'])->save();
					$user->correspondal_address->fill($data['correspondal_address'])->save();
				}
			} else if (!$user->isAdministrationWorker() && $user->isDirty('type_aw')) {
				// no longer an administration worker 
				// need to delete addresses
				$user->load('address', 'correspondal_address'); 
				$user->address->delete();
				$user->correspondal_address->delete();
			}
			if (!$user->isLecturer()) {
				$user->phone = null;
				$user->education = null;
			}

			if ($user->isDirty()) {
				$user->save();
			}
		});

		return response()->json([
			"updated" => true,
			"message" => "User record has been updated"
		], 200);
    }

    /**
     * Delete a user.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function delete(User $user)
    {
		DB::transaction(function () use ($user) {
			if ($user->isAdministrationWorker()) {
				$user->load('address', 'correspondal_address'); 
				$user->address->delete();
				$user->correspondal_address->delete();
			}
			$user->delete();
		});

		return response()->json([
			"deleted" => true,
			"message" => "User record deleted"
		], 200);
    }
}
