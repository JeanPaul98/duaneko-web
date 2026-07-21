<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function getMainNavItems()
    {
        $items = [
            [
                'icon' => 'dashboard',
                'name' => 'Tableau de bord',
                'path' => '/dashboard', // Adapté de votre lien <a href="home">
            ],
            [
                'icon' => 'reports',
                'name' => 'Signalements',
                'path' => route('reports.index'),
            ]
        ];

        // Rôle Admin ou Manager
        if (Auth::user()->hasRole(['admin', 'manager'])) {
            $items[] = [
                'icon' => 'agents',
                'name' => 'Agents',
                'path' => route('agents.index'),
            ];
        }

        // Rôle Admin uniquement
        if (Auth::user()->hasRole('admin')) {
            $items[] = [
                'icon' => 'companies',
                'name' => 'Entreprises',
                'path' => route('companies.index'),
            ];
            $items[] = [
                'icon' => 'managers',
                'name' => 'Manageurs',
                'path' => route('managers.index'),
            ];
        }

        // Ramassages & Zones (Accessibles à tous les connectés d'après votre ancien code)
        $items[] = [
            'icon' => 'ramassages',
            'name' => 'Ramassages',
            'path' => route('ramassages.index'),
        ];

        $items[] = [
            'icon' => 'zones',
            'name' => 'Zones',
            'path' => route('zones.index'),
        ];

        return $items;
    }

    public static function getOthersItems()
    {
        // Gardez votre logique ou laissez vide si non utilisé
        return [];
    }

    public static function getMenuGroups()
    {
        return [
            [
                'title' => 'Menu',
                'items' => self::getMainNavItems()
            ]
        ];
    }

    public static function isActive($path)
    {
        return request()->is(ltrim($path, '/')) || request()->url() == $path;
    }

    public static function getIconSvg($iconName)
    {
        $icons = [
            'dashboard' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
            
            'reports' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="1"></circle></svg>',
            
            'agents' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
            
            'companies' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>',
            
            'managers' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"></circle><path d="M18 21v-2a4 4 0 0 0-4-4H10a4 4 0 0 0-4 4v2"></path></svg>',
            
            'ramassages' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>',
            
            'zones' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>'
        ];

        return $icons[$iconName] ?? '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle></svg>';
    }
}