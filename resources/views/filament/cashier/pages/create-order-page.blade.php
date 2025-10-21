<x-filament-panels::page>
    <form wire:submit="create">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament-panels::form.actions
                :actions="$this->getFormActions()"
                alignment="start"
            />
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
