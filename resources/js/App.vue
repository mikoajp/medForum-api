<template>
    <div id="app">
        <h1>Wydarzenia</h1>
        <EventList
            :events="events"
            v-model:sort-key="sortKey"
            v-model:sort-order="sortOrder"
            @select-event="selectEvent"
        />
        <EventDetails
            v-if="selectedEvent"
            :event="selectedEvent"
            @deselect-event="deselectEvent"
        />
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import EventList from './components/EventList.vue';
import EventDetails from './components/EventDetails.vue';
import '../css/app.css';
export default {
    name: 'App',
    components: { EventList, EventDetails },
    setup() {
        const events = ref([]);
        const selectedEvent = ref(null);
        const sortKey = ref('title');
        const sortOrder = ref('asc');

        const fetchEvents = async () => {
            try {
                const response = await axios.get('/api/events');
                events.value = response.data || [];
                console.log('Fetched events:', events.value);
            } catch (error) {
                console.error('Błąd podczas pobierania wydarzeń:', error);
            }
        };

        const selectEvent = async (id) => {
            try {
                const response = await axios.get(`/api/events/${id}`);
                selectedEvent.value = response.data;
                setTimeout(() => {
                    document.querySelector('.single-event-container')?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            } catch (error) {
                console.error('Błąd podczas pobierania szczegółów wydarzenia:', error);
            }
        };

        const deselectEvent = () => {
            selectedEvent.value = null;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        onMounted(() => {
            fetchEvents();
        });

        return { events, selectedEvent, sortKey, sortOrder, selectEvent, deselectEvent };
    }
};
</script>
