// resources/js/components/Widget.vue

<template>
    <!-- Your widget HTML here -->
    <div>
        <p>Widget Content</p>
        <div class="card-widgets">
            <a href="javascript: void(0);" @click="refreshWidget"><i class="mdi mdi-refresh"></i></a>
            <!-- Other widget controls -->
        </div>
        <!-- Your table and data display -->
        <!-- ... -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            services: [], // Initialize the services data property
            // Add any other data properties you need for your widget
        };
    },
    methods: {
        // Add any other methods you need for your widget

        refreshWidget() {
            // Perform an AJAX request to fetch the latest data from the server
            axios.get('/api/get-latest-services')
                .then(response => {
                    // Update the services data property with the latest data
                    this.services = response.data;
                })
                .catch(error => {
                    console.error('Error fetching latest services:', error);
                });
        },
        
        playSound() {
            // Create an audio element
            const audio = new Audio("{{ asset('uploads/sound_premium.mp3') }}"); // Replace with the path to your sound file
            // Play the audio
            audio.play();
        }
    },
    mounted() {
        // Fetch initial data when the component is mounted  
        this.refreshWidget();
        
        const self = this;

        window.Echo.channel('play-sound-and-refresh-widget')
            .listen('PlaySoundAndRefreshWidget', (event) => {
                // Execute the JavaScript logic to play the sound
                self.playSound();

                // Execute the JavaScript logic to refresh the widget
                self.refreshWidget();
            });
    }
}
</script>
