<?php

namespace App\Models;

class Media extends \Spatie\MediaLibrary\MediaCollections\Models\Media
{


    protected $appends=[
        'media_link',
        'created_at_format',
    ];
    public function getMediaLinkAttribute()
    {
        return $this->getUrl();
    }
    public function getCreatedAtFormatAttribute()
    {
        return optional($this->created_at)->format('d-m-Y h:i A');
    }
}
