<div class="min-h-screen flex flex-col items-center justify-start px-4 py-8">

    {{-- Logo / Header --}}
    <div class="text-center mb-6">
        <img
            src="{{ asset('storage/images/logo.png') }}"
            alt="{{ siteName() }}"
            class="h-24 w-auto mx-auto mb-2 drop-shadow-lg"
        >
        <p class="text-gray-500 text-xs tracking-widest uppercase">{{ siteInfo()?->address ?? siteName() }}</p>
    </div>

    @if ($campaignInvalid)
    {{-- SIN CAMPAÑA ACTIVA --}}
    <div class="w-full max-w-md text-center">
        <div class="text-5xl mb-4">😔</div>
        <h2 class="text-2xl text-gray-400 font-bold mb-2">Sin campaña activa</h2>
        <p class="text-gray-500 text-sm">{{ $campaignErrorMsg }}</p>
    </div>

    @elseif ($alreadySubmitted)
    {{-- YA DEJÓ RESEÑA --}}
    <div class="w-full max-w-md text-center">
        <div class="text-6xl mb-4">🙌</div>
        <h2 class="text-2xl text-orange-400 font-bold mb-2">¡Ya dejaste tu reseña!</h2>
        <p class="text-gray-400 text-sm">
            Gracias por compartir tu experiencia con nosotros. Solo se permite una reseña por visita.
        </p>
        <p class="text-gray-600 text-xs mt-4">¡Te esperamos pronto en {{ siteName() }}! 🔥🥩</p>
    </div>

    @elseif (!$submitted)
        {{-- FORM --}}
        <div class="w-full max-w-md">

            {{-- Gift teaser --}}
            <div class="bg-[#242424] border border-[#E52B2B] rounded-xl p-4 mb-6 text-center">
                <p class="text-2xl">🎁</p>
                <p class="text-orange-400 font-bold text-lg mt-1">{{ $campaign->gift_title }}</p>
                @if ($campaign->gift_description)
                    <p class="text-gray-400 text-sm mt-1">{{ $campaign->gift_description }}</p>
                @endif
                <p class="text-gray-500 text-xs mt-2">Déjanos una reseña y te enviamos tu regalo al correo</p>
            </div>

            <form wire:submit="submit" class="space-y-5">

                {{-- Star rating --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">
                        ¿Cuántas estrellas merece {{ siteName() }}? <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2 justify-center">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="setRating({{ $i }})"
                                class="text-4xl transition-transform active:scale-90
                                   {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-600' }}">★</button>
                        @endfor
                    </div>
                    @error('rating')
                        <p class="text-red-400 text-xs mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-1">
                        Tu nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="customerName" placeholder="ej. Juan Pérez" autocomplete="name"
                        class="w-full bg-[#1c1c1c] border border-gray-700 rounded-lg px-4 py-3 text-white
                           placeholder-gray-600 focus:outline-none focus:border-orange-500 text-base" />
                    @error('customerName')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-1">
                        Tu correo electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" wire:model="customerEmail" placeholder="juan@ejemplo.com" autocomplete="email"
                        inputmode="email"
                        class="w-full bg-[#1c1c1c] border border-gray-700 rounded-lg px-4 py-3 text-white
                           placeholder-gray-600 focus:outline-none focus:border-orange-500 text-base" />
                    <p class="text-gray-600 text-xs mt-1">Aquí recibirás tu regalo 🎁</p>
                    @error('customerEmail')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Comment --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-1">
                        Cuéntanos tu experiencia <span class="text-gray-500 font-normal">(opcional)</span>
                    </label>
                    <textarea wire:model="comment" rows="3" placeholder="¿Qué fue lo que más te gustó? ¿Volvería?"
                        class="w-full bg-[#1c1c1c] border border-gray-700 rounded-lg px-4 py-3 text-white
                           placeholder-gray-600 focus:outline-none focus:border-orange-500 text-base resize-none"></textarea>
                </div>

                {{-- Submit --}}
                <button type="submit" wire:loading.attr="disabled"
                    class="w-full bg-[#E52B2B] hover:bg-red-700 active:bg-red-800 text-white font-bold
                       py-4 rounded-xl text-lg tracking-wide transition-colors
                       disabled:opacity-60 disabled:cursor-not-allowed">
                    <span wire:loading.remove>Enviar reseña y recibir mi regalo 🎁</span>
                    <span wire:loading>Enviando...</span>
                </button>

            </form>
        </div>
    @else
        {{-- SUCCESS STATE --}}
        <div class="w-full max-w-md text-center">

            <div class="text-6xl mb-4 animate-bounce">🎉</div>
            <h2 class="text-3xl text-orange-400 mb-2">¡Gracias, {{ $customerName }}!</h2>
            <p class="text-gray-400 text-sm mb-6">
                Tu reseña fue enviada. Revisa tu correo
                <strong class="text-white">{{ $customerEmail }}</strong> — ahí está tu regalo.
            </p>

            {{-- Gift code display --}}
            <div class="bg-[#242424] border-2 border-[#E52B2B] rounded-2xl p-6 mb-6">
                <p class="text-2xl mb-2">🎁</p>
                <p class="text-orange-400 font-bold text-xl mb-1">{{ $campaign->gift_title }}</p>
                @if ($campaign->gift_description)
                    <p class="text-gray-400 text-sm mb-4">{{ $campaign->gift_description }}</p>
                @endif
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-1">Tu código único</p>
                <p class="text-white text-4xl font-black tracking-[8px] font-mono">{{ $giftCode }}</p>
            </div>

            <div class="bg-[#242424] rounded-xl p-4 text-left space-y-2">
                <p class="text-yellow-400 font-semibold text-sm">📱 ¿Cómo canjear tu regalo?</p>
                <ol class="text-gray-400 text-sm space-y-1 list-decimal list-inside">
                    <li>Muestra este código (o el correo) al llegar al restaurante</li>
                    <li>El equipo lo registrará en el sistema</li>
                    <li>¡Disfruta tu regalo! 🥩🔥</li>
                </ol>
                <p class="text-gray-600 text-xs mt-2">Válido solo una vez. No acumulable con otras promociones.</p>
            </div>

        </div>
    @endif

    <p class="text-gray-700 text-xs mt-10 text-center">© {{ date('Y') }} {{ siteName() }}</p>
</div>
