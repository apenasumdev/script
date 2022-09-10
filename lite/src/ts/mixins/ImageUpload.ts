import {api,snackbar} from "@/helpers/utils"

export default {
    methods: {
        uploadImage(file, Editor, cursorLocation, resetUploader){
            this.process = true
     
            snackbar('Image Uploading', 'info')
            api('upload/image','post',{image: file}).then(resp=>{
                Editor.insertEmbed(cursorLocation, "image", resp.data.image);
                
                snackbar('Image Uploaded', 'success')

                resetUploader();
            }).catch(error=>{

                snackbar(error.response.data.error || 'Something went wrong!', 'error')
                
            }).finally(()=>{
                this.process = false
            })
        }
    }
}