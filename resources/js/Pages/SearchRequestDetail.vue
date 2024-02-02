<script setup lang="ts">
import Button from "primevue/button";
import Card from "primevue/card";
import Message from "primevue/message";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import dayjs from "dayjs";

const props = defineProps<{
    showSuccessMessage: boolean
    searchRequest: object
    freeTimeSlots: object
}>();
</script>

<template>
    <div class="bg-gray-200 h-screen flex items-center">
        <div class="max-w-2xl mx-auto">
            <form @submit.prevent="submit">
                <Card>
                    <template #title>
                        Suchauftrag #{{ searchRequest.id }}
                    </template>
                    <template #subtitle>

                    </template>
                    <template #content>
                        <Message v-if="showSuccessMessage" severity="success" :closable="false">Erfolgreich! Du hast deinen Suchauftrag
                            erstellt.
                        </Message>


                        <ul class="border-b pb-6 mb-6">
                            <li v-for="filter in searchRequest.filters" class="flex justify-between">
                                <span class="font-bold">
                                    {{ filter.title }}
                                </span>

                                <span>
                                 {{ filter.value }}
                                </span>
                            </li>
                        </ul>


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

                    <template #footer>
                        <!--                        <div class="flex justify-end">-->
                        <!--                            <Button type="submit" :label="step === 1 ? 'Weiter' : 'Suchauftrag erstellen'" />-->
                        <!--                        </div>-->
                    </template>
                </Card>
            </form>
        </div>
    </div>
</template>


