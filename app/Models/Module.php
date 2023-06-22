<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
	use HasFactory;

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array<string>|bool
	 */
	protected $guarded = ['id'];

	/**
	 * Get all of the packets for the Module
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function packets(): HasMany
	{
		return $this->hasMany(Packet::class);
	}

	/**
	 * Get all of the zones for the Module
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function zones(): HasMany
	{
		return $this->hasMany(Zone::class);
	}
}
