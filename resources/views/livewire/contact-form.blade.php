<div>
    @if($this->submitted)
        {{-- ═══ SUCCESS STATE ═══ --}}
        <div class="text-center py-12 px-6">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-[var(--accent_green)]/20 border-2 border-[var(--accent_green)] rounded-full mb-6">
                <svg class="w-10 h-10 text-[#18833b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-2xl font-['Anton'] text-white uppercase tracking-widest mb-3">
                ¡Mensaje Enviado!
            </h3>
            <p class="text-gray-300 text-lg">
                {{ $this->successMessage }}
            </p>
        </div>
    @else
        <form wire:submit="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($this->blockData['fields'] ?? [] as $i => $field)
                    @php
                        $type        = $field['type'] ?? 'text';
                        $label       = $field['label'] ?? '';
                        $placeholder = $field['placeholder'] ?? '';
                        $required    = $field['required'] ?? true;
                        $isFullWidth = in_array($type, ['textarea', 'select']);
                    @endphp

                    <div class="{{ $isFullWidth ? 'md:col-span-2' : '' }}">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                            {{ $label }}
                            @if($required)<span class="text-[#E52B2B] ml-0.5">*</span>@endif
                        </label>

                        @if($type === 'textarea')
                            <textarea
                                wire:model="formValues.{{ $i }}"
                                placeholder="{{ $placeholder }}"
                                rows="4"
                                class="w-full bg-[var(--bg_section)] border border-[var(--border)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] rounded-lg px-4 py-3 text-[var(--text_secondary)] placeholder-gray-600 transition-colors outline-none resize-none"
                                {{ $required ? 'required' : '' }}
                            ></textarea>

                        @elseif($type === 'select')
                            @php
                                $rawOptions = $field['options'] ?? '';
                                $options = array_filter(array_map('trim', explode("\n", $rawOptions)));
                            @endphp
                            <select
                                wire:model="formValues.{{ $i }}"
                                class="w-full bg-[var(--bg_section)] border border-[var(--border)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] rounded-lg px-4 py-3 text-[var(--text_secondary)] transition-colors outline-none"
                                {{ $required ? 'required' : '' }}
                            >
                                <option value="">{{ $placeholder ?: 'Selecciona una opción' }}</option>
                                @foreach($options as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>

                        @else
                            <input
                                type="{{ $type }}"
                                wire:model="formValues.{{ $i }}"
                                placeholder="{{ $placeholder }}"
                                class="w-full bg-[var(--bg_section)] border border-[var(--border)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] rounded-lg px-4 py-3 text-[var(--text_secondary)] placeholder-gray-600 transition-colors outline-none"
                                {{ $required ? 'required' : '' }}
                            >
                        @endif

                        @error("formValues.{$i}")
                            <p class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                @endforeach

                {{-- Captcha matemático --}}
                @if($this->blockData['show_captcha'] ?? true)
                    <div class="md:col-span-2">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-[var(--bg_section)] border border-[var(--border)] rounded-lg px-5 py-4">
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Anti-bot:</span>
                                <div class="bg-[var(--bg_primary)] border border-[var(--border_light)] rounded px-4 py-2 font-['Anton'] text-white text-xl tracking-widest select-none">
                                    {{ $this->captchaA }} + {{ $this->captchaB }} = ?
                                </div>
                            </div>
                            <div class="flex-1 w-full sm:w-auto">
                                <input
                                    type="number"
                                    wire:model="captchaAnswer"
                                    placeholder="Tu respuesta"
                                    class="w-full sm:w-32 bg-[var(--bg_primary)] border border-[var(--border)] focus:border-[var(--accent)] focus:ring-1 focus:ring-[var(--accent)] rounded-lg px-4 py-2.5 text-[var(--text_secondary)] placeholder-gray-600 transition-colors outline-none"
                                >
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Submit --}}
            <div class="mt-8">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-60 cursor-wait"
                    class="inline-flex items-center justify-center gap-3 bg-[var(--accent)] hover:bg-[var(--button_hover)] disabled:opacity-60 text-white px-8 py-4 rounded font-bold uppercase tracking-widest text-sm transition-all duration-300 shadow-[0_0_20px_rgba(229,43,43,0.3)] hover:shadow-[0_0_35px_rgba(229,43,43,0.5)] hover:-translate-y-0.5 min-w-48"
                >
                    <span wire:loading.remove>
                        {{ $this->blockData['submit_label'] ?? 'Enviar Mensaje' }}
                    </span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        Enviando...
                    </span>
                </button>
            </div>
        </form>
    @endif
</div>
