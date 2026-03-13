<div>

    <x-header title="{{ __('dashboard.title') }}" separator />

    @if (auth()->user()->admin)

        <x-card title="{{ __('dashboard.admin.card.title') }}" subtitle="{{ __('dashboard.admin.card.subtitle') }}" shadow separator>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

                <x-stat
                    title="{{ __('dashboard.admin.stats.administrators.title') }}"
                    value="{{ $systemAdminUsers }}"
                    icon="c-shield-check"
                    description="{{ __('dashboard.admin.stats.administrators.description') }}"
                    color="text-primary" />

                <x-stat
                    title="{{ __('dashboard.admin.stats.users.title') }}"
                    value="{{ $systemUsers }}"
                    icon="c-user-group"
                    description="{{ __('dashboard.admin.stats.users.description') }}"
                    color="text-primary" />

                <x-stat
                    title="{{ __('dashboard.admin.stats.teams.title') }}"
                    value="{{ $systemTeams }}"
                    icon="c-rectangle-group"
                    color="text-primary" />

                <x-stat
                    title="{{ __('dashboard.admin.stats.respondents.title') }}"
                    value="{{ $systemRespondents }}"
                    icon="c-user"
                    color="text-primary" />

                <x-stat
                    title="{{ __('dashboard.admin.stats.surveys.title') }}"
                    value="{{ $systemSurveys }}"
                    icon="c-clipboard-document-list"
                    color="text-primary" />

                <x-stat
                    title="{{ __('dashboard.admin.stats.avg_teams_per_user.title') }}"
                    value="{{ $systemAverageTeamsPerUser }}"
                    icon="c-chart-bar"
                    color="text-primary" />

                <x-stat
                    title="{{ __('dashboard.admin.stats.avg_respondents_per_team.title') }}"
                    value="{{ $systemAverageRespondentsPerTeam }}"
                    icon="c-chart-pie"
                    color="text-primary" />

                <x-stat
                    title="{{ __('dashboard.admin.stats.avg_surveys_per_respondent.title') }}"
                    value="{{ $systemAverageSurveysPerRespondent }}"
                    icon="c-presentation-chart-bar"
                    color="text-primary" />

                <x-stat
                    title="{{ __('dashboard.admin.stats.avg_surveys_per_team.title') }}"
                    value="{{ $systemAverageSurveysPerTeam }}"
                    icon="c-presentation-chart-bar"
                    color="text-primary" />

            </div>

        </x-card>

        <div class="my-5"></div>
    @endif

    <x-card title="{{ __('dashboard.my.card.title') }}" subtitle="{{ __('dashboard.my.card.subtitle') }}" shadow separator>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-5">
            <x-stat
                title="{{ __('dashboard.my.stats.teams.title') }}"
                value="{{ $teams }}"
                icon="c-rectangle-group" />

            <x-stat
                title="{{ __('dashboard.my.stats.respondents.title') }}"
                value="{{ $respondents }}"
                icon="c-user" />

            <x-stat
                title="{{ __('dashboard.my.stats.surveys.title') }}"
                value="{{ $surveys }}"
                icon="c-clipboard-document-list" />

            <x-stat
                title="{{ __('dashboard.my.stats.avg_respondents_per_team.title') }}"
                value="{{ $averageRespondentsPerTeam }}"
                icon="c-chart-pie" />

            <x-stat
                title="{{ __('dashboard.my.stats.avg_surveys_per_respondent.title') }}"
                value="{{ $averageSurveysPerRespondent }}"
                icon="c-presentation-chart-bar" />

            <x-stat
                title="{{ __('dashboard.my.stats.avg_surveys_per_team.title') }}"
                value="{{ $averageSurveysPerTeam }}"
                icon="c-presentation-chart-bar" />

        </div>

    </x-card>

</div>
