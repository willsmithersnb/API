<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Material extends Model
{
    protected $table = 'materials';
    public $timestamps = true;

    use SoftDeletes;
    use Filterable;
    use LogsActivity;

    protected $dates = ['deleted_at'];
    protected $fillable = array('igmp_material_id', 'igmp_spec_id', 'igmp_part_num', 'igmp_name', 'igmp_material_description', 'igmp_lead_time', 'grade', 'storage_requirement', 'reference_num', 'reference_type', 'price', 'cost', 'pricing_quantity', 'pricing_unit', 'unit_type', 'is_active', 'nb_part_num', 'materialable_id', 'materialable_type');
    protected $visible = array('id', 'igmp_material_id', 'igmp_spec_id', 'igmp_part_num', 'igmp_name', 'igmp_material_description', 'igmp_lead_time', 'grade', 'storage_requirement', 'reference_num', 'reference_type', 'price', 'cost', 'pricing_quantity', 'pricing_unit', 'unit_type', 'is_active', 'nb_part_num', 'materialable_id', 'materialable_type', 'created_at', 'updated_at');
    protected static $logAttributes = ['igmp_material_id', 'igmp_spec_id', 'igmp_part_num', 'igmp_name', 'igmp_material_description', 'igmp_lead_time', 'grade', 'storage_requirement', 'reference_num', 'reference_type', 'price', 'cost', 'pricing_quantity', 'pricing_unit', 'unit_type', 'is_active', 'nb_part_num', 'materialable_id', 'materialable_type'];
    protected static $logOnlyDirty = true;

    public function materialable()
    {
        return $this->morphTo();
    }
}
