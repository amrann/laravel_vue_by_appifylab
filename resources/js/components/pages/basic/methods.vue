<template>
    <div>
        <h3>The number is : {{nilai}}</h3>
        <button @click="updetCounter(1)">Increase</button>
        <button @click="updetCounter(-1)">Decrease</button>
        <br><br>
        <p>#############################</p>
        <div v-for="(blog, i) in blogs" :key="i" v-if="blogs.length">
            <h3>{{blog.judul}}</h3>
            <p>{{blog.desk}}</p>
        </div>
        <p>#############################</p>
        <div>
            <h3 v-if="showItem">Show this item if "showItem" is true</h3>
            <p v-else>Show this item if "showItem" is false</p>
        </div>
        <button @click="showItem = !showItem">Change showItem</button>
    </div>
</template>
<script>
export default {
    data() {
       return {
           nilai: 0,
           blogs: [],
           showItem: false
       }
    },
    methods: {
        updetCounter(number){
            this.nilai += number
        }
    },
    created() {
        // call the server to get nilai of this blog post
        this.nilai = 100

        // get the blog artikel
        let artikels = [
            {judul: 'this is judul blog 1', desk: 'this is deskripsi blog 1', id: 1},
            {judul: 'this is judul blog 2', desk: 'this is deskripsi blog 2', id: 2},
            {judul: 'this is judul blog 3', desk: 'this is deskripsi blog 3', id: 3},
            {judul: 'this is judul blog 4', desk: 'this is deskripsi blog 4', id: 4},
        ]

        this.blogs = artikels
    },
}
</script>
