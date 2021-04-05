<template>
    <div>
        <h3 ref="myref">Hook tutorial</h3>
        <h3>My name is {{nama}}</h3>
    </div>
</template>

<script>
    export default({
        data() {
            return {
                nama: 'Amran'
            }
        },
        beforeCreate() {
            console.log(this.nama)
        },
        created() {
            console.log('data and other func is avilable but not html ', this.nama )
        },
        mounted() {
            console.log(this.$refs.myref)
        },
    })
</script>

