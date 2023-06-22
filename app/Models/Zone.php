<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zone extends Model
{
	use HasFactory;

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array<string>|bool
	 */
	protected $guarded = ['id'];

	/**
	 * Get the module that owns the Zone
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function module(): BelongsTo
	{
		return $this->belongsTo(Module::class);
	}
}
