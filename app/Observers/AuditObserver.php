<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function updated(Model $model)
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'action' => 'updated',
            'old_values' => $model->getOriginal(),
            'new_values' => $model->getChanges(),
        ]);
    }
}
