<template>
  <CRow>
    <CCol col="12" xl="12">
      <transition name="slide">
      <CCard>
        <CCardHeader>
            Permintaan Barang
        </CCardHeader>
        <CCardBody>
        <CButton class="float-right" color="primary" @click="create()">Create New</CButton>
          <CAlert
            :show.sync="dismissCountDown"
            color="primary"
            fade
          >
            ({{dismissCountDown}}) {{ message }}
          </CAlert>
          <CDataTable
            hover
            striped
            :items="items"
            :fields="fields"
            :items-per-page="10"
            pagination
          >
          <template #nik="{item}">
            <td>
              <strong>{{item.nik}}</strong>
            </td>
          </template>
          <template #name="{item}">
            <td>
              <strong>{{item.name}}</strong>
            </td>
          </template>
          <template #departemen="{item}">
            <td>
              <strong>{{item.departemen}}</strong>
            </td>
          </template>
          <template #tanggal_permintaan="{item}">
            <td>
              <strong>{{item.tanggal_permintaan}}</strong>
            </td>
          </template>
          <template #show="{item}">
            <td>
              <CButton color="primary" @click="show( item.id )">Show</CButton>
            </td>
          </template>
          <template #edit="{item}">
            <td>
              <CButton color="primary" @click="edit( item.id )">Edit</CButton>
            </td>
          </template>
          <template #delete="{item}">
            <td>
              <CButton v-if="you!=item.id" color="danger" @click="delete( item.id )">Delete</CButton>
            </td>
          </template>
        </CDataTable>
        </CCardBody>
      </CCard>
      </transition>
    </CCol>
  </CRow>
</template>

<script>
import axios from 'axios'

export default {
  name: 'permintaanBarang',
  data: () => {
    return {
      items: [],
      fields: ['nik', 'name', 'departemen', 'tanggal_permintaan', 'show', 'edit', 'delete'],
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      you: null,
      message: '',
      showMessage: false,
      dismissSecs: 7,
      dismissCountDown: 0,
      showDismissibleAlert: false
    }
  },
  paginationProps: {
    align: 'center',
    doubleArrows: false,
    previousButtonHtml: 'prev',
    nextButtonHtml: 'next'
  },
  methods: {
    getBadge (status) {
      return status === 'Active' ? 'success'
        : status === 'Inactive' ? 'secondary'
          : status === 'Pending' ? 'warning'
            : status === 'Banned' ? 'danger' : 'primary'
    },
    dataLink (id) {
      return `permintaanBarang/${id.toString()}`
    },
    editLink (id) {
      return `permintaanBarang/${id.toString()}/edit`
    },
    show ( id ) {
      const dataLink = this.dataLink( id );
      this.$router.push({path: dataLink});
    },
    edit ( id ) {
      const editLink = this.editLink( id );
      this.$router.push({path: editLink});
    },
    delete ( id ) {
      let self = this;
      let dataId = id;
      axios.post(  this.$apiAdress + '/api/permintaanBarang/' + id + '?token=' + localStorage.getItem("api_token"), {
        _method: 'DELETE'
      })
      .then(function (response) {
          self.message = 'Successfully deleted Permintaan Barang.';
          self.showAlert();
          self.getData();
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    },
    create () {
      this.$router.push({path: 'permintaanBarang/create'});
    },
    countDownChanged (dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert () {
      this.dismissCountDown = this.dismissSecs
    },
    getData (){
      let self = this;
      axios.get(  this.$apiAdress + '/api/permintaanBarang?token=' + localStorage.getItem("api_token"))
      .then(function (response) {
        self.items = response.data;
      }).catch(function (error) {
        console.log(error);
        self.$router.push({ path: '/login' });
      });
    }
  },
  mounted: function(){
    this.getData();
  }
}
</script>
