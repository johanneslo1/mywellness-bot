<script setup lang="ts">
import Card from "primevue/card";
import Message from "primevue/message";
import Button from "primevue/button";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Skeleton from 'primevue/skeleton';
import dayjs from "dayjs";
import {onBeforeUnmount, onMounted, ref, watch} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {Link} from "@inertiajs/inertia-vue3";

const props = defineProps<{
    showSuccessMessage: boolean
    searchRequest: object
    freeTimeSlots: object,
    availableFilters: object
}>();


</script>

<template>
    <div class="bg-gray-200 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <form @submit.prevent="submit">
                <Message :closable="false"><strong>Tipp:</strong> Speichere dir die URL ab, dann kannst jederzeit deinen
                    Suchauftrag einfach verwalten.
                </Message>

                <Message v-if="showSuccessMessage" severity="success">Erfolgreich! Du hast deinen Suchauftrag
                    erstellt.
                </Message>
                <Card>
                    <template #title>
                        Suchauftrag #{{ searchRequest.id }}
                    </template>
                    <template #subtitle>

                    </template>
                    <template #content>


                        <div class="border-b pb-6 mb-6">
                            <ul class=" space-y-4">
                                <li class="flex justify-between">
                                    <span class="font-semibold">Benachrichtigung an</span><span>{{
                                        searchRequest.email
                                    }}</span>
                                </li>
                                <li v-for="filter in searchRequest.filters" class="flex justify-between">
                                <span class="font-semibold">
                                    {{ filter.title }}
                                </span>
                                    <span>
                                 {{ filter.value }}
                                </span>
                                </li>
                            </ul>


                        </div>


                        <template v-if="freeTimeSlots.length > 0">
                            <p class="mb-3 text-center">Hier ist eine Auswahl an Tagen mit freien Zeitslots:</p>
                            <DataTable :value="freeTimeSlots" paginator :rows="5" tableStyle="min-width: 50rem">
                                <Column field="date" filterField="date" dataType="date" sortable header="Datum">
                                    <template #body="{ data }">
                                        {{ dayjs(data.date).format('ddd, DD.MM.YYYY') }}
                                    </template>
                                </Column>
                                <Column field="url" header="Link">
                                    <template #body="{ data }">
                                        <a :href="data.url" class="text-blue-500 hover:underline" target="_blank">Hier
                                            buchen!</a>
                                    </template>
                                </Column>
                            </DataTable>
                        </template>

                        <p class="text-center" v-else>Es sind noch keine passenden Slots vorhanden. <br> Du erhälst eine
                            E-Mail wenn sich etwas ergibt :)</p>
                    </template>
                </Card>
                <div class="flex justify-center mt-3">
                    <Link :href="'/unsubscribe?email='+ searchRequest.email">
                        <Button severity="danger" size="small" label="Suchauftrag löschen"/>
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>


