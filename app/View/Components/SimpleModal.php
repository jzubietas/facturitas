<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SimpleModal extends Component
{
    public $childs;
    public $attrs;
    public $title;
    public $body;
    public $footer;
    public $dialog;

    public function __construct(
        $all = [],
        $title = '',
        $body = '',
        $footer = '',
        $class = '',
        $scrollable = false,
        $centered = false,
        $size = '',
        $id = ''
    )
    {
        $this->childs = $all ?? [];
        $this->title = $title ?: $all['title'] ?? '';
        $this->body = $body ?: $all['body'] ?? '';
        $this->footer = $footer ?: $all['footer'] ?? '';
        $this->scrollable = $scrollable ?: $all['scrollable'] ?? false;
        $this->centered = $centered ?: $all['centered'] ?? false;
        $this->size = $size ?: $all['size'] ?? '';
        $this->attrs = [
            'id' => $id ?: $all['id'] ?? '',
        ];
        $this->attrs['class'] = static::ClassesGet([
            'modal',
            'class' => $class ?: $all['class'] ?? 'fade',
        ]);
        $this->dialog['class'] = static::ClassesGet([
            'modal-dialog',
            $this->scrollable === true ? 'modal-dialog-scrollable' : '',
            $this->centered === true ? 'modal-dialog-centered' : '',
            !empty($this->size) ? 'modal-' . $this->size : '',
        ]);
        $this->attrs = \array_filter($this->attrs);
    }

    public function render()
    {
        return view('components.simple-modal');
    }
    public static function Classesget($classes)
    {
        $result = '';

        foreach($classes as $class) {
            $spacer = empty($result) ? '' : ' ';
            if (isset($class['class']) && !empty($class['class'])) {
                $result .= $spacer . $class['class'];
            } elseif(!empty($class) && !is_array($class)) {
                $result .= $spacer . $class;
            }
        }

        return $result;
    }
}

