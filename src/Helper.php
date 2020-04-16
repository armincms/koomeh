<?php 

namespace Armincms\Koomeh;


use Illuminate\Support\Str;

 
class Helper
{
    
    public static function table(string $table)
    {
        $prefix = "koomeh_";

        return (Str::startsWith($table, $prefix) ? '' : $prefix).$table;
    }

	public static function periods()
	{
		return [
			'hour' 	=> __('Hour'),
			'day'	=> __("Day"),
			'week' 	=> __('Week'),
			'month'	=> __('Month'),
			'year' 	=> __('Year'),
		];
	}
}