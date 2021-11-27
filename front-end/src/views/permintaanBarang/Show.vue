<template>
  <CRow>
    <CCol col="12" lg="12">
      <CCard no-header>
        <CCardBody>
          <h3>Permintaan Barang id:  {{ $route.params.id }}</h3>

          <CInput label="NIK" type="text" horizontal placeholder="NIK" name="nik" v-model="permintaanBarang.nik" readonly/>
          <CInput label="Name" type="text" horizontal placeholder="Name" name="name" v-model="permintaanBarang.name" readonly/>
          <CInput label="Departement" type="text" horizontal placeholder="Departement" name="departemen" v-model="permintaanBarang.departemen" readonly/>
          <CInput label="Tanggal Permintaan" type="text" horizontal placeholder="Tanggal Permintaan" name="tanggal_permintaan" v-model="permintaanBarang.tanggal_permintaan" readonly/>

          <table class="table">
          <thead>
            <tr>
              <td>Barang</td>
              <td>Lokasi</td>
              <td>Tersedia</td>
              <td>Kuantiti</td>
              <td>Satuan</td>
              <td>Keterangan</td>
            </tr>
          </thead>
          <tbody v-for="(detailBarang, index) in permintaanBarang.detailBarangs">
            <tr>
              <td>{{ detailBarang.label }}</td>
              <td>{{ detailBarang.lokasi }}</td>
              <td>{{ detailBarang.tersedia }}</td>
              <td>{{ detailBarang.kuantiti }}</td>
              <td>{{ detailBarang.satuan }}</td>
              <td>{{ detailBarang.keterangan }}</td>
            </tr>
          </tbody>
          </table>

          <CButton color="primary" @click="goBack">Back</CButton>
        </CCardBody>
      </CCard>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'
export default {
  name: 'User',
  props: {
    caption: {
      type: String,
      default: 'User id'
    },
  },
  data: () => {
    return {
      permintaanBarang: [],
    }
  },
  methods: {
    goBack() {
      this.$router.go(-1)
      // this.$router.replace({path: '/users'})
    }
  },
  mounted: function(){
    let self = this;
    axios.get(  this.$apiAdress + '/api/permintaanBarang/' + self.$route.params.id + '?token=' + localStorage.getItem("api_token"))
    .then(function (response) {
      self.permintaanBarang = response.data;
    }).catch(function (error) {
      console.log(error);
      self.$router.push({ path: '/login' });
    });
  }
}


</script>
