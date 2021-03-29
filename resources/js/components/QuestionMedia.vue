<template>
    <div>
        <div class="tabs is-boxed">
            <ul>
                <li :class="{'is-active': isActive('gambar')}">
                    <a @click="setActiveTab('gambar')">
                        <span class="icon is-small"><i class="fas fa-image"></i></span>
                        <span>Gambar</span>
                    </a>
                </li>
                <li :class="{'is-active': isActive('audio')}">
                    <a @click="setActiveTab('audio')">
                        <span class="icon is-small"><i class="fas fa-music"></i></span>
                        <span>Audio</span>
                    </a>
                </li>
                <li :class="{'is-active': isActive('video')}">
                    <a @click="setActiveTab('video')">
                        <span class="icon is-small"><i class="fas fa-film"></i></span>
                        <span>Video</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="field is-grouped">
            <div class="control">
                <div :class="{file: true, 'is-danger': message, 'has-name': filename}">
                    <label class="file-label">
                        <input class="file-input" name="document" v-validate="getRules(active_tab)" type="file" @change="fileChanged">
                        <span class="file-cta">
                            <span class="file-icon">
                                <i class="fas fa-upload"></i>
                            </span>
                            <span class="file-label">
                                Choose a file…
                            </span>
                        </span>
                        <span v-show="filename" class="file-name">{{ filename }}</span>
                    </label>
                </div>

                <p v-show="message" class="help is-danger">{{ message }}</p>
            </div>
            <div v-show="getMedia('label')" class="control">
                <a @click="openPreview" class="button">
                    <span class="icon">
                        <i class="fas fa-external-link-alt"></i>
                    </span>
                    <span>Preview Media: {{ getMedia('label') }}</span>
                </a>

                <p class="help is-info">Click above button to preview media.</p>
            </div>
        </div>

        
    </div>
</template>

<style scoped>
    .thumb {
        margin-top: 15px;
        /*background: pink;*/
    }
</style>

<script>
    import bus from '../bus'
    export default {
        props: ['field', 'ujian', 'soal'],
        data () {
            return {
                active_tab: 'gambar',
                document: {},
                media: {},
                message: '',
                filename: ''
                // shown: true
            }
        },
        watch: {
            active_tab(newVal, oldVal) {
                this.message = ''
                this.filename = ''
            }
        },
        mounted() {
            this.media = window.documents
        },

        methods: {
            setActiveTab(tab) {
                this.active_tab = tab
            },

            isActive(tab) {
                return this.active_tab == tab
            },

            getMedia(prop) {
                let category = this.active_tab

                if (typeof(this.media[this.field]) !== 'undefined') {
                    if (this.media[this.field] !== null) {
                        if (this.media[this.field].hasOwnProperty(category)) {
                            return this.media[this.field][category][prop]
                        }
                        return false
                    }
                    return false
                }

                return false
            },

            openPreview() {
                let media = {
                    path: this.getMedia('path'),
                    filename: this.getMedia('filename'),
                    jenis: this.active_tab,
                }

                bus.$emit('open-preview', media)
                // console.log(media)
            },

            fileChanged(e) {
                let vm = this
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        console.log('ayo proses upload...')
                        let data = new FormData()
                        data.append('ujian', vm.ujian)
                        data.append('soal', vm.soal)
                        data.append('jenis', vm.active_tab)
                        data.append('lahan', vm.field)
                        data.append('dokumen', e.target.files[0])

                        // axios.post('/guru/unggah-media', data)
                        axios.post('/teacher/unggah-media', data)
                            .then((res) => {
                                // console.log(res.data)
                                vm.media = res.data
                                vm.message = ''
                                vm.filename = ''
                            })
                            .catch(err => {
                                // console.log(err.response.data)
                                // this.message = err.response.data.errors.dokumen[0]
                                vm.message = err.response.data.message

                                console.log(err.response.data)

                                // if (err.response.data.errors.dokumen.length) {
                                //     vm.message = 'Dokumen yang diunggah tidak valid'
                                // }
                            })

                        this.filename = e.target.files[0].name
                    } else {
                        this.message = this.$validator.errors.items[0].msg
                    }
                })
            },

            getRules(category) {
                let rules = 'required|size:4096|'
                if (category == 'gambar') rules += 'ext:jpg,png'
                if (category == 'audio') rules += 'ext:mp3,m4a'
                if (category == 'video') rules += 'ext:mp4'
                return rules
            },
        }
    }
</script>
