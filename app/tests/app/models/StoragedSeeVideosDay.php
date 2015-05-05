<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Tárolt videók (model videok és belső videók) nézetségének nyilvántartása
 */

class StoragedSeeVideosDay extends Eloquent
{
    protected $table = '.storaged_see_videos_day';
    protected $primaryKey = 'id';

    public static function getViewToStoragedVideoIdByInterval($storagedVideoId, $start, $end) {
        $query = self::where('storaged_video_id', '=', $storagedVideoId);
        $query->whereBetween('day', array($start, $end));
        $sees = $query->get();
        $view = 0;
        foreach($sees as $see) {
            $view+= $see->see_count;
        }
        return $view;
    }


    public static function get7DaySeeToStoragedVideoId($storagedVideoId) {
        $end = date('Y-m-d');
        $start = date('Y-m-d', strtotime($end." -6 days"));
        return self::getViewToStoragedVideoIdByInterval($storagedVideoId, $start, $end);
    }
}