<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Packet extends Model
{
	use HasFactory;

	const CREATED_AT = 'recorded_at';

	const UPDATED_AT = null;

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array<string>|bool
	 */
	protected $guarded = ['id'];

	/**
	 * Get the module that owns the Packet
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function module(): BelongsTo
	{
		return $this->belongsTo(Module::class);
	}

	/**
	 * Get all of the Sensor Values for the Packet
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function sensorValues(): HasMany
	{
		return $this->hasMany(SensorValue::class);
	}
}
