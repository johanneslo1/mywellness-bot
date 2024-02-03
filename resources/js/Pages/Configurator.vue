<script setup lang="ts">
import {onMounted, ref} from "vue";
import Steps from 'primevue/steps';
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import {useForm} from "@inertiajs/inertia-vue3";
import Dropdown from "primevue/dropdown";
import MultiSelect from "primevue/multiselect";
import Message from "primevue/message";
import {Link} from "@inertiajs/inertia-vue3";
import {useToastWatcher} from "../Uses/UseToastWatcher";
import Checkbox from 'primevue/checkbox';
import {Inertia} from "@inertiajs/inertia";
import VueHcaptcha from '@hcaptcha/vue3-hcaptcha';


const props = withDefaults(defineProps<{
    availableFilters: [],
    step: number,
    hCaptchaSiteKey: string
}>(), {step: 1});


const form = useForm({
    filters: {
        ...props.availableFilters.reduce((acc, filter) => {
            acc[filter.name] = null;
            return acc;
        }, {}),
        date: null
    },
    email: '',
    prefered_weekdays: [],
    h_captcha_response: '',

});

onMounted(() => {
    if (props.step === 2 && !form.email) {
        Inertia.get('/start')
    }
})


const hCaptcha = ref()

function executeCaptcha() {
    hCaptcha.value?.execute();
}

function submit(skipCaptcha: boolean = false, hCatpchaToken: string = '') {

    if (props.step === 2 && !skipCaptcha) {
        executeCaptcha();
        return;
    }

    if (hCatpchaToken !== '')
        form.h_captcha_response = hCatpchaToken;

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
            <form id="ConfiguratorForm" @submit.prevent="submit(false)">
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

                        <Message v-if="$page.props.errors" severity="error">

                            <ul class="list-disc pl-8">
                                <li v-for="field in $page.props.errors">
                                    {{ field[0] }}
                                </li>
                            </ul>
                        </Message>

                        <div v-if="step === 1">
                            <InputText data-umami-event="Enter email" class="w-full" v-model="form.email" type="email"
                                       placeholder="E-Mail Adresse"/>
                        </div>
                        <div v-else-if="step === 2">

                            <div class="grid grid-cols-3 gap-6">
                                <!--                                <div class="col-span-1">-->
                                <!--                                    &lt;!&ndash;                                  <Calendar v-model="form.filters.date" placeholder="Bevorzugte Tage" class="w-full" date-format="dd.mm.yy" selectionMode="multiple" :manualInput="false" />&ndash;&gt;-->

                                <!--                                </div>-->

                                <div v-for="filter in availableFilters" class="col-span-1">
                                    <label :for="filter.name">{{ filter.title }}</label>
                                    <template v-if="filter.input_type === 'select'">
                                        <Dropdown v-if="!filter.allow_multiple_values"
                                                  v-model="form.filters[filter.name]" :options="filter.options"
                                                  option-label="title"
                                                  data-umami-event="Select filter"
                                                  :data-umami-event-filter-type="filter.name"
                                                  option-value="value" :placeholder="filter.title"
                                                  class="w-full md:w-14rem mt-1"/>

                                        <MultiSelect v-else v-model="form.filters[filter.name]"
                                                     :options="filter.options"
                                                     optionLabel="title"
                                                     option-value="value"
                                                     :placeholder="filter.title"
                                                     data-umami-event="Select filter"
                                                     :data-umami-event-filter-type="filter.name"
                                                     :maxSelectedLabels="3" class="w-full md:w-14rem mt-1"/>
                                    </template>

                                </div>

                                <div class="col-span-1">
                                    <label for="prefered_weekdays">Bevorzugte Wochentage</label>
                                    <MultiSelect v-model="form.prefered_weekdays"
                                                 :options="[{'title': 'Montag', 'value': 1}, {'title': 'Dienstag', 'value': 2}, {'title': 'Mittwoch', 'value': 3}, {'title': 'Donnerstag', 'value': 4}, {'title': 'Freitag', 'value': 5}, {'title': 'Samstag', 'value': 6}, {'title': 'Sonntag', 'value': 7}]"
                                                 placeholder="Bevorzugte Wochentage"
                                                 optionLabel="title"
                                                 required
                                                 option-value="value"
                                                 id="prefered_weekdays"
                                                 class="w-full md:w-14rem mt-1"/>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t">
                                <Checkbox data-umami-event="Accept privacy checkbox" v-model="acceptPrivacy"
                                          inputId="accept-privacy" binary/>
                                <label data-umami-event="Accept privacy checkbox by label" for="accept-privacy"
                                       class="ml-1">
                                    Indem Sie diesen Suchauftrag erstellen, willigen Sie ein, dass Ihre E-Mail-Adresse
                                    gespeichert wird, um Sie zu benachrichtigen, sobald ein passender Termin verfügbar
                                    ist.
                                    <Link href="/unsubscribe" class="text-blue-500 hover:underline">Sie können diese
                                        Zustimmung jederzeit widerrufen.
                                    </Link>
                                </label>
                            </div>

                            <vue-hcaptcha ref="hCaptcha" @verify="submit(true, $event)" size="invisible"
                                          :sitekey="hCaptchaSiteKey"></vue-hcaptcha>


                        </div>

                    </template>

                    <template #footer>
                        <div class="flex justify-end">
                            <Button v-if="step === 1" type="submit"
                                    :disabled="!form.email" label="Weiter"/>
                            <Button v-else type="submit" :disabled="!acceptPrivacy" label="Suchauftrag erstellen"/>
                        </div>
                    </template>
                </Card>
            </form>
        </div>
    </div>

</template>
