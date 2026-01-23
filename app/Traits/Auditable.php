<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    /**
     * Boot the Auditable trait.
     */
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            static::createAuditLog($model, 'created');
        });

        static::updated(function ($model) {
            static::createAuditLog($model, 'updated');
        });

        static::deleted(function ($model) {
            static::createAuditLog($model, 'deleted');
        });
    }

    /**
     * Create an audit log entry for the model.
     */
    protected static function createAuditLog($model, string $action)
    {
        // Get the attributes that changed
        $oldValues = null;
        $newValues = null;

        if ($action === 'updated') {
            // Get the original values before update
            $oldValues = collect($model->getOriginal())
                ->only($model->getAuditableAttributes())
                ->toArray();

            // Get the new values after update
            $newValues = collect($model->getAttributes())
                ->only($model->getAuditableAttributes())
                ->toArray();

            // Only log if there are actual changes
            if ($oldValues === $newValues) {
                return;
            }
        } else if ($action === 'created') {
            $newValues = collect($model->getAttributes())
                ->only($model->getAuditableAttributes())
                ->toArray();
        } else if ($action === 'deleted') {
            $oldValues = collect($model->getAttributes())
                ->only($model->getAuditableAttributes())
                ->toArray();
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Get the attributes that should be audited.
     * Override this method in your model to specify custom attributes.
     *
     * @return array
     */
    public function getAuditableAttributes(): array
    {
        // By default, audit all attributes except timestamps and model relations
        return collect($this->getAttributes())
            ->except(['created_at', 'updated_at', 'deleted_at', 'remember_token'])
            ->keys()
            ->toArray();
    }

    /**
     * Get audit logs for this model.
     */
    public function auditLogs()
    {
        return AuditLog::where('model_type', get_class($this))
            ->where('model_id', $this->id)
            ->latest()
            ->get();
    }
}
