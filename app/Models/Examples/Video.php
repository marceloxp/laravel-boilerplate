<?php
namespace App\Models\Examples;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Masters\ExamplesModel;

class Video extends ExamplesModel
{
	use SoftDeletes;
    protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at','category_id_text'];

    public static function validate($request, $id = '')
    {
		$rules = 
		[
			'name'        => 'required|min:5|max:150',
			'category_id' => 'required',
			'youtube'     => 'required|min:5|max:150'
		];

		return Video::_validate($request, $rules, $id);
    }

	public function category()
	{
		return $this->belongsTo(\App\Models\Examples\Category::class);
	}

	/**
	* Retrieve Tags pivot Table
	*/
	public function tags()
	{
		return $this->belongsToMany(\App\Models\Examples\Tag::class);
	}
}