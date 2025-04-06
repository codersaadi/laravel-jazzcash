<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Registration;


class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $guarded = [];
    // protected $fillable = [
    //     'course_name',
    //     'description',
    //     'advisor',
    //     'level',
    //     'no_of_lectures',
    //     'duration',
    //     'location',
    //     'content',
    //     'course_image',
    // ];
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function regions()
    {
        return $this->belongsToMany(Region::class, 'course_region')
                    ->withPivot('price') // Include price in the pivot table
                    ->withTimestamps();  // Add timestamps
    }
    public function registerations(){
        return $this->hasMany(Registration::class);
    }
}
