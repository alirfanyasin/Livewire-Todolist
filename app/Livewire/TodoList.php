<?php

namespace App\Livewire;

use App\Models\Todo;
use Exception;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;

    #[Rule('required|min:2|max:50')]
    public $name;

    public $search;

    public $editingTodoId;

    #[Rule('required|min:2|max:50')]
    public $editingTodoName;

    public function create()
    {
        // validation
        $validated = $this->validateOnly('name');
        // add to database
        Todo::create($validated);
        // reset value
        $this->reset(['name']);
        // flash message
        session()->flash('success', 'Created todo successfully');

        $this->resetPage();
    }


    public function delete($todoId)
    {
        try {
            Todo::findOrFail($todoId)->delete();
        } catch (Exception $error) {
            session()->flash('error', 'Failed deleted todo');
        }
    }


    public function edit($id)
    {
        $this->editingTodoId = $id;
        $this->editingTodoName = Todo::find($id)->name;
    }

    public function cancelEdit()
    {
        $this->reset('editingTodoId', 'editingTodoName');
    }

    public function update()
    {
        $this->validateOnly('editingTodoName');
        $data = Todo::find($this->editingTodoId);
        $data->update(['name' => $this->editingTodoName]);

        $this->cancelEdit();
    }


    public function toggle($id)
    {
        $todo = Todo::find($id);
        $todo->completed = !$todo->completed;
        $todo->save();
    }

    public function render()
    {
        return view('livewire.todo-list', [
            'todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(5)
        ]);
    }
}
