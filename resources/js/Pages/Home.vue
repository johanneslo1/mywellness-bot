<template>
    <div class="bg-gray-200 h-screen flex items-center">
        <div class="max-w-4xl mx-auto">

            <h1 class="text-6xl font-semibold">
                <a href="https://mywellness.de/" target="_blank">MyWellness</a> Bot</h1>

            <p class="mt-3">Erhalte Benachrichtigungen, wenn andere Gäste ihre Buchung stornieren und für dich damit ein
                Zeitslot frei wird.</p>


            <div class="mt-12 rounded bg-gray-50 px-4 py-6">
                <h3 class="text-xl font-medium">Suchst du vergeblich nach einem verfügbaren Termin, weil alles vollständig ausgebucht ist?</h3>
            <p class="mt-3">Keine Sorge mehr! Manchmal müssen Gäste ihre Termine kurzfristig absagen, wodurch oft schnell ein Platz frei wird. Mit dem Bot wirst du sofort informiert und kannst den Termin direkt buchen.</p>

            <Link href="/start" class="flex justify-center mt-6">
                <Button  class="w-1/2 justify-center" label="Jetzt loslegen!" />
            </Link>
            </div>

        </div>
    </div>
</template>


<style>
html, body {
    font-family: 'Open Sans', sans-serif;
}
</style>

<script setup lang="ts">
import {Link} from "@inertiajs/inertia-vue3";
import Button from "primevue/button";
</script>
