<?php

namespace Armincms\Koomeh\Models; 

class KoomehVacationDay extends Model  
{      
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [   
        'start_date' => 'date',
        'end_date' => 'date',
    ];  

    /**
     * Query related KoomehVacation.
     * 
     * @return \Illuminate\DAtabase\Elqoeunt\Relations\BelongsTo
     */
    public function vacation()
    {
        return $this->belongsTo(KoomehVacation::class);
    }
}
