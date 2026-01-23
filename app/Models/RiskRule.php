<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class RiskRule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rule_key',
        'weight',
        'description',
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
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Invalidate cache when rule is created, updated, or deleted
        static::created(function ($model) {
            self::clearCache();
        });

        static::updated(function ($model) {
            self::clearCache();
        });

        static::deleted(function ($model) {
            self::clearCache();
        });
    }

    /**
     * Clear the risk rules cache.
     */
    public static function clearCache()
    {
        Cache::forget('risk_rules');
    }

    /**
     * Get all active risk rules from cache or database.
     * 
     * @return array Array of rule_key => weight
     */
    public static function getRules(): array
    {
        return Cache::rememberForever('risk_rules', function () {
            return self::where('is_active', true)
                ->pluck('weight', 'rule_key')
                ->toArray();
        });
    }

    /**
     * Get all rules with descriptions.
     *
     * @return array
     */
    public static function getRulesWithDescriptions(): array
    {
        return Cache::rememberForever('risk_rules_full', function () {
            return self::where('is_active', true)
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
     *
     * @param string $ruleKey
     * @return int
     */
    public static function getWeight(string $ruleKey): int
    {
        $rules = self::getRules();
        return $rules[$ruleKey] ?? 0;
    }

    /**
     * Update rule weight and clear cache.
     *
     * @param string $ruleKey
     * @param int $weight
     * @return bool
     */
    public static function updateWeight(string $ruleKey, int $weight): bool
    {
        $result = self::where('rule_key', $ruleKey)->update(['weight' => $weight]);
        self::clearCache();
        return $result > 0;
    }
}
