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

    /**
     * Determine if includes current date.
     * 
     * @return boolean
     */
    public function includesPresent(): bool
    {
        return now()->between($this->start_date, $this->end_date, true);        
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collections\VacationDayCollection($models);
    }
}
