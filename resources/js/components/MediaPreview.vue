<template>
    <div :class="{modal: true, 'is-active': shown}">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div id="media-preview" class="box" style="border-radius: 0;">
                <h2 class="title">Media Preview <small class="is-size-5 has-text-grey">{{ label }}</small></h2>
                <img v-show="shown && jenis == 'gambar'" :src="url">
                <audio v-show="shown && jenis == 'audio'" ref="my_audio" controls>
                    <source :src="url" type="audio/mp3">
                    Your browser does not support the <code>audio</code> element.
                </audio>
                <video v-show="shown && jenis == 'video'" ref="my_video" controls>
                    <source :src="url" type="video/mp4">
                    Your browser does not support the <code>video</code> element.
                </video>
            </div>
        </div>
        <button @click="closePreview" class="modal-close is-large"></button>
    </div>
</template>
<style scoped>
    #media-preview audio, #media-preview video {
        width: 100%;
    }
</style>
<script>
    import bus from '../bus'
    export default {
        data () {
            return {
                shown: false,
                url: '',
                jenis: '',
                label: ''
            }
        },
        mounted() {
            bus.$on('open-preview', (data) => {
                console.log(data.filename)
                this.url = window.location.origin + '/storage/media/' + data.filename
                this.jenis = data.jenis
                this.label = data.label

                if (data.jenis == 'audio') {
                    this.$refs.my_audio.load()
                }

                if (data.jenis == 'video') {
                    this.$refs.my_video.load()
                }

                this.shown = true
            })
        },
        methods: {
            closePreview() {
                if (this.jenis == 'audio') {
                    this.$refs.my_audio.pause()
                }

                if (this.jenis == 'video') {
                    this.$refs.my_video.pause()
                }

                this.shown = false
            }
        }
    }
</script>