<template>
    <div class="lg:w-2/3">
        <div ref="mapContainer" class="bg-gray-200 rounded-xl shadow-lg overflow-hidden h-96 lg:h-125"></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const mapContainer = ref(null)

const center = [52.285429, 104.288476]

const loadYandexMaps = () => {
    return new Promise((resolve, reject) => {
        if (window.ymaps) {
            resolve(window.ymaps)
            return
        }

        const script = document.createElement('script')
        script.src = 'https://api-maps.yandex.ru/2.1/?apikey=ваш_api_ключ&lang=ru_RU'
        script.type = 'text/javascript'

        script.onload = () => {
            window.ymaps.ready(resolve)
        }

        script.onerror = reject

        document.head.appendChild(script)
    })
}

const initMap = () => {
    if (!mapContainer.value) return

    const map = new window.ymaps.Map(mapContainer.value, {
        center: center,
        zoom: 17,
        controls: ['zoomControl', 'fullscreenControl']
    })

    const placemark = new window.ymaps.Placemark(center, {
        hintContent: 'SIN-BMX',
        balloonContent: `
            <div style="padding: 10px;">
                <strong>SIN-BMX</strong><br>
                г. Иркутск, ул. Горького, 42<br>
                Цокольный этаж<br>
                <a href="tel:+79834020663" style="color: #3b82f6;">+7 983 402-06-63</a>
            </div>
        `
    }, {
        preset: 'islands#redDotIconWithCaption',
        iconCaption: 'SIN-BMX'
    })

    map.geoObjects.add(placemark)
    placemark.balloon.open()
}

onMounted(async () => {
    try {
        await loadYandexMaps()
        initMap()
    } catch (error) {
        console.error('Ошибка загрузки карты:', error)
        if (mapContainer.value) {
            mapContainer.value.innerHTML = `
                <div class="h-full flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <p class="text-gray-500 font-medium">Не удалось загрузить карту</p>
                        <p class="text-gray-400 text-sm mt-2">г. Иркутск, ул. Горького, 42</p>
                    </div>
                </div>
            `
        }
    }
})
</script>
