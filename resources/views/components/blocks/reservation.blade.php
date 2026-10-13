@props(['data' => []])
@php
    $__wa = preg_replace('/\D+/', '', (string) (siteInfo()?->whatsapp ?: siteInfo()?->phone));
    $__max = (int) ($data['max_people'] ?? 12);
@endphp
<section class="py-24 relative overflow-hidden bg-[var(--bg_section)]" id="reservar">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <h2 class="text-4xl md:text-6xl font-['Anton'] text-[var(--text_primary)] uppercase tracking-widest mb-4">
                {{ $data['heading'] ?? 'Reserva tu mesa' }}
            </h2>
            <p class="text-[var(--text_secondary)] text-lg mb-6">
                {{ $data['subtitle'] ?? 'Cuéntanos cuándo vienes y te confirmamos por WhatsApp.' }}
            </p>
            <x-open-status />
        </div>

        <form x-data="{
                name: '', people: 2, date: '', time: '', notes: '',
                today: new Date(new Date().getTime() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 10),
                get ok() { return this.name.trim() && this.date && this.time },
                get link() {
                    const d = this.date ? new Date(this.date + 'T12:00').toLocaleDateString('es-MX', { weekday: 'long', day: 'numeric', month: 'long' }) : '';
                    const t = [
                        'Hola, quiero reservar en {{ addslashes(siteName()) }}.',
                        'Nombre: ' + this.name.trim(),
                        'Personas: ' + this.people,
                        'Fecha: ' + d,
                        'Hora: ' + this.time,
                        this.notes.trim() ? 'Notas: ' + this.notes.trim() : ''
                    ].filter(Boolean).join('\n');
                    return 'https://wa.me/{{ $__wa }}?text=' + encodeURIComponent(t);
                }
            }"
            @submit.prevent="ok && window.open(link, '_blank', 'noopener')"
            class="rounded-3xl border border-[var(--border)] bg-white/[0.04] p-6 sm:p-8 backdrop-blur-xl shadow-2xl space-y-5">
            <label class="block">
                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-[var(--text_secondary)]">Nombre</span>
                <input x-model="name" required maxlength="60" autocomplete="name"
                    class="w-full rounded-xl border border-[var(--border)] bg-black/30 px-4 py-3 text-[var(--text_primary)] placeholder-gray-500 focus:border-[var(--accent)] focus:outline-none">
            </label>
            <div class="grid grid-cols-3 gap-4">
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-[var(--text_secondary)]">Personas</span>
                    <select x-model.number="people" class="w-full rounded-xl border border-[var(--border)] bg-black/30 px-3 py-3 text-[var(--text_primary)] focus:border-[var(--accent)] focus:outline-none">
                        @for ($i = 1; $i <= $__max; $i++)<option value="{{ $i }}">{{ $i }}</option>@endfor
                    </select>
                </label>
                <label class="block col-span-1">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-[var(--text_secondary)]">Fecha</span>
                    <input type="date" x-model="date" :min="today" required class="w-full rounded-xl border border-[var(--border)] bg-black/30 px-3 py-3 text-[var(--text_primary)] focus:border-[var(--accent)] focus:outline-none [color-scheme:dark]">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-[var(--text_secondary)]">Hora</span>
                    <input type="time" x-model="time" required class="w-full rounded-xl border border-[var(--border)] bg-black/30 px-3 py-3 text-[var(--text_primary)] focus:border-[var(--accent)] focus:outline-none [color-scheme:dark]">
                </label>
            </div>
            <label class="block">
                <span class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-[var(--text_secondary)]">Notas (opcional)</span>
                <textarea x-model="notes" rows="2" maxlength="200" placeholder="Terraza, cumpleaños, silla para bebé…"
                    class="w-full rounded-xl border border-[var(--border)] bg-black/30 px-4 py-3 text-[var(--text_primary)] placeholder-gray-500 focus:border-[var(--accent)] focus:outline-none"></textarea>
            </label>
            <button type="submit" :disabled="!ok"
                class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-[#25D366] px-8 py-4 font-bold uppercase tracking-widest text-black transition disabled:cursor-not-allowed disabled:opacity-40 hover:brightness-110">
                Enviar por WhatsApp
            </button>
            <p class="text-center text-xs text-[var(--text_secondary)]">Se abre WhatsApp con tu solicitud; la reservación queda confirmada cuando te respondamos.</p>
        </form>
    </div>
</section>
