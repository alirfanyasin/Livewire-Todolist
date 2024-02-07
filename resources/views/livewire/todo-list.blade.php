<div>
  {{-- Component Create Todo --}}
  @include('livewire.includes.create-todo')

  {{-- Component Search Box --}}
  @include('livewire.includes.search-card')


  {{-- Component Todos List --}}
  <div id="todos-list">
    @foreach ($todos as $todo)
      @include('livewire.includes.todo-card')
    @endforeach

    <div class="my-2">
      <!-- Pagination goes here -->
      {{ $todos->links() }}
    </div>
  </div>
</div>
