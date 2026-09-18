<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    public const CATEGORIES = [
        'Música' => ['Concierto', 'Festival', 'DJ / Electrónica', 'Otros'],
        'Deportes' => ['Fútbol', 'Running', 'Fitness', 'Otros'],
        'Negocios' => ['Networking', 'Conferencia', 'Taller', 'Otros'],
        'Arte y cultura' => ['Teatro', 'Exposición', 'Cine', 'Otros'],
        'Gastronomía' => ['Cata', 'Feria', 'Clase de cocina', 'Otros'],
        'Comunidad' => ['Charla', 'Voluntariado', 'Encuentro social', 'Otros'],
        'Otros' => ['General'],
    ];

    protected $fillable = [
        'user_id',
        'organization_id',
        'title',
        'slug',
        'description',
        'category',
        'subcategory',
        'cover_image',
        'address',
        'latitude',
        'longitude',
        'start_at',
        'end_at',
        'is_public',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'is_public' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_events')->withTimestamps();
    }

    public function revenue(): float
    {
        return (float) $this->orders()->where('status', 'paid')->sum('total');
    }
}
