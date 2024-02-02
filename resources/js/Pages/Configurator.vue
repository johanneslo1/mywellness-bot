<script setup lang="ts">
import {ref} from "vue";
import Steps from 'primevue/steps';
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import {useForm} from "@inertiajs/inertia-vue3";
import Dropdown from "primevue/dropdown";
import MultiSelect from "primevue/multiselect";
import Calendar from "primevue/calendar";
import {Link} from "@inertiajs/inertia-vue3";
import {useToastWatcher} from "../Uses/UseToastWatcher";
import Checkbox from 'primevue/checkbox';


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
    email: ''

});


function submit() {

    form.post('/start?step=' + props.step, {
        replace: true,
        preserveState: true
    });

}



const acceptPrivacy = ref(false);
useToastWatcher();
</script>

<template>
    <div class="bg-gray-200 h-screen flex items-center">
        <div class="max-w-2xl mx-auto">
            <form @submit.prevent="submit">
                <Card>
                    <template #title>
                        {{ step === 1 ? 'Deine E-Mail Adresse' : 'Filter auswählen' }}
                    </template>
                    <template #subtitle>
                        {{
                            step === 1 ? 'Du bekommst per E-Mail die verfügbaren Zeitslots zugeschickt. Daher wird deine E-Mail Adresse benötigt.' : 'Wähle deine bevorzugten Filter aus. Du wirst benachrichtigt, sobald ein passender Termin für diese Filter verfügbar ist.'
                        }}
                    </template>
                    <template #content>
                        <div v-if="step === 1">
                            <InputText class="w-full" v-model="form.email" type="email" placeholder="E-Mail Adresse"/>
                        </div>
                        <div v-else-if="step === 2">

                            <div class="grid grid-cols-3 gap-6">
<!--                                <div class="col-span-1">-->
<!--                                    &lt;!&ndash;                                  <Calendar v-model="form.filters.date" placeholder="Bevorzugte Tage" class="w-full" date-format="dd.mm.yy" selectionMode="multiple" :manualInput="false" />&ndash;&gt;-->

<!--                                </div>-->

                                <div v-for="filter in availableFilters" class="col-span-1">
                                    <template v-if="filter.input_type === 'select'">
                                        <Dropdown v-if="!filter.allow_multiple_values"
                                                  v-model="form.filters[filter.name]" :options="filter.options"
                                                  option-label="title"
                                                  option-value="value" :placeholder="filter.title"
                                                  class="w-full md:w-14rem"/>

                                        <MultiSelect v-else v-model="form.filters[filter.name]" :options="filter.options"
                                                     optionLabel="title"
                                                     option-value="value"
                                                     :placeholder="filter.title"
                                                     :maxSelectedLabels="8" class="w-full md:w-14rem"/>
                                    </template>

                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t">
                                <Checkbox v-model="acceptPrivacy" inputId="accept-privacy" binary />
                                <label for="accept-privacy" class="ml-1">
                                    Indem Sie diesen Suchauftrag erstellen, willigen Sie ein, dass Ihre E-Mail-Adresse gespeichert wird, um Sie zu benachrichtigen, sobald ein passender Termin verfügbar ist. <Link href="/unsubscribe" class="text-blue-500 hover:underline">Sie können diese Zustimmung jederzeit widerrufen.</Link>
                                </label>
                            </div>

                        </div>

                    </template>

                    <template #footer>
                        <div class="flex justify-end">
                            <Button type="submit" :disabled="step === 1 && !form.email || step === 2 && !acceptPrivacy" :label="step === 1 ? 'Weiter' : 'Suchauftrag erstellen'"/>
                        </div>
                    </template>
                </Card>
            </form>
        </div>
    </div>

</template>
