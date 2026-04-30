<div x-data="{ mode: 'desktop', device: '390' }" style="display:flex; flex-direction:column; height:80vh;">

    {{-- Barra superior --}}
    <div style="display:flex; align-items:center; gap:10px; padding:8px 16px; background:#f59e0b; color:#000; font-size:11px; font-weight:700; border-radius:6px 6px 0 0; flex-shrink:0; flex-wrap:wrap;">

        {{-- Ícono ojo --}}
        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>

        <span>VISTA PREVIA — Los cambios sin guardar no se reflejan aquí</span>

        {{-- Toggle Desktop / Móvil --}}
        <div style="display:flex; background:#d97706; border-radius:6px; padding:2px; gap:2px; margin-left:4px;">
            <button type="button" @click="mode = 'desktop'"
                x-bind:style="mode === 'desktop'
                    ? 'background:#fff; color:#b45309; border-radius:4px; padding:3px 10px; font-size:11px; font-weight:700; border:none; cursor:pointer; display:flex; align-items:center; gap:4px;'
                    : 'background:transparent; color:#000; border-radius:4px; padding:3px 10px; font-size:11px; font-weight:700; border:none; cursor:pointer; display:flex; align-items:center; gap:4px;'">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2"/><path stroke-linecap="round" d="M8 21h8M12 17v4"/>
                </svg>
                Desktop
            </button>
            <button type="button" @click="mode = 'mobile'"
                x-bind:style="mode === 'mobile'
                    ? 'background:#fff; color:#b45309; border-radius:4px; padding:3px 10px; font-size:11px; font-weight:700; border:none; cursor:pointer; display:flex; align-items:center; gap:4px;'
                    : 'background:transparent; color:#000; border-radius:4px; padding:3px 10px; font-size:11px; font-weight:700; border:none; cursor:pointer; display:flex; align-items:center; gap:4px;'">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="5" y="2" width="14" height="20" rx="2"/><path stroke-linecap="round" d="M12 18h.01"/>
                </svg>
                Móvil
            </button>
        </div>

        {{-- Selector de dispositivo (solo visible en móvil) --}}
        <div x-show="mode === 'mobile'" style="display:flex; align-items:center; gap:4px; background:#d97706; border-radius:6px; padding:2px; gap:2px;">
            <template x-for="d in [
                { key: '360', label: 'Android S', w: '360px' },
                { key: '390', label: 'iPhone 14', w: '390px' },
                { key: '430', label: 'iPhone 14 Max', w: '430px' },
                { key: '412', label: 'Pixel 7', w: '412px' }
            ]" :key="d.key">
                <button type="button" @click="device = d.key"
                    x-bind:style="device === d.key
                        ? 'background:#fff; color:#b45309; border-radius:4px; padding:3px 9px; font-size:10px; font-weight:700; border:none; cursor:pointer; white-space:nowrap;'
                        : 'background:transparent; color:#000; border-radius:4px; padding:3px 9px; font-size:10px; font-weight:700; border:none; cursor:pointer; white-space:nowrap;'"
                    x-text="d.label">
                </button>
            </template>
        </div>

        {{-- Link pestaña nueva --}}
        <a href="{{ $url }}" target="_blank" style="margin-left:auto; display:flex; align-items:center; gap:4px; text-decoration:underline; color:#000; font-size:11px; font-weight:700; white-space:nowrap;">
            Abrir en pestaña nueva
            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
        </a>
    </div>

    {{-- Área de previsualización --}}
    <div style="flex:1; overflow:hidden; background:#e5e7eb; position:relative; min-height:0;">

        {{-- Desktop --}}
        <iframe
            x-show="mode === 'desktop'"
            src="{{ $url }}"
            style="position:absolute; inset:0; width:100%; height:100%; border:none;"
            loading="lazy"
        ></iframe>

        {{-- Móvil --}}
        <div x-show="mode === 'mobile'"
             style="position:absolute; inset:0; overflow:hidden; padding-top:16px; padding-bottom:10px; box-sizing:border-box; text-align:center;">

            {{-- Marco del teléfono — centrado con margin:auto --}}
            <div x-bind:style="'display:inline-block; vertical-align:top; width:' + device + 'px; max-width:calc(100% - 32px); height:calc(100% - 28px); border-radius:36px; border:5px solid #1f2937; box-shadow:0 20px 60px rgba(0,0,0,0.5); overflow:hidden; background:#1f2937; position:relative;'">
                {{-- Notch --}}
                <div style="position:absolute; top:0; left:50%; transform:translateX(-50%); width:80px; height:22px; background:#1f2937; border-radius:0 0 12px 12px; z-index:10;"></div>
                <iframe
                    x-bind:style="'width:100%; height:100%; border:none; display:block; padding-top:22px; box-sizing:border-box;'"
                    src="{{ $url }}"
                    loading="lazy"
                ></iframe>
            </div>

            {{-- Etiqueta --}}
            <p x-text="{ '360': 'Android Small — 360px', '390': 'iPhone 14 Pro — 390px', '430': 'iPhone 14 Plus — 430px', '412': 'Google Pixel 7 — 412px' }[device]"
               style="margin-top:6px; font-size:10px; color:#4b5563; font-weight:600; letter-spacing:0.05em;"></p>
        </div>

    </div>
</div>
