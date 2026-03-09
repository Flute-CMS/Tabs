<?php

namespace Flute\Modules\Tabs\Widgets;

use Flute\Core\Modules\Page\Widgets\AbstractWidget;

class TabsWidget extends AbstractWidget
{
    public function getName(): string
    {
        return 'tabs.widget';
    }

    public function getIcon(): string
    {
        return 'ph.regular.stack';
    }

    public function getCategory(): string
    {
        return 'content';
    }

    public function render(array $settings): string
    {
        $tabs = $settings['tabs'] ?? [];

        $tabs = array_filter($tabs, function ($tab) {
            return !empty($tab['title']);
        });

        $uniqueId = uniqid('tabs_');

        return view('tabs::widgets.tabs', [
            'tabs' => array_values($tabs),
            'settings' => $settings,
            'uniqueId' => $uniqueId
        ])->render();
    }

    public function getDefaultWidth(): int
    {
        return 12;
    }

    public function getMinWidth(): int
    {
        return 2;
    }

    public function hasSettings(): bool
    {
        return true;
    }

    /**
     * Get default settings
     */
    public function getSettings(): array
    {
        return [
            'tabs' => [],
            'position' => 'top', // top, left
            'style' => 'default', // default, pills (for top position)
        ];
    }

    /**
     * Returns the settings form
     */
    public function renderSettingsForm(array $settings): string
    {
        return view('tabs::widgets.settings', [
            'settings' => $settings
        ])->render();
    }

    /**
     * Validates the widget's settings before saving.
     */
    public function validateSettings(array $input)
    {
        return validator()->validate($input, [
            'position' => 'required|in:top,left',
            // 'style' => 'required|in:default,pills',
        ]);
    }

    /**
     * Saves the widget's settings.
     */
    public function saveSettings(array $input): array
    {
        $settings = $this->getSettings();

        $settings['position'] = $input['position'] ?? 'top';
        $settings['style'] = $input['style'] ?? 'default';

        $tabs = [];

        if (isset($input['tabs']) && is_array($input['tabs'])) {
            foreach ($input['tabs'] as $tab) {
                if (!empty($tab['title'])) {
                    $tabs[] = [
                        'title' => $tab['title'],
                        'content' => $tab['content'] ?? ''
                    ];
                }
            }
        }

        $settings['tabs'] = array_values($tabs);

        return $settings;
    }
} 