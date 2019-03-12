<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesGroup extends Model
{
    protected $table = "oc_sales_group";
    protected $primaryKey = "sales_group_id";
    public $timestamps = false;

    protected $fillable = ["name", "start_date", "end_date"];
}
