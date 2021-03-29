<template>
    <div>
        <div style="position: relative;">
            <div v-show="showNomerSoal" class="numbers-box">
                <ul class="nomor_soal">
                    <li @click="jumpTo(n)" v-for="n in jumlah_soals">
                        <div v-if="getJenisSoal(n) == 'pg'" class="bulat">{{ getJawaban(n) }}</div>
                        <div v-if="getJenisSoal(n) == 'es'" class="bulat">es</div>
                        <div :class="{kotak:true, terjawab:cekTerjawabYakin(n), ragu:cekTerjawabRagu(n),terpilih:(seq == n)}">{{ n }}</div>
                    </li>
                </ul>
                <div style="clear: both;"></div>
            </div>
        </div>
        <div>
            <div class="level">
                <div class="level-left">
                    <div class="tags has-addons">
                        <span class="tag is-medium is-dark">Nomor Soal</span>
                        <span class="tag is-medium is-info">{{ seq }}</span>
                    </div>
                </div>
                <div class="level-item">
                    <span class="is-size-3">{{ sisa_waktu | timer }}</span>
                </div>
                <div class="level-right">
                    <button @click="showNomerSoal = !showNomerSoal" :class="{button:true, 'is-primary':!showNomerSoal, 'is-danger':showNomerSoal}">
                        <span class="icon">
                            <i class="fas fa-list-ol"></i>
                        </span>
                        <span>{{ showNomerSoal ? 'Sembunyikan Nomor Soal' : 'Tampilkan Nomor Soal' }}</span>
                    </button>
                </div>
            </div>
            <div class="box">
                <span v-if="soal.terkait_soal_sebelumnya" class="tag is-info">Soal ini berkaitan dengan soal sebelumnya</span>
                <div class="konten-soal" v-html="soal.konten"></div>
                <img v-if="getImgUrl('pertanyaan')" @click="tampilkan_preview_gambar = true" :src="getImgUrl('pertanyaan')" width="300">

                <p v-if="getImgUrl('pertanyaan')"><strong>Note: </strong> klik pada gambar untuk memperbesar.</p>
            </div>

            <div v-if="soal.jenis == 'es'">
                <div class="field">
                    <div class="control">
                        <textarea v-model="es" cols="30" rows="4" class="textarea" placeholder="Tulis jawaban kamu di sini!"></textarea>
                    </div>
                </div>
                <div class="level">
                    <div class="level-left">
                        <span v-if="!getJawaban(seq)" class="has-text-danger"><strong>Status:</strong> Jawaban essay belum tersimpan!</span>
                        <span v-if="getJawaban(seq)" class="has-text-success"><strong>Status:</strong> Jawaban essay sudah tersimpan!</span>
                    </div>
                    <div class="level-right">
                        <button :disabled="es == null || es.trim().length == 0" @click="setJawaban(es)" class="button is-outlined is-primary">Simpan Jawaban Essay</button>
                    </div>
                </div>
            </div>

            <article v-if="soal.jenis == 'pg'" v-for="(v,k) in soal.opsi" class="media">
                <figure class="media-left">
                   <button @click="setJawaban(k)" :class="{circle:true, 'selected': k == jawaban_soal_ini}">{{ k }}</button>
                </figure>
                <div class="media-content">
                    <div class="content">
                        <div v-html="v"></div>
                        <div v-if="getImgUrl('opsi_' + k)">
                            <img :src="getImgUrl('opsi_' + k)" width="300">
                        </div>
                    </div>
                </div>
            </article>

            <!-- <div style="height: 30px;"></div> -->

            <div style="margin: 30px 0;" class="level">
                <div class="level-left">
                    <button @click="jumpTo(seq -1)" v-if="seq > 1" class="button is-primary">
                        Soal Sebelumnya
                    </button>
                </div>
                <div class="level-item">
                    <button @click="setJawabanRagu()" :class="{button:true, 'is-dark':!jawaban_soal_ini_ragu, 'is-danger':jawaban_soal_ini_ragu}">
                        <span class="icon">
                            <!-- <i :class="jawaban_soal_ini_ragu ? 'far fa-check-square' : 'far fa-square'"></i> -->
                            <i :class="{far:true, 'fa-check-square':jawaban_soal_ini_ragu, 'far fa-square':!jawaban_soal_ini_ragu}"></i>
                        </span>
                        <span>Ragu?</span>
                    </button>
                </div>
                <div class="level-right">
                    <button v-if="cekJawabanKomplit()" @click="tampilkan_konfirmasi = true" class="button is-danger is-medium is-uppercase">Selesai</button>

                    <button @click="jumpTo(seq +1)" v-if="seq < jumlah_soals" class="button is-primary">
                        Soal Berikutnya
                    </button>
                </div>
            </div>
        </div>

        <!-- preview gambar -->
        <div :class="{modal: true, 'is-active': tampilkan_preview_gambar}">
            <div class="modal-background"></div>
            <div class="modal-content">
                <!-- Any other Bulma elements you want -->
                <p class="image">
                    <img :src="getImgUrl('pertanyaan')">
                </p>
            </div>
            <button @click="tampilkan_preview_gambar = false" class="modal-close is-large" aria-label="close"></button>
        </div>

        <!-- modal -->
        <div :class="{modal: true, 'is-active': tampilkan_konfirmasi}">
            <div class="modal-background"></div>
            <div class="modal-content">
                <!-- Any other Bulma elements you want -->
                <div class="box">
                    <div class="content has-text-centered">
                        <h1>Akhiri Ujian Sekarang?</h1>

                        <p style="padding: 10px 0;">
                            <span class="icon is-large">
                                <i class="fas fa-info-circle fa-5x"></i>
                            </span>
                        </p>

                        <p>Klik checkbox pada pernyataan di bawah, kemudian tekan tombol berwarna merah.</p>

                        <p>
                            <span @click="saya_yakin = !saya_yakin" class="icon is-medium">
                                <i :class="{far:true, 'fa-lg':true, 'fa-check-square': saya_yakin, 'fa-square':!saya_yakin }"></i>
                            </span>
                            <span>Saya yakin dengan jawaban saya.</span>
                        </p>

                        <p>
                            <button :disabled="!saya_yakin" @click="kunciJawaban()" class="button is-medium is-danger is-uppercase">Akhiri Ujian</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .numbers-box {
        position: absolute;
        width: 450px;
        right: 0;
        top: 50px;
        border: solid 1px #ccc;
        background: #999;
        z-index: 1;
        height: 500px;
        overflow: scroll;
    }
    ul.nomor_soal {
        margin: 0;
        padding: 0;
    }

    ul.nomor_soal li {
        list-style: none;
        width: 70px;
        height: 70px;
        float: left;
        position: relative;
        margin: 10px 0 0 10px;
        cursor: pointer;
    }

    ul.nomor_soal li .bulat {
        position: absolute;
        right: 0;
        top: 0;
        width: 30px;
        height: 30px;
        border: solid 2px #000;
        border-radius: 50%;
        background: #fff;
        text-transform: uppercase;
        text-align: center;
    }    

    ul.nomor_soal li .kotak {
        width: 45px;
        height: 45px;
        border: solid 1px #000;
        text-align: center;
        line-height: 3em;
        margin: 15px 10px 0;
        background: #fff;
    }

    ul.nomor_soal li .kotak.terjawab {
        background: hsl(171, 100%, 41%);
        color: #fff;
    }

    ul.nomor_soal li .kotak.ragu {
        background: hsl(48, 100%, 67%);
        color: hsl(348, 100%, 61%);
    }

    ul.nomor_soal li .kotak.terpilih {
        background: hsl(217, 71%, 53%);
        color: #fff;
    }

    .box p {
        margin-bottom: 15px;
    }

    button.circle {
        width: 45px;
        height: 45px;
        /*padding-top: 10px;*/
        font-size: 16px;
        /*font-weight: bold;*/
        border: solid 1px #000;
        border-radius: 50%;
        background: #777;
        color: #fff;
        text-transform: uppercase;
        text-align:center;
    }

    button.circle.selected {
        font-weight: bold;
        background: hsl(217, 71%, 53%);
    }
