<?php

use App\Models\User;
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
        $this->authorize('viewAny', new User);
    }

    public function delete($id): void
    {
        $user = User::find($id);

        $this->authorize('delete', $user);

        $user->delete();

        $this->success(__('users.index.delete_success'));
    }

    public function headers(): array
    {
        return [
            ['key' => 'name', 'label' => __('users.index.headers.name')],
            ['key' => 'email', 'label' => __('users.index.headers.email')],
            ['key' => 'admin', 'label' => __('users.index.headers.admin')],
            ['key' => 'created_at', 'label' => __('users.index.headers.created_at')],
        ];
    }

    public function rows(): LengthAwarePaginator
    {
        return User::query()
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

    public function render()
    {
        return $this->view()
            ->layout('layouts::app')
            ->title(__('users.index.title'));
    }
};
