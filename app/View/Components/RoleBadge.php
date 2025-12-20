<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RoleBadge extends Component
{
    public string $class;
    public string $label;
    public string $icon;

    public function __construct(?string $role)
    {
        $map = [
            'admin' => [
                'class' => 'bg-red-50 text-red-700 ring-1 ring-red-200',
                'label' => 'Admin',
                'icon'  => 'shield-check'
            ],
            'finance' => [
                'class' => 'bg-yellow-50 text-yellow-700 ring-1 ring-yellow-200',
                'label' => 'Finance',
                'icon'  => 'wallet'
            ],
            'jamaah' => [
                'class' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
                'label' => 'Jamaah',
                'icon'  => 'user'
            ],
        ];

        $data = $map[$role] ?? [
            'class' => 'bg-gray-50 text-gray-700 ring-1 ring-gray-200',
            'label' => ucfirst($role ?? 'Unknown'),
            'icon'  => 'user-question'
        ];

        $this->class = $data['class'];
        $this->label = $data['label'];
        $this->icon  = $data['icon'];
    }

    public function render()
    {
        return view('components.role-badge');
    }
}
