<template>
  <div>{{name}}</div>
</template>

<script type="text/javascript">
import draggable from 'vuedraggable'
    export default {
        data:{
                name:"wait"
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
