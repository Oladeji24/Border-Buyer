<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelChanged
{
    use Dispatchable, SerializesModels;

    /**
     * The cache tags to invalidate
     *
     * @var array
     */
    public $tags;

    /**
     * Create a new event instance.
     *
     * @param array $tags
     * @return void
     */
    public function __construct(array $tags)
    {
        $this->tags = $tags;
    }
}
