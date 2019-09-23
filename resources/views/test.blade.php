@extends('layouts.master')

@section('title', 'Set Employees')

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

@section('content')

<div id="app">
	<div v-if="showModal">
		<transition name="modal">
			<div class="modal-mask">
				<div class="modal-wrapper">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Modal title</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true" @click="showModal = false">&times;</span>
								</button>
							</div>
						<div class="modal-body">
							<p>Modal body text goes here.</p>
						</div>
						<div class="modal-footer">
						<button type="button" class="btn btn-secondary" @click="showModal = false">Close</button>
						<button type="button" class="btn btn-primary">Save changes</button>
						</div>
						</div>
					</div>
				</div>
			</div>
		</transition>
	</div>
	<button @click="showModal = true">Click</button>
</div>

@endsection

@section('footer_scripts')
<script src="https://unpkg.com/vue@2.6.10/dist/vue.js"></script>
<script type="text/javascript">
	
new Vue({
  el: '#app',
  data: {
    showModal: false
  }
})

</script>
@endsection