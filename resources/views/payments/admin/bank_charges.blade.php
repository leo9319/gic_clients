@extends('layouts.master')

@section('title', 'Bank Charges')

@section('content')

@section('header_scripts')

<style type="text/css">
   
   .modal-mask {
      position: fixed;
      z-index: 9998;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, .5);
      display: table;
      transition: opacity .3s ease;
   }

   .modal-wrapper {
      display: table-cell;
      vertical-align: middle;
   }

</style>

@stop

<div class="container-fluid" id="app">

   <div class="panel">

      <div class="panel-heading">
         <h3 class="panel-title">Bank Charges</h3>
      </div>

      <div class="panel-body">

         <table id="department-targets" class="table table-striped table-bordered">
            <thead>
               <tr>
                  <th>Bank Name</th>
                  <th>Charge</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="bank in banks">
                  <td v-text="bank.bank_name.toUpperCase().split('_').join(' ')"></td>
                  <td v-text="bank.bank_charge + ' %'"></td>
                  <td>
                     <button class="btn btn-info btn-sm btn-block" @click="onShowModal(bank)">Edit</button>
                  </td>
               </tr>
            </tbody>
            <thead>
               <tr>
                  <th>Bank Name</th>
                  <th>Charge</th>
                  <th>Action</th>
               </tr>
            </thead>
         </table>

      </div>
   </div>

   <div v-if="isActive">
      <transition name="modal">
         <div class="modal-mask">
            <div class="modal-wrapper">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h4 class="modal-title text-center" v-text="bank_name"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" @click="isActive = false">&times;</span>
                        </button>
                     </div>
                     <form method="POST" action="{{ route('payment.store.bank.charges') }}" @submit.prevent="onSubmit">
                        <div class="modal-body">
                           <label for="bank_charge">Bank Charge:</label>
                           <input type="hidden" class="form-control" id="id" v-model="id">
                           <input type="text" class="form-control" id="bank-charge" v-model="bank_charge">
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="isActive = false">Close</button>
                        <button type="submit" class="btn btn-primary" @click="update">Save changes</button>
                     </form>
                        </div>
                  </div>
               </div>
            </div>
         </div>
      </transition>
   </div>
</div>


@section('footer_scripts')

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.js"></script>
<script src="https://unpkg.com/vue@2.6.10/dist/vue.js"></script>

<script type="text/javascript">
	
new Vue({
   el: '#app',
   data: {
      isActive: false,
      bank_name: '',
      banks: []
   },
   methods: {
      onShowModal(bank_charge) {
         this.isActive    = true;
         this.bank_name   = bank_charge.bank_name.toUpperCase().split('_').join(' ');
         this.id          = bank_charge.id;
         this.bank_charge = bank_charge.bank_charge;
      },

      read() {
         this.banks = [];
        window.axios.get('{{ url('/payment/show/bank/charges') }}').then(({ data }) => {
          data.forEach(bank => {
            this.banks.push(bank);
          });
        });
      },

      update() {

         var bankCharge = document.getElementById('bank-charge').value;
         var id         = document.getElementById('id').value;

         window.axios.post(`{{ url('/payment/edit/bank/charges/${id}') }}`, { bank_charge : bankCharge }).then(() => {
            this.read();
         });
      },

      onSubmit() {
         this.isActive    = false;
      },
   },

   created() {
      this.read();
   }
})

</script>

@endsection

@endsection