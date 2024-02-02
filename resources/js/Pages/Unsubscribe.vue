<script setup lang="ts">
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import {useForm} from "@inertiajs/inertia-vue3";
import {useToastWatcher} from "../Uses/UseToastWatcher";
import Message from "primevue/message";

const props = defineProps<{
    email: string,
}>()

const form = useForm({
    email: ''
});

function submit() {

    form.get('/unsubscribe', {
        replace: true,
        preserveState: true
    });

}


useToastWatcher();
</script>

<template>
    <div class="bg-gray-200 h-screen flex items-center">
        <div class="max-w-2xl mx-auto">
            <form @submit.prevent="submit">
                <Card>
                    <template #title>
                        Benachrichtigungen abbestellen
                    </template>
                    <template #subtitle>
                        Tragen Sie Ihre E-Mail-Adresse ein, um keine weiteren Benachrichtigungen zu erhalten.
                    </template>
                    <template v-if="!email" #content>
                        <label for="email">Bitte geben Sie Ihre E-Mail-Adresse ein</label>
                        <InputText class="w-full mt-2" id="email" required v-model="form.email" type="email"
                                   placeholder="E-Mail Adresse"/>
                    </template>
                    <template v-else #content>
                        <Message severity="success" :closable="false">Erfolgreich! Sie haben Ihre E-Mail Adresse abgemeldet.</Message>
                    </template>

                    <template v-if="!email" #footer>
                        <div class="flex justify-end">
                            <Button :disabled="!form.email" data-umami-event="Click unsubscribe" type="submit" label="Benachrichtigungen abbestellen"/>
                        </div>
                    </template>
                </Card>
            </form>
        </div>
    </div>

</template>
