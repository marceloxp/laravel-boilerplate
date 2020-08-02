<?php

namespace App\Models\Examples;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Masters\ExamplesModel;

class Tag extends ExamplesModel
{
	use SoftDeletes;
	protected $dates   = ['created_at','updated_at','deleted_at'];
	protected $guarded = ['created_at','updated_at','deleted_at'];

	public static function validate($request, $id = '')
	{
		$rules = 
		[
			'name' => 'required|min:3|max:150'
		];

		return self::_validate($request, $rules, $id);
	}

	/**
	* Retrieve Videos pivot Table
	*/
	public function videos()
	{
		return $this->belongsToMany(\App\Models\Examples\Video::class);
	}

	/**
	* Retrieve All Tags related to One Video
	*/
	public function scopeTagVideo($query, $p_video_id)
	{
		return $query->join('tag_video', 'tags.id', '=', 'tag_video.tag_id')->where('tag_video.video_id', $p_video_id);
	}
}