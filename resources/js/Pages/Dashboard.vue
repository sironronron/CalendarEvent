<template>
    <app-layout title="Dashboard">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <events
                    :current_date="current_date"
                    :date_range="date_with_events_range"
                />
            </div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Events from "@/Jetstream/Events.vue";

export default defineComponent({
    components: {
        AppLayout,
        Events,
    },

    props: {
        current_date: {
            type: String,
        },

        date_with_events_range: {
            type: Object,
        },
    },
});
</script>
