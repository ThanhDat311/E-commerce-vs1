<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class RiskRule extends Model
{
    public const TYPE_TRANSACTION = 'transaction';

    public const TYPE_LOGIN = 'login';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rule_key',
        'ai_type',
        'weight',
        'description',
        'risk_level',
        'settings',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'weight' => 'integer',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            self::clearCache($model->ai_type);
        });

        static::updated(function ($model) {
            self::clearCache($model->ai_type);
        });

        static::deleted(function ($model) {
            self::clearCache($model->ai_type);
        });
    }

    /**
     * Scope rules by AI type.
     */
    public function scopeForType(Builder $query, string $type): Builder
    {
        return $query->where('ai_type', $type);
    }

    /**
     * Clear the risk rules cache for a given type.
     */
    public static function clearCache(?string $type = null): void
    {
        if ($type) {
            Cache::forget("risk_rules_{$type}");
            Cache::forget("risk_rules_full_{$type}");
        } else {
            Cache::forget('risk_rules_'.self::TYPE_TRANSACTION);
            Cache::forget('risk_rules_'.self::TYPE_LOGIN);
            Cache::forget('risk_rules_full_'.self::TYPE_TRANSACTION);
            Cache::forget('risk_rules_full_'.self::TYPE_LOGIN);
            // Legacy keys
            Cache::forget('risk_rules');
            Cache::forget('risk_rules_full');
        }
    }

    /**
     * Get all active risk rules from cache or database.
     *
     * @return array Array of rule_key => weight
     */
    public static function getRules(string $type = self::TYPE_TRANSACTION): array
    {
        return Cache::rememberForever("risk_rules_{$type}", function () use ($type) {
            return self::where('is_active', true)
                ->where('ai_type', $type)
                ->pluck('weight', 'rule_key')
                ->toArray();
        });
    }

    /**
     * Get all rules with descriptions for a given AI type.
     */
    public static function getRulesWithDescriptions(string $type = self::TYPE_TRANSACTION): array
    {
        return Cache::rememberForever("risk_rules_full_{$type}", function () use ($type) {
            return self::where('is_active', true)
                ->where('ai_type', $type)
                ->get()
                ->map(function ($rule) {
                    return [
                        'key' => $rule->rule_key,
                        'weight' => $rule->weight,
                        'description' => $rule->description,
                    ];
                })
                ->keyBy('key')
                ->toArray();
        });
    }

    /**
     * Get a specific rule weight.
     */
    public static function getWeight(string $ruleKey, string $type = self::TYPE_TRANSACTION): int
    {
        $rules = self::getRules($type);

        return $rules[$ruleKey] ?? 0;
    }

    /**
     * Update rule weight and clear cache.
     */
    public static function updateWeight(string $ruleKey, int $weight, string $type = self::TYPE_TRANSACTION): bool
    {
        $result = self::where('rule_key', $ruleKey)->where('ai_type', $type)->update(['weight' => $weight]);
        self::clearCache($type);

        return $result > 0;
    }

    /**
     * Get risk level badge color
     */
    public function getRiskLevelColor(): string
    {
        return match ($this->risk_level) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'amber',
            'low' => 'green',
            default => 'gray',
        };
    }

    /**
     * Get risk level label
     */
    public function getRiskLevelLabel(): string
    {
        return ucfirst($this->risk_level).' Risk';
    }

    /**
     * Get risk level icon
     */
    public function getRiskLevelIcon(): string
    {
        return match ($this->risk_level) {
            'critical' => 'fa-skull-crossbones',
            'high' => 'fa-exclamation-triangle',
            'medium' => 'fa-exclamation-circle',
            'low' => 'fa-shield-alt',
            default => 'fa-question-circle',
        };
    }
}
