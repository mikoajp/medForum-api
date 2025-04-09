<template>
    <div class="events-container">
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
                    <a href="#" @click.prevent="$emit('select-event', event.id)">
                        <span class="event-title">{{ event.title }}</span>
                        <span class="event-date-small">
                            <span class="date-text">{{ formatDate(event.event_date) }}</span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { computed } from 'vue';
import '../../css/EventList.css';
export default {
    name: 'EventList',
    props: {
        events: {
            type: Array,
            required: true
        },
        sortKey: String,
        sortOrder: String
    },
    emits: ['select-event', 'update:sortKey', 'update:sortOrder'],
    setup(props, { emit }) {
        const sortedEvents = computed(() => {
            return [...props.events].sort((a, b) => {
                const modifier = props.sortOrder === 'asc' ? 1 : -1;
                if (props.sortKey === 'title') return modifier * (a.title || '').localeCompare(b.title || '');
                if (props.sortKey === 'event_date') return modifier * (new Date(a.event_date || 0) - new Date(b.event_date || 0));
                return 0;
            });
        });

        const sortEvents = (key) => {
            if (props.sortKey === key) {
                emit('update:sortOrder', props.sortOrder === 'asc' ? 'desc' : 'asc');
            } else {
                emit('update:sortKey', key);
                emit('update:sortOrder', 'asc');
            }
        };

        const toggleSortOrder = () => {
            emit('update:sortOrder', props.sortOrder === 'asc' ? 'desc' : 'asc');
        };

        const formatDate = (dateString) => {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('pl-PL', options);
        };

        const isEventSoon = (event) => {
            const now = new Date();
            const eventDate = new Date(event.event_date);
            const diffDays = Math.floor((eventDate - now) / (1000 * 60 * 60 * 24));
            return diffDays >= 0 && diffDays <= 7;
        };

        const isEventPast = (event) => {
            return new Date(event.event_date) < new Date();
        };

        return { sortedEvents, sortEvents, toggleSortOrder, formatDate, isEventSoon, isEventPast };
    }
};
</script>
