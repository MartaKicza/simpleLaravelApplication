<?php

namespace App;

use App\Events\UserDeleted;
use App\Events\UserSaved;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname', 'email', 'password', 'type_aw', 'type_l', 'phone', 'education', 'address_id', 'correspondal_address_id' 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

	public function address()
	{
		return $this->belongsTo('App\Address', 'address_id');
	}

	public function correspondal_address()
	{
		return $this->belongsTo('App\Address', 'correspondal_address_id');
	}

	public function logs()
	{
		return $this->morphMany('App\Log', 'loggable');
	}

	public function isAdministrationWorker() {
		return (int)$this->type_aw === 1 ;
	}

	public function isLecturer() {
		return (int)$this->type_l === 1 ;
	}

	/**
	 * The event map for the model.
	 *
	 * @var array
	 */
	protected $dispatchesEvents = [
		'saved' => UserSaved::class,
		'deleted' => UserDeleted::class,
	];

    public function scopeSearchByTerm($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('lastname', 'like', "%{$term}%");
        });
    }

    public function scopeUserOrderBy($query, $field, $dir) {
        switch ($field) {
            case 'type':
                return $this->scopeOrderByType($query, $dir);
                break;
            case 'name':
                return $this->scopeOrderByName($query, $dir);
                break;
            case 'lastname':
                return $this->scopeOrderByLastname($query, $dir);
                break;
            case 'email':
                return $this->scopeOrderByEmail($query, $dir);
                break;
            default:
                return $this->scopeOrderByLastname($query, $dir);
        }
    }

    public function scopeOrderByType($query, $dir = 'DESC')
    {
        return $query->orderBy('type_l', $dir)->orderBy('type_aw', $dir);
    }

    public function scopeOrderByName($query, $dir = 'ASC')
    {
        return $query->orderBy('name', $dir);
    }

    public function scopeOrderByLastname($query, $dir = 'ASC')
    {
        return $query->orderBy('lastname', $dir);
    }

    public function scopeOrderByEmail($query, $dir = 'ASC')
    {
        return $query->orderBy('email', $dir);
    }

}
