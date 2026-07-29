<?php

namespace App\Models;

use Database\Factories\WineFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wine extends Model
{
  /** @use HasFactory<WineFactory> */
  use HasFactory;
}
