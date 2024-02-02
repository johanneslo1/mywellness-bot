<script setup lang="ts" >
import { ref } from "vue";
import Steps from 'primevue/steps';
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import {useForm} from "@inertiajs/inertia-vue3";
import Dropdown from "primevue/dropdown";
import Calendar from "primevue/calendar";
import {Inertia} from "@inertiajs/inertia";
import {useToastWatcher} from "../Uses/UseToastWatcher";


const items = ref([
    {
        label: 'E-Mail Adresse'
    },
    {
        label: 'Bevorzugte Suite'
    },
]);



const props = withDefaults(defineProps<{
    availableFilters: [],
    step: number,
}>(), {step: 1});


const form = useForm({
    filters: props.availableFilters.reduce((acc, filter) => {
        acc[filter.name] = null;
        return acc;
    }, {}),
    dates: [],
    email: 'technikclou@gmail.com'

});


function submit() {

    form.post('/start?step=' + props.step, {
        replace: true,
        preserveState: true
    });

}


function toggleSearchRequest(searchRequest, event) {

    Inertia.post(`/search-requests/${searchRequest.id}/toggle`, {
        active: event
    })
    console.log(searchRequest, event);
}

useToastWatcher();
</script>

<template>
  <div class="bg-gray-200 h-screen flex items-center">
      <div class="max-w-2xl mx-auto">
          <form @submit.prevent="submit">
             <Card>
                  <template #title>
                      {{ step === 1 ? 'E-Mail Adresse' : 'Bevorzugte Suite' }}
                  </template>
                  <template #subtitle>
                        {{ step === 1 ? 'Du bekommst per E-Mail die verfügbaren Zeitslots zugeschickt. Daher braucht der Bot deine Mail.' : 'Stelle nun ein, an welchen Suits du interessiert bist.' }}
                  </template>
                  <template #content>
                      <div v-if="step === 1">
                          <InputText class="w-full" v-model="form.email" type="email" placeholder="E-Mail Adresse" />
                      </div>
                      <div v-else-if="step === 2">

                          <div class="grid grid-cols-3 gap-6">
                              <div class="col-span-1">
                                  <Calendar v-model="form.filters.date" placeholder="Bevorzugte Tage" class="w-full" date-format="dd.mm.yy" selectionMode="multiple" :manualInput="false" />

                              </div>

                              <div v-for="filter in availableFilters" class="col-span-1">
                                  <Dropdown v-if="filter.input_type === 'select'"
                                            :multiple="filter.allow_multiple_values"
                                            v-model="form.filters[filter.name]" :options="filter.options" option-label="title"
                                            option-value="value" :placeholder="filter.title" class="w-full md:w-14rem"/>
                              </div>
                          </div>
                      </div>

                  </template>

                  <template #footer>
                      <div class="flex justify-end">
                          <Button type="submit" :label="step === 1 ? 'Weiter' : 'Suchauftrag erstellen'" />
                      </div>
                  </template>
              </Card>
          </form>
      </div>
  </div>

</template>
