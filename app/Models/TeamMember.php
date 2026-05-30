<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['surname', 'name', 'description', 'image_path', 'position'])]
class TeamMember extends Model
{

}
