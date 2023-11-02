<template>
    <div class="bg-teal-400">
        <div class="px-4 max-w-6xl mx-auto h-screen space-y-4 flex justify-center flex-col">

            <Card>
                <template #title>Neuen Suchauftrag erstellen</template>

                <template #content>
                    <form @submit.prevent="form.post('search-requests')" class="pr-6">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-1">
                                <Calendar v-model="form.dates" placeholder="Bevorzugte Tage" class="w-full" date-format="dd.mm.yy" selectionMode="multiple" :manualInput="false" />

                            </div>


                            <div v-for="filter in availableFilters" class="col-span-1">

                                <Dropdown v-if="filter.input_type === 'select'"
                                          :multiple="filter.allow_multiple_values"
                                          v-model="form[filter.name]" :options="filter.options" option-label="title"
                                          option-value="value" :placeholder="filter.title" class="w-full md:w-14rem"/>
                            </div>
                        </div>
                        <div class="flex justify-center">
                            <Button label="Suchauftrag erstellen"/>
                        </div>
                    </form>
                </template>
            </Card>

            <Card>
                <template #title>Aktuelle Suchaufträge</template>
                <template #content>


                            <DataTable :value="searchRequests" class="w-full" table-style="width: 100%">
                                <Column field="id" header="ID"></Column>
                                <Column field="params" header="Suchbegriff">
                                    <template #body="{data}">
                                        Filiale(n) {{data.params.outlet_ids }},  Personen {{ data.params.capacity}}, Uhrzeit {{ data.params.day_time}}, Dauer {{ data.params.duration}}, Suite-Kategorie {{ data.params.suite_type }}
                                    </template>
                                </Column>
                                <Column field="email" header="E-Mail"></Column>
                                <Column field="last_check_at" data-type="date" header="Zuletzt geprüft um">
                                    <template #body="{data}">
                                        {{ data.last_check_at ? dayjs(data.last_check_at).format('DD.MM.YYYY HH:mm') : 'Unbekannt' }}
                                    </template>
                                </Column>
                                <Column field="active" header="Aktiv">

                                    <template #body="{data}">
                                        <ToggleButton off-icon="pi pi-play"  on-icon="pi pi-pause" @update:model-value="toggleSearchRequest(data, $event)" :model-value="data.active" class="text-md" on-label="Aktiv" off-label="Inaktiv" />
                                    </template>

                                </Column>

                                <Column field="actions" header="Aktionen">

                                    <template #body="{data}">

                                        <Button icon="pi pi-trash" severity="danger" size="small" title="Löschen" />

                                    </template>

                                </Column>
                            </DataTable>

                </template>
            </Card>
        </div>
    </div>
</template>

<script setup>
import Button from "primevue/button";
import Card from "primevue/card";
import {useForm} from "@inertiajs/inertia-vue3";
import Dropdown from 'primevue/dropdown';
import ToggleButton from 'primevue/togglebutton';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import {Inertia} from "@inertiajs/inertia";
import Calendar from 'primevue/calendar';


const props = defineProps({
    availableFilters: Array,
    searchRequests: Array
});


const form = useForm({
    ...props.availableFilters.reduce((acc, filter) => {
        acc[filter.name] = null;
        return acc;
    }, {}),
    dates: [],
});


function toggleSearchRequest(searchRequest, event) {

    Inertia.post(`/search-requests/${searchRequest.id}/toggle`, {
        active: event
    })
    console.log(searchRequest, event);
}


</script>

<style>
html, body {
    font-family: 'Open Sans', sans-serif;
}
</style>

