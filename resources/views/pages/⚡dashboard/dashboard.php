<?php

use App\Models\Respondent;
use App\Models\Survey;
use App\Models\Team;
use App\Models\User;
use Livewire\Component;

new class extends Component
{
    public int $systemAdminUsers = 0;

    public int $systemUsers = 0;

    public int $systemTeams = 0;

    public int $systemRespondents = 0;

    public int $systemSurveys = 0;

    public int $systemAverageTeamsPerUser = 0;

    public int $systemAverageRespondentsPerTeam = 0;

    public int $systemAverageSurveysPerRespondent = 0;

    public int $systemAverageSurveysPerTeam = 0;

    public int $teams = 0;

    public int $respondents = 0;

    public int $surveys = 0;

    public int $averageRespondentsPerTeam = 0;

    public int $averageSurveysPerRespondent = 0;

    public int $averageSurveysPerTeam = 0;

    public function mount(): void
    {
        $user = auth()->user();

        if ($user->admin) {
            $this->systemAdminUsers = User::whereAdmin(true)->count();
            $this->systemUsers = User::count();
            $this->systemTeams = Team::count();
            $this->systemRespondents = Respondent::count();
            $this->systemSurveys = Survey::count();
            $this->systemAverageTeamsPerUser = round($this->systemTeams / $this->systemUsers, 2);
            $this->systemAverageRespondentsPerTeam = $this->systemTeams ? round($this->systemRespondents / $this->systemTeams, 2) : 0;
            $this->systemAverageSurveysPerRespondent = $this->systemRespondents ? round($this->systemSurveys / $this->systemRespondents, 2) : 0;
            $this->systemAverageSurveysPerTeam = $this->systemTeams ? round($this->systemSurveys / $this->systemTeams, 2) : 0;
        }

        $teams = $user->teams;
        $respondents = Respondent::whereIn('team_id', $user->teams->pluck('id'));
        $surveys = Survey::whereIn('respondent_id', $respondents->pluck('id'));

        $this->teams = $teams->count();
        $this->respondents = $respondents->count();
        $this->surveys = $surveys->count();
        $this->averageRespondentsPerTeam = $this->teams ? round($this->respondents / $this->teams, 2) : 0;
        $this->averageSurveysPerRespondent = $this->respondents ? round($this->surveys / $this->respondents, 2) : 0;
        $this->averageSurveysPerTeam = $this->teams ? round($this->surveys / $this->teams, 2) : 0;
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app')
            ->title(__('dashboard.title'));
    }
};
