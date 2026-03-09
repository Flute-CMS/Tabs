<div class="tabs-settings-container">
    <form class="tabs-settings-form">
        <div class="settings-section">
            <div class="settings-grid">
                <x-forms.field>
                    <x-forms.label for="position">{{ __('tabs.settings.position') }}</x-forms.label>
                    <x-fields.select name="position" id="position">
                        <option value="top" {{ ($settings['position'] ?? 'top') === 'top' ? 'selected' : '' }}>
                            {{ __('tabs.settings.position_top') }}
                        </option>
                        <option value="left" {{ ($settings['position'] ?? 'top') === 'left' ? 'selected' : '' }}>
                            {{ __('tabs.settings.position_left') }}
                        </option>
                    </x-fields.select>
                </x-forms.field>

                {{-- <x-forms.field id="style-field" style="{{ ($settings['position'] ?? 'top') === 'left' ? 'display: none;' : '' }}">
                    <x-forms.label for="style">{{ __('tabs.settings.style') }}</x-forms.label>
                    <x-fields.select name="style" id="style">
                        <option value="default" {{ ($settings['style'] ?? 'default') === 'default' ? 'selected' : '' }}>
                            {{ __('tabs.settings.style_default') }}
                        </option>
                        <option value="pills" {{ ($settings['style'] ?? 'default') === 'pills' ? 'selected' : '' }}>
                            {{ __('tabs.settings.style_pills') }}
                        </option>
                    </x-fields.select>
                </x-forms.field> --}}
            </div>
        </div>

        <div class="settings-section">
            <div class="settings-header">
                <x-forms.label class="section-title">{{ __('tabs.settings.manage_tabs') }}</x-forms.label>
                <x-button type="button" id="btn-add-tab" class="btn-add">
                    <x-icon path="ph.regular.plus" />
                    <span>{{ __('tabs.settings.add_tab') }}</span>
                </x-button>
            </div>

            <div class="tabs-container">
                @if (!empty($settings['tabs']) && count($settings['tabs']) > 0)
                    <div class="tabs-count">
                        <span class="count-text">{{ count($settings['tabs']) }} {{ __('tabs.settings.tabs_total') }}</span>
                    </div>
                @endif
                
                <div class="tabs-list" id="tabs-list">
                    @if (!empty($settings['tabs']) && count($settings['tabs']) > 0)
                        @foreach ($settings['tabs'] as $index => $tab)
                            <div class="tab-item" data-index="{{ $index }}" data-initialized="true">
                                <div class="tab-header">
                                    <h5 class="tab-title">{{ __('tabs.tab') }} #{{ $index + 1 }}</h5>
                                    <button type="button" class="btn-remove-tab" title="{{ __('tabs.settings.remove_tab') }}">
                                        <x-icon path="ph.regular.trash" />
                                    </button>
                                </div>

                                <div class="tab-inputs">
                                    <x-forms.field>
                                        <x-forms.label>{{ __('tabs.settings.title') }}</x-forms.label>
                                        <x-fields.input type="text" name="tabs[{{ $index }}][title]"
                                            value="{{ $tab['title'] ?? '' }}" 
                                            placeholder="{{ __('tabs.settings.title_placeholder') }}"
                                            required />
                                    </x-forms.field>

                                    <x-forms.field>
                                        <x-forms.label>{{ __('tabs.settings.content') }}</x-forms.label>
                                        <x-editor name="tabs[{{ $index }}][content]"
                                            id="tab-content-{{ $index }}-{{ uniqid() }}" 
                                            height="150"
                                            :value="$tab['content'] ?? ''" 
                                            placeholder="{{ __('tabs.settings.content_placeholder') }}" />
                                    </x-forms.field>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="tabs-empty" id="tabs-empty">
                            <p class="empty-text">{{ __('tabs.settings.no_tabs_yet') }}</p>
                            <p class="empty-subtext">{{ __('tabs.settings.add_first_tab') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<template id="tab-item-template">
    <div class="tab-item" data-index="{index}" data-initialized="false">
        <div class="tab-header">
            <h5 class="tab-title">{{ __('tabs.tab') }} #{number}</h5>
            <button type="button" class="btn-remove-tab" title="{{ __('tabs.settings.remove_tab') }}">
                <x-icon path="ph.regular.trash" />
            </button>
        </div>

        <div class="tab-inputs">
            <x-forms.field>
                <x-forms.label>{{ __('tabs.settings.title') }}</x-forms.label>
                <x-fields.input type="text" name="tabs[{index}][title]" 
                    placeholder="{{ __('tabs.settings.title_placeholder') }}"
                    required />
            </x-forms.field>

            <x-forms.field>
                <x-forms.label>{{ __('tabs.settings.content') }}</x-forms.label>
                <x-editor name="tabs[{index}][content]" 
                    id="tab-content-{index}-{uniqid}" 
                    height="150" 
                    value="" 
                    placeholder="{{ __('tabs.settings.content_placeholder') }}" />
            </x-forms.field>
        </div>
    </div>
</template> 