<?php
namespace App\Models;


use App\Services\SetRequeetReturnDataServicr;
use Illuminate\Database\Eloquent\Model;
class BaseModel extends Model{

    protected $guarded = [];
    protected $connection = 'sqlite';
}
