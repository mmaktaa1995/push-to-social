<?php

namespace SocialMedia\Poster\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * \SocialMedia\Poster\Models\SocialMediaSetting
 *
 * @property int $id
 * @property array $facebook
 * @property array $twitter
 * @property array $telegram
 * @property array $linkedin
 * @property array $whatsapp
 * @property array $google
 *
 * @mixin Model
 */
class SocialMediaSetting extends Model
{
    use HasFactory;

    protected $casts = [
        'facebook' => 'array',
        'twitter' => 'array',
        'telegram' => 'array',
        'linkedin' => 'array',
        'whatsapp' => 'array',
        'google' => 'array',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
