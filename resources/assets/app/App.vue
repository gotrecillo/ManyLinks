<template>
    <v-app>
        <tool-bar></tool-bar>
    </v-app>
</template>


<script>
    import ToolBar from './components/layout/toolbar.vue'

    export default {
        components: {
            ToolBar
        },
        beforeMount () {
            console.log('mount')
        },
    }
</script>

<style>
</style>