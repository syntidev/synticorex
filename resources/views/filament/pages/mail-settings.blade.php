<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}
        <button type="submit" class="fi-btn fi-btn-primary mt-4 px-4 py-2 rounded-lg">
            Guardar
        </button>
    </form>
</x-filament-panels::page>
