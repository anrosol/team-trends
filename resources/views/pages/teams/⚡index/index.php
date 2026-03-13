<?php

use App\Models\Team;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;
    use WithPagination;

    public string $search = '';

    public array $sortBy = ['column' => 'created_at', 'direction' => 'asc'];

    public function mount(): void
    {
        $this->authorize('viewAny', new Team);
    }

    public function delete($id): void
    {
        $team = Team::find($id);

        $this->authorize('delete', $team);

        $team->delete();

        $this->success(__('teams.index.delete_success'));
    }

    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => __('teams.index.headers.name')],
            ['key' => 'max_respondents', 'label' => __('teams.index.headers.max_respondents')],
            ['key' => 'respondents', 'label' => __('teams.index.headers.respondents')],
            ['key' => 'available', 'label' => __('teams.index.headers.available')],
            ['key' => 'active', 'label' => __('teams.index.headers.active')],
            ['key' => 'created_at', 'label' => __('teams.index.headers.created_at')],
        ];
    }

    public function rows(): LengthAwarePaginator
    {
        return Team::query()
            ->when($this->search, fn (Builder $q) => $q->where('name', 'like', "%$this->search%"))
            ->orderBy(...array_values($this->sortBy))
            ->paginate(5);
    }

    public function with(): array
    {
        return [
            'headers' => $this->headers(),
            'rows' => $this->rows(),
        ];
    }

    public function newPassphraseLink(Team $team)
    {
        $team->token = str()->ulid();
        $team->save();

        $this->success(__('teams.index.new_passphrase_link_success'));
    }

    public function render()
    {
        return $this->view()
            ->layout('layouts::app')
            ->title(__('teams.index.title'));
    }
};
