@php
    $position = $settings['position'] ?? 'top';
    $style = $settings['style'] ?? 'default';
    $isPills = $style === 'pills' && $position === 'top';
    $isLeft = $position === 'left';
@endphp

<div class="tabs-widget tabs-position-{{ $position }} tabs-style-{{ $style }}">
    @if (empty($tabs))
        <p>{{ __('tabs.no_tabs') }}</p>
    @else
        @if ($isLeft)
            <div class="tabs-left-container">
                <div class="tabs-left-nav">
                    @foreach ($tabs as $index => $tab)
                        <button 
                            type="button"
                            class="tab-left-button {{ $index === 0 ? 'active' : '' }}"
                            data-tab-target="tab-{{ $uniqueId }}-{{ $index }}"
                            role="tab"
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-controls="tab-{{ $uniqueId }}-{{ $index }}"
                        >
                            {{ $tab['title'] }}
                        </button>
                    @endforeach
                </div>

                <div class="tabs-left-content">
                    @foreach ($tabs as $index => $tab)
                        <div 
                            id="tab-{{ $uniqueId }}-{{ $index }}"
                            class="tab-left-panel {{ $index === 0 ? 'active' : '' }}"
                            role="tabpanel"
                            aria-labelledby="tab-left-{{ $uniqueId }}-{{ $index }}"
                        >
                            <div class="tab-content-inner">
                                @if (!empty($tab['content']))
                                    {!! markdown()->parse($tab['content'], false, false) !!}
                                @else
                                    <p class="tab-empty-content">{{ __('tabs.no_content') }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <x-tabs name="{{ $uniqueId }}" :pills="$isPills">
                <x-slot:headings>
                    @foreach ($tabs as $index => $tab)
                        <x-tab-heading
                            name="tab-{{ $uniqueId }}-{{ $index }}"
                            label="{{ $tab['title'] }}"
                            active="{{ $index === 0 }}"
                            withoutHtmx="true"
                        />
                    @endforeach
                </x-slot:headings>

                <x-tab-body>
                    @foreach ($tabs as $index => $tab)
                        <x-tab-content
                            name="tab-{{ $uniqueId }}-{{ $index }}"
                            active="{{ $index === 0 }}"
                        >
                            <div class="tab-content-inner">
                                @if (!empty($tab['content']))
                                    {!! markdown()->parse($tab['content'], false, false) !!}
                                @else
                                    <p class="tab-empty-content">{{ __('tabs.no_content') }}</p>
                                @endif
                            </div>
                        </x-tab-content>
                    @endforeach
                </x-tab-body>
            </x-tabs>
        @endif
    @endif
</div> 