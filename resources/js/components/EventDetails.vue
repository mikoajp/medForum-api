<template>
    <div ref="eventDetails" class="single-event-container event-details">
        <a href="#" @click.prevent="$emit('deselect-event')" class="back-link">Powrót do listy</a>

        <h2>{{ event.title }}</h2>
        <div class="event-date">{{ formatDate(event.event_date) }}</div>
        <p class="event-description">{{ event.description }}</p>

        <div class="countdown-container" v-if="!isEventPast(event)">
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
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import '../../css/EventDetails.css';
export default {
    name: 'EventDetails',
    props: {
        event: {
            type: Object,
            required: true
        }
    },
    emits: ['deselect-event'],
    setup(props) {
        const eventDetails = ref(null);
        const countdown = ref({ days: 0, hours: 0, minutes: 0, seconds: 0 });
        let countdownInterval = null;

        const updateCountdown = () => {
            const now = new Date();
            const eventDate = new Date(props.event.event_date);
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

        const isEventPast = () => {
            return new Date(props.event.event_date) < new Date();
        };

        onMounted(() => {
            startCountdown();
        });

        onUnmounted(() => {
            if (countdownInterval) clearInterval(countdownInterval);
        });

        return { eventDetails, countdown, formatDate, isEventPast };
    }
};
</script>

