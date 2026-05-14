<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['city', 'street', 'house', 'phone', 'working_hours'])]
class Address extends Model
{

}
