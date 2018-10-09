<?php

namespace App\Http\Utilities;

define('YT_DEFAULT'           , 'default.jpg');
define('YT_HIGH_QUALITY'      , 'hqdefault.jpg');
define('YT_MEDIUM_QUALITY'    , 'mqdefault.jpg');
define('YT_STANDARD'          , 'sddefault.jpg');
define('YT_MAXIMUM_RESOLUTION', 'maxresdefault.jpg');

class Youtube
{
	public static function getYoutubeUrl($p_youtube_id_or_url)
	{
		$yid = self::getYoutubeId($p_youtube_id_or_url);
		return sprintf('https://www.youtube.com/watch?v=%s', $yid);
	}

	public static function getYoutubeId($p_youtube_id_or_url)
	{
		$yid = $p_youtube_id_or_url;
		if (strpos($yid, 'youtu') !== false)
		{
			preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $yid, $result);
			if (is_array($result))
			{
				if (count($result) > 0)
				{
					$yid = $result[0];
				}
			}
		}

		return $yid;
	}

	public static function getUrlThumb($p_youtube_id_or_url, $p_size = YT_DEFAULT)
	{
		$yid = self::getYoutubeId($p_youtube_id_or_url);
		return sprintf('https://img.youtube.com/vi/%s/%s', $yid, $p_size);
    }

	public static function getImageThumb($p_youtube_id_or_url, $p_size = YT_DEFAULT)
	{
		return sprintf('<img src="%s">', self::getUrlThumb($p_youtube_id_or_url, $p_size));
	}

	public static function getImageLinkThumb($p_youtube_id_or_url, $p_size = YT_DEFAULT)
	{
		return sprintf
		(
			'<a href="%s" target="_blank">%s</a>',
			self::getYoutubeUrl($p_youtube_id_or_url),
			self::getImageThumb($p_youtube_id_or_url, $p_size)
		);
	}

	public static function getUrlLink($p_youtube_id_or_url)
	{
		return sprintf
		(
			'<a href="%s" target="_blank">%s</a>',
			self::getYoutubeUrl($p_youtube_id_or_url),
			self::getYoutubeUrl($p_youtube_id_or_url)
		);
	}

	public static function getImageUrlLink($p_youtube_id_or_url)
	{
		return self::getImageLinkThumb($p_youtube_id_or_url) . '<br>' . self::getUrlLink($p_youtube_id_or_url);
	}

	public static function getEmbeddedPlayer($p_youtube_id_or_url, $p_width = 560, $p_height = 315)
	{
		$yid = self::getYoutubeId($p_youtube_id_or_url);
		$result = sprintf
		(
			'<iframe width="%s" height="%s" src="https://www.youtube.com/embed/%s" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
			$p_width,
			$p_height,
			$yid
		);

		return $result;
	}
}