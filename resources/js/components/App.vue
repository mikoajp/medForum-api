<template>
    <div id="app">
        <h1>Wydarzenia</h1>

        <div class="sort-controls">
            <button @click="sortEvents('title')">Sortuj wg tytułu</button>
            <button @click="sortEvents('event_date')">Sortuj wg daty</button>
            <button @click="toggleSortOrder">
                Zmień kolejność ({{ sortOrder === 'asc' ? '↑' : '↓' }})
            </button>
        </div>

        <div class="events-list">
            <ul>
                <li v-for="event in sortedEvents" :key="event.id"
                    :class="{'event-soon': isEventSoon(event), 'event-past': isEventPast(event)}">
                    <a href="#" @click.prevent="selectEvent(event.id)">
                        <span class="event-title">{{ event.title }}</span>
                        <span class="event-date-small">{{ formatDate(event.event_date) }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div v-if="selectedEvent" id="event-details" ref="eventDetails" class="single-event-container">
            <a href="#" @click.prevent="deselectEvent" class="back-link">Powrót do listy</a>

            <h2>{{ selectedEvent.title }}</h2>
            <div class="event-date">{{ formatDate(selectedEvent.event_date) }}</div>
            <p class="event-description">{{ selectedEvent.description }}</p>

            <div class="countdown-container" v-if="!isEventPast(selectedEvent)">
                <p class="countdown-title">Pozostało do wydarzenia:</p>
                <div class="countdown">
                    <div class="countdown-item">
                        <div class="countdown-value">{{ countdown.days }}</div>
                        <div class="countdown-label">dni</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value">{{ countdown.hours }}</div>
                        <div class="countdown-label">godzin</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value">{{ countdown.minutes }}</div>
                        <div class="countdown-label">minut</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-value">{{ countdown.seconds }}</div>
                        <div class="countdown-label">sekund</div>
                    </div>
                </div>
            </div>

            <div v-else class="event-past-message">
                To wydarzenie już się odbyło.
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue';
import axios from 'axios';

export default {
    name: 'App',
    setup() {
        const events = ref([]);
        const selectedEvent = ref(null);
        const sortKey = ref('title');
        const sortOrder = ref('asc');
        const countdown = ref({ days: 0, hours: 0, minutes: 0, seconds: 0 });
        const eventDetails = ref(null);
        let countdownInterval = null;

        const fetchEvents = async () => {
            try {
                const response = await axios.get('/api/events');
                events.value = response.data;
            } catch (error) {
                console.error('Błąd podczas pobierania wydarzeń:', error);
            }
        };

        const selectEvent = async (id) => {
            try {
                const response = await axios.get(`/api/events/${id}`);
                selectedEvent.value = response.data;
                startCountdown();

                // Po załadowaniu wydarzenia, poczekaj na zaktualizowanie DOM i przewiń do szczegółów
                await nextTick();
                scrollToEventDetails();
            } catch (error) {
                console.error('Błąd podczas pobierania szczegółów wydarzenia:', error);
            }
        };

        const scrollToEventDetails = () => {
            if (eventDetails.value) {
                eventDetails.value.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        };

        const deselectEvent = () => {
            selectedEvent.value = null;
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
            // Przewiń z powrotem do góry po powrocie do listy
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        const sortedEvents = computed(() => {
            return [...events.value].sort((a, b) => {
                const modifier = sortOrder.value === 'asc' ? 1 : -1;
                if (sortKey.value === 'title') return modifier * a.title.localeCompare(b.title);
                if (sortKey.value === 'event_date') return modifier * (new Date(a.event_date) - new Date(b.event_date));
                return 0;
            });
        });

        const sortEvents = (key) => {
            if (sortKey.value === key) {
                toggleSortOrder();
            } else {
                sortKey.value = key;
                sortOrder.value = 'asc';
            }
        };

        const toggleSortOrder = () => {
            sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
        };

        const updateCountdown = () => {
            if (!selectedEvent.value) return;
            const now = new Date();
            const eventDate = new Date(selectedEvent.value.event_date);
            const diff = eventDate - now;

            if (diff <= 0) {
                clearInterval(countdownInterval);
                countdown.value = { days: 0, hours: 0, minutes: 0, seconds: 0 };
                return;
            }

            countdown.value = {
                days: Math.floor(diff / (1000 * 60 * 60 * 24)),
                hours: Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                minutes: Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60)),
                seconds: Math.floor((diff % (1000 * 60)) / 1000),
            };
        };

        const startCountdown = () => {
            if (countdownInterval) clearInterval(countdownInterval);
            updateCountdown();
            countdownInterval = setInterval(updateCountdown, 1000);
        };

        const formatDate = (dateString) => {
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleDateString('pl-PL', options);
        };

        const isEventSoon = (event) => {
            const now = new Date();
            const eventDate = new Date(event.event_date);
            const diffDays = Math.floor((eventDate - now) / (1000 * 60 * 60 * 24));
            return diffDays >= 0 && diffDays <= 7;
        };

        const isEventPast = (event) => {
            const now = new Date();
            const eventDate = new Date(event.event_date);
            return eventDate < now;
        };

        onMounted(() => {
            fetchEvents();
        });

        onUnmounted(() => {
            if (countdownInterval) clearInterval(countdownInterval);
        });

        return {
            events,
            selectedEvent,
            sortedEvents,
            sortKey,
            sortOrder,
            countdown,
            eventDetails,
            fetchEvents,
            selectEvent,
            deselectEvent,
            sortEvents,
            toggleSortOrder,
            formatDate,
            isEventSoon,
            isEventPast
        };
    },
};
</script>
