<div>

    {{-- Hero --}}
    <div class="text-center py-12 px-4">
        <h1 class="text-4xl font-bold mb-4">{{ __('index.hero.title') }}</h1>
        <p class="text-lg max-w-2xl mx-auto opacity-70">{{ __('index.hero.subtitle') }}</p>
    </div>

    {{-- Audience Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-20">

        <x-card title="{{ __('index.respondents.title') }}">
            
            <p class="mb-5 font-bold text-md">{{ __('index.respondents.lead') }}</p>

            <p class="mb-5">{{ __('index.respondents.p1') }}</p>
            
            <p>{{ __('index.respondents.p2') }}</p>

            <x-slot:figure>

                <img src="https://picsum.photos/800/200" />

            </x-slot:figure>

            <x-slot:menu>

                {{--
                    <x-button icon="o-share" class="btn-circle btn-sm" />

                    <x-icon name="o-heart" class="cursor-pointer" />
                --}}

            </x-slot:menu>

            <x-slot:actions separator>

                <x-button label="{{ __('index.actions.respondent') }}" link="{{ route('pages.teams.respondent') }}" />

            </x-slot:actions>

        </x-card>

        <x-card title="{{ __('index.admins.title') }}">

            <p class="mb-5 font-bold text-md">{{ __('index.admins.lead') }}</p>

            <p class="mb-5">{{ __('index.admins.p1') }}</p>
            
            <p>{{ __('index.admins.p2') }}</p>

            <x-slot:figure>

                <img src="https://picsum.photos/801/200" />

            </x-slot:figure>

            <x-slot:menu>

                {{--
                    <x-button icon="o-share" class="btn-circle btn-sm" />

                    <x-icon name="o-heart" class="cursor-pointer" />
                --}}

            </x-slot:menu>

            <x-slot:actions separator>

                @guest

                    <x-button label="{{ __('index.actions.register') }}" link="{{ route('pages.auth.register') }}" class="btn" />

                    <x-button label="{{ __('index.actions.login') }}" link="{{ route('login') }}" class="btn" />

                @endguest

            </x-slot:actions>

        </x-card>

    </div>

    {{-- How It Works --}}
    <div class="mb-20">

        <h2 class="text-2xl font-semibold text-center mb-6">{{ __('index.how.title') }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <x-card title="{{ __('index.how.step1.title') }}" shadow>

                <x-slot:figure>

                    <div class="flex items-center justify-center py-6">

                        <x-icon name="c-rectangle-group" class="w-16 h-16 opacity-30" />

                    </div>

                </x-slot:figure>

                <p>{{ __('index.how.step1.body') }}</p>

            </x-card>

            <x-card title="{{ __('index.how.step2.title') }}" shadow>

                <x-slot:figure>

                    <div class="flex items-center justify-center py-6">

                        <x-icon name="c-clipboard-document-list" class="w-16 h-16 opacity-30" />

                    </div>

                </x-slot:figure>

                <p>{{ __('index.how.step2.body') }}</p>

            </x-card>

            <x-card title="{{ __('index.how.step3.title') }}" shadow>

                <x-slot:figure>

                    <div class="flex items-center justify-center py-6">

                        <x-icon name="c-presentation-chart-line" class="w-16 h-16 opacity-30" />

                    </div>

                </x-slot:figure>

                <p>{{ __('index.how.step3.body') }}</p>

            </x-card>

        </div>

    </div>

    {{-- Features / Benefits --}}
    <div class="mb-10">

        <h2 class="text-2xl font-semibold text-center mb-6">{{ __('index.features.title') }}</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

            @foreach ([
                ['icon' => 'c-shield-check',           'title' => __('index.features.privacy.title'),     'body' => __('index.features.privacy.body')],
                ['icon' => 'c-eye-slash',               'title' => __('index.features.anonymous.title'),   'body' => __('index.features.anonymous.body')],
                ['icon' => 'c-chart-bar',               'title' => __('index.features.trends.title'),      'body' => __('index.features.trends.body')],
                ['icon' => 'c-code-bracket',            'title' => __('index.features.open_source.title'), 'body' => __('index.features.open_source.body')],
            ] as $feature)

                <div class="bg-base-100 rounded-lg px-5 py-4 flex gap-3">

                    <div class="text-primary shrink-0 mt-1">

                        <x-icon :name="$feature['icon']" class="w-9 h-9" />

                    </div>

                    <div>

                        <div class="text-xs text-base-content/50 mb-1">{{ $feature['title'] }}</div>

                        <div class="text-sm">{{ $feature['body'] }}</div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</div>
