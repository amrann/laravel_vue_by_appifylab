<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button @click="tambahModal=true"><Icon type="md-add"/>Tambah Tag</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
							<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Tag Name</th>
								<th>Time</th>
								<th>Action</th>
							</tr>
							<!-- TABLE TITLE -->
							<!-- ITEMS -->
							<tr v-for="(tg, i) in listDataTag" :key="i" v-if="listDataTag.length">
								<td>{{tg.id}}</td>
								<td class="_table_name">{{tg.tagName}}</td>
								<td>{{tg.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(tg, i)">Edit</Button>
									<Button type="error" size="small" @click="showHapusModal(tg, i)" :loading="tg.isDeleting">Delete</Button>
								</td>
							</tr>
							<!-- ITEMS -->
						</table>
					</div>
				</div>

                <!-- TAG ADDING MODAL -->
                <Modal
                    v-model="tambahModal"
                    title="Tambah Tag"
                    :mask-closable="false"
                    :closable="false">

                    <Input v-model="data.tagName" placeholder="Add tag name..." />

                    <div slot="footer">
                        <Button type="default" @click="tambahModal=false">Close</Button>
                        <Button
                            type="primary"
                            @click="tambahTag"
                            :disabled="isAdding"
                            :loading="isAdding">
                            <!-- Simpan -->
                            {{isAdding ? 'Adding..' : 'Add Tag'}}
                        </Button>
                    </div>
                </Modal>

                <!-- TAG EDITING MODAL -->
                <Modal
                    v-model="editModal"
                    title="Edit Tag"
                    :mask-closable="false"
                    :closable="false">

                    <Input v-model="editDataTag.tagName" placeholder="Edit tag name..." />

                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Close</Button>
                        <Button
                            type="primary"
                            @click="editTag"
                            :disabled="isAdding"
                            :loading="isAdding">
                            <!-- Simpan -->
                            {{isAdding ? 'Editing..' : 'Edit Tag'}}
                        </Button>
                    </div>
                </Modal>

                <!-- TAG DELETING MODAL -->
                <Modal v-model="deleteModal" width="360">
                    <p slot="header" style="color:#f60;text-align:center">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                    <div style="text-align:center">
                        <p>Are you sure want to delete this tag?</p>
                    </div>
                    <div slot="footer">
                        <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="hapusTag">Delete</Button>
                    </div>
                </Modal>

			</div>
		</div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                data: {
                    tagName: ''
                },
                tambahModal: false,
                editModal: false,
                deleteModal: false,
                isAdding: false,
                listDataTag: [],
                editDataTag: {
                    tagName: ''
                },
                index: -1,
                isDeleting: false,
                hapusDataTag: {},
                deletingIndex: -1
            }
        },
        methods: {
            async tambahTag(){
                if(this.data.tagName.trim() == '') return this.e('Tag name is required!')
                const res = await this.panggilApi('post', 'app/tambah_tag', this.data)
                if (res.status==201) {
                    // console.log(res)
                    this.listDataTag.unshift(res.data)
                    this.s('Tag has been added successfully')
                    this.tambahModal = false
                    this.data.tagName = ''
                } else {
                    if (res.status==422) {
                        if (res.data.errors.tagName) {
                            this.e(res.data.errors.tagName[0]) //datanya ini diambil dari inspect element > network
                        }
                    } else {
                        this.swr()
                    }
                }
            },
            async hapusTag(){
                // if(!confirm('Are you sure want to delete this tag?')) return
                // // make loading
                // this.$set(param, 'isDeleting', true)
                this.isDeleting = true
                const res = await this.panggilApi('post', 'app/hapus_tag', this.hapusDataTag)
                if (res.status==200) {
                    this.listDataTag.splice(this.deletingIndex, 1)
                    this.s('Tag has been deleted successfully')
                } else {
                    this.swr()
                }
                this.isDeleting = false
                this.deleteModal = false
            },
            async editTag(){
                if(this.editDataTag.tagName.trim() == '') return this.e('Tag name is required!')
                const res = await this.panggilApi('post', 'app/edit_tag', this.editDataTag)
                if (res.status==200) {
                    // console.log(res)
                    this.listDataTag[this.index].tagName = this.editDataTag.tagName
                    this.s('Tag has been edit successfully')
                    this.editModal = false
                } else {
                    if (res.status==422) {
                        if (res.data.errors.tagName) {
                            this.e(res.data.errors.tagName[0]) //datanya ini diambil dari inspect element > network
                        }
                    } else {
                        this.swr()
                    }
                }
            },
            showEditModal(param, index) {
                let obj = {
                    id: param.id,
                    tagName: param.tagName
                }
                this.editDataTag = obj
                this.editModal = true
                this.index = index
            },
            showHapusModal(param, index) {
                this.hapusDataTag = param
                this.deletingIndex = index
                this.deleteModal = true
            },
        },

        async created(){
            const res = await this.panggilApi('get', 'app/get_tag')
            if (res.status==200) {
                this.listDataTag = res.data
            } else {
                this.swr()
            }
        }
    }
</script>

