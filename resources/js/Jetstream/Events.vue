<template>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <form
                    @submit.prevent="addNewEvent"
                    method="POST"
                    class="space-y-6"
                >
                    <div>
                        <jet-label for="event_name" value="Event Name" />
                        <jet-input
                            id="event_name"
                            type="text"
                            class="mt-2 block w-full"
                            v-model="form.event_name"
                            placeholder="Event Name"
                        />
                        <jet-input-error
                            :message="form.errors.event_name"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <div
                            class="
                                grid grid-cols-1
                                gap-4
                                md:grid-cols-2 md:gap-6
                            "
                        >
                            <div>
                                <jet-label for="start_date" value="From" />
                                <jet-input
                                    id="start_date"
                                    type="date"
                                    class="mt-2 block w-full"
                                    v-model="form.start_date"
                                />
                                <jet-input-error
                                    :message="form.errors.start_date"
                                    class="mt-2"
                                />
                            </div>

                            <div>
                                <jet-label for="end_date" value="To" />
                                <jet-input
                                    id="end_date"
                                    type="date"
                                    class="mt-2 block w-full"
                                    v-model="form.end_date"
                                />
                                <jet-input-error
                                    :message="form.errors.end_date"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <jet-label for="monday">
                                <div class="flex items-center">
                                    <jet-checkbox
                                        name="monday"
                                        id="monday"
                                        v-model:checked="form.days"
                                        value="1"
                                    />

                                    <div class="ml-2">Monday</div>
                                </div>
                            </jet-label>
                        </div>

                        <div>
                            <jet-label for="tuesday">
                                <div class="flex items-center">
                                    <jet-checkbox
                                        name="tuesday"
                                        id="tuesday"
                                        v-model:checked="form.days"
                                        value="2"
                                    />

                                    <div class="ml-2">Tuesday</div>
                                </div>
                            </jet-label>
                        </div>

                        <div>
                            <jet-label for="wednesday">
                                <div class="flex items-center">
                                    <jet-checkbox
                                        name="wednesday"
                                        id="wednesday"
                                        v-model:checked="form.days"
                                        value="3"
                                    />

                                    <div class="ml-2">Wednesday</div>
                                </div>
                            </jet-label>
                        </div>

                        <div>
                            <jet-label for="thursday">
                                <div class="flex items-center">
                                    <jet-checkbox
                                        name="thursday"
                                        id="thursday"
                                        v-model:checked="form.days"
                                        value="4"
                                    />

                                    <div class="ml-2">Thursday</div>
                                </div>
                            </jet-label>
                        </div>

                        <div>
                            <jet-label for="friday">
                                <div class="flex items-center">
                                    <jet-checkbox
                                        name="friday"
                                        id="friday"
                                        v-model:checked="form.days"
                                        value="5"
                                    />

                                    <div class="ml-2">Friday</div>
                                </div>
                            </jet-label>
                        </div>

                        <div>
                            <jet-label for="saturday">
                                <div class="flex items-center">
                                    <jet-checkbox
                                        name="saturday"
                                        id="saturday"
                                        v-model:checked="form.days"
                                        value="6"
                                    />

                                    <div class="ml-2">Saturday</div>
                                </div>
                            </jet-label>
                        </div>

                        <div>
                            <jet-label for="sunday">
                                <div class="flex items-center">
                                    <jet-checkbox
                                        name="sunday"
                                        id="sunday"
                                        v-model:checked="form.days"
                                        value="0"
                                    />

                                    <div class="ml-2">Sunday</div>
                                </div>
                            </jet-label>
                        </div>
                    </div>

                    <div>
                        <div>
                            <jet-button
                                :class="{
                                    'opacity-25': form.processing,
                                }"
                                :disabled="form.processing"
                            >
                                Save Event
                            </jet-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6 space-y-4">
                <div class="font-semibold text-2xl">
                    {{ current_date }}
                </div>

                <div>
                    <div
                        v-for="(item, key) in date_range"
                        :key="key"
                        class="
                            border-b
                            last:border-none
                            border-gray-200
                            text-sm
                        "
                    >
                        <div
                            :class="{ 'bg-green-300': item.event != null }"
                            class="py-4 px-2"
                        >
                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div>
                                    {{ formatDate(item) }}
                                </div>

                                <div>
                                    {{ item.event }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetCheckbox from "@/Jetstream/Checkbox.vue";
import JetButton from "@/Jetstream/Button.vue";
import moment from "moment";

export default defineComponent({
    components: {
        JetInput,
        JetLabel,
        JetInputError,
        JetCheckbox,
        JetButton,
    },

    props: ["current_date", "date_range"],

    data() {
        return {
            form: this.$inertia.form({
                event_name: "",
                start_date: "",
                end_date: "",
                days: [],
            }),
        };
    },

    methods: {
        addNewEvent() {
            this.form.post(route("event.store"), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => this.form.reset(),
            });
        },

        formatDate(date) {
            var dt = moment(date).format("YYYY-MM-DD");
            return moment(date).format("DD dddd");
        },
    },
});
</script>

<style></style>
