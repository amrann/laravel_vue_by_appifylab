<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Categori <Button @click="tambahModal=true"><Icon type="md-add"/>Tambah Categori</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
							<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Categori Name</th>
								<th>Icon Image</th>
								<th>Time</th>
								<th>Action</th>
							</tr>
							<!-- TABLE TITLE -->
							<!-- ITEMS -->
							<tr v-for="(ctgr, i) in listDataCategori" :key="i" v-if="listDataCategori.length">
								<td>{{ctgr.id}}</td>
								<td>{{ctgr.categoriName}}</td>
								<td class="table_image">
                                    <img :src="ctgr.iconImage" />
                                </td>
								<td>{{ctgr.created_at}}</td>
								<td>
									<Button type="info" size="small" @click="showEditModal(ctgr, i)">Edit</Button>
									<Button type="error" size="small">Delete</Button>
								</td>
							</tr>
							<!-- ITEMS -->
						</table>
					</div>
				</div>

                <!-- TAG ADDING MODAL -->
                <Modal
                    v-model="tambahModal"
                    title="Tambah Categori"
                    :mask-closable="false"
                    :closable="false">

                    <Input v-model="data.categoriName" placeholder="Add categori name..." />
                    <div class="space"></div>
                    <Upload
                        ref="iniupload"
                        type="drag"
                        :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                        :on-success="handleSuccess"
                        :on-error="handleError"
                        :format="['jpg','jpeg','png']"
                        :max-size="2048"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        action="/app/upload">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
                    <div class="demo-upload-list" v-if="data.iconImage">
                        <img :src="`${data.iconImage}`" >
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="hapusGambar"></Icon>
                        </div>
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="tambahModal=false">Close</Button>
                        <Button
                            type="primary"
                            @click="tambahCategori"
                            :disabled="isAdding"
                            :loading="isAdding">
                            <!-- Simpan -->
                            {{isAdding ? 'Adding..' : 'Add Categori'}}
                        </Button>
                    </div>
                </Modal>

                <!-- TAG EDITING MODAL -->
                <Modal
                    v-model="editModal"
                    title="Edit Categori"
                    :mask-closable="false"
                    :closable="false">

                    <Input v-model="editDataCategori.categoriName" placeholder="Add categori name..." />
                    <div class="space"></div>
                    <Upload v-show="isIconImageNew"
                        ref="iniuploadEdit"
                        type="drag"
                        :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
                        :on-success="handleSuccess"
                        :on-error="handleError"
                        :format="['jpg','jpeg','png']"
                        :max-size="2048"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        action="/app/upload">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
                    <div class="demo-upload-list" v-if="editDataCategori.iconImage">
                        <img :src="`${editDataCategori.iconImage}`" >
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="hapusGambar(false)"></Icon>
                        </div>
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="closeEditModal">Close</Button>
                        <Button
                            type="primary"
                            @click="editCategori"
                            :disabled="isAdding"
                            :loading="isAdding">
                            <!-- Simpan -->
                            {{isAdding ? 'Editing..' : 'Edit Categori'}}
                        </Button>
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
                    categoriName: '',
                    iconImage: ''
                },
                tambahModal: false,
                editModal: false,
                isAdding: false,
                listDataCategori: [],
                editDataCategori: {
                    categoriName: '',
                    iconImage: ''
                },
                index: -1,
                deletingIndex: -1,
                token: '',
                isIconImageNew: false,
                isEditing: false
            }
        },
        methods: {
            async tambahCategori(){
                if(this.data.categoriName.trim() == '') return this.e('Categori name is required!')
                if(this.data.iconImage.trim() == '') return this.e('Icon image is required!')
                this.data.iconImage = `/mygambar/${this.data.iconImage}`
                const res = await this.panggilApi('post', 'app/tambah_categori', this.data)
                if (res.status==201) {
                    // console.log(res)
                    this.listDataCategori.unshift(res.data)
                    this.s('Categori has been added successfully')
                    this.tambahModal = false
                    this.data.categoriName = ''
                    this.data.iconImage = ''
                } else {
                    if (res.status==422) {
                        if (res.data.errors.categoriName) {
                            this.e(res.data.errors.categoriName[0]) //datanya ini diambil dari inspect element > network
                        }
                        if (res.data.errors.iconImage) {
                            this.e(res.data.errors.iconImage[0]) //datanya ini diambil dari inspect element > network
                        }
                    } else {
                        this.swr()
                    }
                }
            },
            async editCategori(){
                if(this.editDataCategori.categoriName.trim() == '') return this.e('Categori name is required!')
                if(this.editDataCategori.iconImage.trim() == '') return this.e('Icon image is required!')
                const res = await this.panggilApi('post', 'app/edit_categori', this.editDataCategori)
                if (res.status==200) {
                    // console.log(res)
                    this.listDataCategori[this.index].categoriName = this.editDataCategori.categoriName
                    this.s('Categori has been edit successfully')
                    this.editModal = false
                } else {
                    if (res.status==422) {
                        if (res.data.errors.categoriName) {
                            this.e(res.data.errors.categoriName[0]) //datanya ini diambil dari inspect element > network
                        }
                        if (res.data.errors.iconImage) {
                            this.e(res.data.errors.iconImage[0]) //datanya ini diambil dari inspect element > network
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
                this.editDataCategori = param
                this.editModal = true
                this.index = index
                this.isEditing = true
            },
            closeEditModal() {
                this.isEditing = false
                this.editModal = false
            },
            handleSuccess (res, file) {
                res = `/mygambar/${res}`
                if(this.isEditing) {
                    return this.editDataCategori.iconImage = res
                }
                this.data.iconImage = res
            },
            handleError (res, file) {
                // console.log('res : ', res)
                // console.log('file : ', file)
                this.$Notice.warning({
                    title: 'The file format is incorrect',
                    desc: `${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
                });
            },
            handleFormatError (file) {
                this.$Notice.warning({
                    title: 'The file format is incorrect',
                    desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
                });
            },
            handleMaxSize (file) {
                this.$Notice.warning({
                    title: 'Exceeding file size limit',
                    desc: 'File  ' + file.name + ' is too large, no more than 2M.'
                });
            },
            async hapusGambar(isAdding=true){
                let gmbr;
                if (!isAdding) {
                    this.isIconImageNew = true
                    gmbr = this.editDataCategori.iconImage
                    this.$refs.iniuploadEdit.clearFiles()
                    this.editDataCategori.iconImage = ''
                } else {
                    gmbr = this.data.iconImage
                    this.$refs.iniupload.clearFiles()
                    this.data.iconImage = ''
                }

                const res = await this.panggilApi('post', 'app/hapus_gambar', {namaGmbr: gmbr})
                if (res.status!=200) {
                    this.data.iconImage = gmbr
                    this.swr()
                }
            },
        },

        async created(){
            this.token = window.Laravel.csrfToken
            const res = await this.panggilApi('get', 'app/get_categori')
            if (res.status==200) {
                this.listDataCategori = res.data
            } else {
                this.swr()
            }
        }
    }
</script>


