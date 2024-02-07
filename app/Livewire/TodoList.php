<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;

    #[Rule('required|min:2')]
    public $name;

    public $search;

    public function create()
    {
        // validation
        $validated = $this->validateOnly('name');
        // add to database
        Todo::create($validated);
        // reset value
        $this->reset(['name']);
        // flash message
        request()->session()->flash('success', 'Created todo successfully');
    }


    public function delete($todoId)
    {
        Todo::find($todoId)->delete();
    }

    public function render()
    {
        return view('livewire.todo-list', [
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5)
        ]);
    }
}
