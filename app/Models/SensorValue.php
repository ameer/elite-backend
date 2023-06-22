<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorValue extends Model
{
	use HasFactory;

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array<string>|bool
	 */
	protected $guarded = ['packet_id'];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Get the packet that owns the SensorValue
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function packet(): BelongsTo
	{
		return $this->belongsTo(Packet::class);
	}
}