</style>

<script>
    // let socket_server_url = 'http://' + window.location.hostname + ':3000';
    let socket_server_url = 'https://timekeeper.myschool.web.id';
    // console.log(socket_server_url);
    let socket = io.connect(socket_server_url);
    export default {
        data() {
            return {
                seq: 1,
                soal: {
                    urut: 0,
                    konten: '',
                    opsi: {},
                    media: {},
                    jawaban: null,
                },
                soals: {},
                mapJawaban: {},
                showNomerSoal: false,
                jumlah_soals: 50,
                jawaban_soal_ini: null,
                jawaban_soal_ini_ragu: false,
                jawabans: {},
                ragus: [],
                sisa_waktu: 0,
                saya_yakin: false,
                tampilkan_konfirmasi: false,
                tampilkan_preview_gambar: false,
                submisi_id: null,
                es: null,
            };
        },

        created() {
            this.getData();

            setInterval(() => {
                this.sisa_waktu -=1;
            }, 1000);
        },

        watch: {
            soal: _.debounce(function(e) {
                this.$nextTick(function() {
                    MathJax.Hub.Queue(["Typeset",MathJax.Hub,"konten-soal"])
                })
            }, 300),
            sisa_waktu: function(val) {
                if (val == 0) {
                    console.log('auto submit');
                    this.kunciJawaban();
                }
            },
        },

        methods: {
            getData() {
                let url = window.location.href;
                axios.get(url)
                    .then((res) => {
                        this.submisi_id = res.data.submisi.id;
                        this.soals = res.data.soal;
                        this.jumlah_soals = _.size(res.data.soal);
                        this.soal = res.data.soal[1];
                        this.jawabans = res.data.submisi.jawaban;
                        this.ragus = res.data.submisi.nomor_urut_jawaban_ragu;
                        this.jawaban_soal_ini = _.get(res.data.submisi.jawaban, 1, null);
                        this.es = _.get(res.data.submisi.jawaban, 1, null);

                        this.jawaban_soal_ini_ragu = _.includes(this.ragus, 1);

                        // sisa waktu
                        this.sisa_waktu = parseInt(res.data.sisa_waktu);
                        console.log(this.sisa_waktu);

                        // broadcast socket event
                        let user = {
                            id: res.data.user.id,
                            cbt_id: res.data.cbt.id,
                            name: res.data.user.name,
                            start_at: res.data.submisi.start_at,
                        }
                        socket.emit('start_exam', user);
                    })
                    .catch((err) => {
                        console.log(err)
                    })
            },

            jumpTo(n) {
                this.seq = n;
                this.soal = this.soals[n];
                this.jawaban_soal_ini = _.get(this.jawabans, n, null);
                this.es = _.get(this.jawabans, n, null);
                this.jawaban_soal_ini_ragu = _.includes(this.ragus, n);
                this.showNomerSoal = false;
            },

            setJawaban(pilihan) {
                this.jawaban_soal_ini = pilihan;

                let url = window.location.href;
                axios.post(url, {nomor_soal: this.seq, jawaban: pilihan})
                    .then((res) => {
                        this.jawabans = res.data.jawaban;
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            },

            getJawaban(nomor_soal) {
                return _.get(this.jawabans, nomor_soal, null);
            },

            getJenisSoal(nomor_soal) {
                return _.get(this.soals, nomor_soal + '.jenis', null);
                // return this.soals[nomor_soal]['jenis'];
            },

            cekTerjawabYakin(nomor_soal) {
                let nums_dijawab = Object.keys(this.jawabans).map(i => parseInt(i));
                let yakin = _.difference(nums_dijawab, this.ragus);
                return yakin.indexOf(nomor_soal) > -1;
            },

            cekTerjawabRagu(nomor_soal) {
                return this.ragus.indexOf(nomor_soal) > -1;
            },

            cekJawabanKomplit() {
                return this.seq == this.jumlah_soals && _.size(this.jawabans) == this.jumlah_soals && _.size(this.ragus) == 0;
            },

            kunciJawaban() {
                this.tampilkan_konfirmasi = false;
                let url = '/student/cbt-finish';
                let data = {
                    submisi_id: this.submisi_id
                }
                axios.post(url, data)
                    .then((res) => {
                        console.log(res.data);
                        window.location = '/student/cbt';
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            },

            setJawabanRagu() {
                this.jawaban_soal_ini_ragu = !this.jawaban_soal_ini_ragu;

                let url = window.location.href;
                axios.put(url, {nomor_soal: this.seq, ragu: this.jawaban_soal_ini_ragu})
                    .then((res) => {
                        this.ragus = res.data.nomor_urut_jawaban_ragu;
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            },

            getImgUrl(category) {
                if (this.soal.media[category] == null) {
                    return false;
                }

                let url = window.location.origin + '/storage/media/' + this.soal.media[category]['gambar']['filename'];
                return url;
            },
        },

        filters: {
            timer(remain_time) {
                let sec_num = parseInt(remain_time, 10); // don't forget the second param
                let hours   = Math.floor(sec_num / 3600);
                let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
                let seconds = sec_num - (hours * 3600) - (minutes * 60);

                if (hours   < 10) {hours   = "0"+hours;}
                if (minutes < 10) {minutes = "0"+minutes;}
                if (seconds < 10) {seconds = "0"+seconds;}
                return hours+':'+minutes+':'+seconds;
            }
        }
    }
</script>