<?php

namespace App\Http\Forms;

use Taufik\LaravelFormBuilder\BaseForm;

class BookForm extends BaseForm {

    public function build()
    {
        $this->setOrientation('horizontal');

        $this->addField('title', 'text', ['rules' => 'required', 'narrow' => true]);

        $this->addField('quality', 'select', [
            'empty_value' => ' -- Choose Quality -- ',
            'choices' => [
                'low' => 'Fair',
                'medium' => 'Good',
                'high' => 'Best',
            ],
            'selected' => 'medium',
            'rules' => 'required|not_in:none',
        ]);

        $this->addField('is_public', 'radio', [
            'choices' => [
                '1' => 'Yes',
                '0' => 'No',
            ],
            'selected' => '0',
            'rules' => 'required',
        ]);

        $this->addField('tags', 'checkbox', [
            'choices' => [
                'php' => 'PHP',
                'laravel' => 'Laravel',
                'js' => 'Javascript',
            ],
            'selected' => 'laravel',
            'rules' => 'required',
        ]);

        $this->addField('description', 'textarea', ['rules' => 'required', 'rows' => 3]);

        $this->addField('send', 'submit', ['class' => 'is-primary']);
    }
}