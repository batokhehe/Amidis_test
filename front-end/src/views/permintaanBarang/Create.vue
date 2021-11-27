<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard no-header>
        <CCardBody>
          <h4>
            Create Permintaan Barang
          </h4>
          <CAlert
            :show.sync="dismissCountDown"
            color="primary"
            fade >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>
          <CSelect
            label="NIK" 
            horizontal
            name="nik"
            @change="onChange($event)"
            :value.sync="permintaanBarang.nik"
            :plain="true"
            :options="users">
          </CSelect>
          <CInput label="Nama" type="text" horizontal placeholder="Nama" name="nama" v-model="nama" readonly/>

          <CInput label="Departemen" type="text" horizontal placeholder="Departemen" name="departemen" v-model="departemen" readonly/>

          <CInput label="Tanggal Permintaan" type="date" horizontal name="tanggal_permintaan" v-model="permintaanBarang.tanggal_permintaan" />

          <CRow>
            <CCol col="12" lg="12">
              <CButton class="float-right" color="success" @click="addDetail()">Tambah</CButton>
            </CCol>
          </CRow>

          <table>
          <thead>
            <tr>
              <td>Barang</td>
              <td>Lokasi</td>
              <td>Tersedia</td>
              <td>Kuantiti</td>
              <td>Satuan</td>
              <td>Keterangan</td>
              <td>Status</td>
            </tr>
          </thead>
          <tbody v-for="(detailBarang, index) in permintaanBarang.detailBarangs">
            <tr>
              <td><CSelect
                @change="onChangeBarang($event, index)"
                :value.sync="detailBarang.kode"
                :plain="true"
                :options="barangs">
              </CSelect></td>
              <td><CInput type="text" v-model="detailBarang.lokasi" readonly/></td>
              <td><CInput type="text" v-model="detailBarang.tersedia" readonly/></td>
              <td><CInput type="text" v-model="detailBarang.kuantiti" @input="validateQty(index)"/></td>
              <td><CInput type="text" v-model="detailBarang.satuan" readonly/></td>
              <td><CInput type="text" v-model="detailBarang.keterangan" /></td>
              <td>
                <span v-if="detailBarang.status === 'valid'" class="badge badge-success">Terpenuhi</span>
                <span v-else class="badge badge-danger">Tidak Terpenuhi</span>
                <button @click="deleteDetail(index)">x</button>
              </td>
            </tr>
          </tbody>
          </table>

          <CButton color="info" @click="goBack">Back</CButton>
          <CButton class="float-right" color="primary" @click="store()">Create</CButton>

        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'
export default {
  name: 'CreatePermintaanBarang',
  data: () => {
    return {
        permintaanBarang: {
            nik: '',
            tanggal_permintaan: '',
            detailBarangs: [{ kode:'', lokasi:'', tersedia:'', kuantiti:'0', satuan:'', keterangan:'', status:'' }] 
        },
        nama: '',
        departemen: '',
        users: [],
        barangs: [],
        message: '',
        dismissSecs: 7,
        dismissCountDown: 0,
        showDismissibleAlert: false,
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    },
    onChange(event) {
        let self = this;
        console.log(event.target.value);
        axios.get(  this.$apiAdress + '/api/users/' + event.target.value + '?token=' + localStorage.getItem("api_token"))
        .then(function (response) {
            self.nama = response.data.name;
            self.departemen = response.data.departemen;
        }).catch(function (error) {
            console.log(error);
            self.$router.push({ path: 'login' });
        });
    },

    onChangeBarang(event, index) {
        let self = this;
        console.log(event.target.value);
        axios.get(  this.$apiAdress + '/api/barang/' + event.target.value + '?token=' + localStorage.getItem("api_token"))
        .then(function (response) {
            var newVal = Object.assign({}, self.permintaanBarang.detailBarangs[index], {kode:event.target.value, lokasi: response.data.lokasi, tersedia:response.data.qty, kuantiti:'0', satuan:response.data.uom, keterangan:'', status:''})
            self.$set(self.permintaanBarang.detailBarangs, index, newVal)
        }).catch(function (error) {
            console.log(error);
            self.$router.push({ path: 'login' });
        });
    },

    validateQty(index) {
        let self = this;
        let barang = self.permintaanBarang.detailBarangs[index];
        if (barang.kuantiti!= '' && barang.tersedia > barang.kuantiti){
            var newVal = Object.assign({}, self.permintaanBarang.detailBarangs[index], {kode:barang.kode, lokasi: barang.lokasi, tersedia:barang.tersedia,   kuantiti:barang.kuantiti, satuan:barang.satuan, keterangan:barang.keterangan, status:'valid'})
            self.$set(self.permintaanBarang.detailBarangs, index, newVal)
        } else {
            var newVal = Object.assign({}, self.permintaanBarang.detailBarangs[index], {kode:barang.kode, lokasi: barang.lokasi, tersedia:barang.tersedia,   kuantiti:barang.kuantiti, satuan:barang.satuan, keterangan:barang.keterangan, status:''})
            self.$set(self.permintaanBarang.detailBarangs, index, newVal)
        }
    },

    store() {
        let self = this;
        axios.post(  this.$apiAdress + '/api/permintaanBarang?token=' + localStorage.getItem("api_token"),
          self.permintaanBarang
        )
        .then(function (response) {
            self.permintaanBarang = {
                nik: '',
                tanggal_permintaan:'',
                detailBarangs: [{ kode:'', lokasi:'', tersedia:'', kuantiti:'0', satuan:'', keterangan:'', status:'' }]
             }
            self.message = 'Successfully created Permintaan Barang.';
            self.showAlert();
        }).catch(function (error) {
            if(error.response.data.message == 'The given data was invalid.'){
              self.message = '';
              for (let key in error.response.data.errors) {
                if (error.response.data.errors.hasOwnProperty(key)) {
                  self.message += error.response.data.errors[key][0] + '  ';
                }
              }
              self.showAlert();
            }else{
              console.log(error);
              self.$router.push({ path: 'login' }); 
            }
        });
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    addDetail: function () {
      this.permintaanBarang.detailBarangs.push({kode:'', lokasi:'', tersedia:'', kuantiti:'0', satuan:'', keterangan:'', status:'' });
    },
    deleteDetail: function (index) {
      console.log(index);
      this.permintaanBarang.detailBarangs.splice(index, 1);
    }
  },
  mounted: function(){
    let self = this;
    axios.get(  this.$apiAdress + '/api/users?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        self.users = response.data.users;
    }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: 'login' });
    });

    axios.get(  this.$apiAdress + '/api/barang?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
        self.barangs = response.data;
    }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: 'login' });
    });
  }
}

</script>
