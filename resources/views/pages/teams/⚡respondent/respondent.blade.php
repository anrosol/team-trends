<div>

    @use('\App\Enums\PeriodEnum')

    @if ($team === null)

        {{-- Passphrase Gate --}}
        <div class="md:w-96 mx-auto mt-20">

            <x-header title="{{ __('teams.respondent.title') }}" subtitle="{{ __('teams.respondent.subtitle') }}" separator />

            <p class="mb-6 text-sm text-base-content/60">{{ __('teams.respondent.passphrase_intro') }}</p>

            <x-form wire:submit="submitPassphrase">

                <x-honeypot />

                <div>

                    <x-password label="{{ __('teams.respondent.passphrase') }}" wire:model="passphrase" hint="{{ __('teams.respondent.passphrase_hint') }}" required />

                    <x-slot:actions>

                        <x-button label="{{ __('teams.respondent.submit') }}" icon="o-paper-airplane" spinner="submitPassphrase" type="submit" class="btn-primary" />

                    </x-slot:actions>

                </div>

            </x-form>

        </div>

    @else

        <x-header title="{{ $team->name }}" separator>

            <x-slot:actions>

                <x-button label="{{ __('teams.respondent.leave') }}" link="{{ route('pages.teams.respondent') }}" class="btn" />

            </x-slot:actions>

        </x-header>

        <x-tabs selected="survey">

            <x-tab name="survey" label="{{ __('teams.respondent.tab_survey') }}" icon="o-clipboard-document-list">

                @if (! $team->active)

                    <x-alert icon="o-exclamation-triangle" class="alert-warning">
                        {{ __('teams.respondent.errors.team_inactive') }}
                    </x-alert>

                @else

                <p class="text-sm text-base-content/50 mb-6">
                    {{ __('teams.respondent.question_count', ['count' => count($team->questions)]) }}
                    &middot;
                    {{ __('teams.respondent.weekly_pulse') }}
                </p>

                <x-form class="w-full" wire:submit="submitSurvey">

                    @foreach($team->questions as $i => $question)

                        <x-card class="mb-4" shadow>

                            <div class="flex items-start gap-4">

                                <div class="badge badge-primary badge-lg shrink-0 mt-1">{{ $i + 1 }}</div>

                                <div class="flex-1">

                                    <div class="font-semibold mb-1">{{ $question['name'] }}</div>

                                    <div class="text-sm text-base-content/60 mb-4">{{ $question['definition'] }}</div>

                                    <x-radio wire:model="responses.{{ $i }}" :options="$this->likertScaleOptions" inline error="responses.{{ $i }}" />

                                </div>

                            </div>

                        </x-card>

                    @endforeach

                    @error('responses')
                        <div class="text-error text-sm -mt-2 mb-2">{{ $message }}</div>
                    @enderror

                    <x-slot:actions>

                        <x-button label="{{ __('teams.respondent.submit_survey') }}" icon="o-paper-airplane" spinner="submitSurvey" type="submit" class="btn-primary" />

                    </x-slot:actions>

                </x-form>

                @endif

            </x-tab>

            <x-tab name="chart" label="{{ __('teams.respondent.tab_chart') }}" icon="o-presentation-chart-line">

                <x-card separator>

                    <div class="grid sm:grid-cols-1 md:grid-cols-6 sm:gap-1 md:gap-4">

                        <x-datetime label="{{ __('teams.respondent.begin_at') }}" wire:model.live="begin_at" />

                        <x-datetime label="{{ __('teams.respondent.end_at') }}" wire:model.live="end_at" />

                        <x-select label="{{ __('teams.respondent.question') }}" wire:model.live="question" :options="collect($team->questions)->map(fn ($question, $i) => ['id' => $i, 'name' => $question['name']])" icon="o-user" />

                        <x-select label="{{ __('teams.respondent.period') }}" wire:model.live="period" :options="collect(PeriodEnum::cases())->map(fn ($period) => ['id' => $period->value, 'name' => __($period->value)])" icon="o-user" />

                        <div class="md:mt-10">

                            <x-toggle label="{{ __('teams.respondent.weighted') }}" wire:model.live="weighted" />

                        </div>

                        <div class="md:mt-10">

                            <x-toggle label="{{ __('teams.respondent.only_me') }}" wire:model.live="me" />

                        </div>

                    </div>

                </x-card>

                <div class="flex flex-row">

                    <x-chart wire:model="chart" class="grow" />

                </div>

            </x-tab>

        </x-tabs>

    @endif

</div>
