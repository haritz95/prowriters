import{d}from"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import{_ as c,f as m,o as r,c as s,d as h,t as p,h as l,a as g,F as _,b as f}from"./app.4060d334.js";const C={props:["data","url"],components:{AvatarCropper:d},data(){return{showCropper:null,outputOptions:{width:360,height:360},onProgress:!1}},methods:{uploadHandler(t){let e=t.getCroppedCanvas().toDataURL(this.cropperOutputMime);e=e.replace("data:image/png;base64,",""),this.$inertia.patch(this.url,{file:e},{onStart:a=>{this.onProgress=!0},onFinish:a=>{this.onProgress=!1}})}}},v=f("i",{class:"fa-solid fa-image"},null,-1),V={key:1,class:"text-center text-muted"};function b(t,e,a,k,o,i){const u=m("avatar-cropper");return r(),s(_,null,[o.onProgress?l("",!0):(r(),s("button",{key:0,class:"btn btn-sm btn-light",onClick:e[0]||(e[0]=n=>o.showCropper=!0)},[v,h(" "+p(t.__("Select an image")),1)])),o.onProgress?(r(),s("div",V,p(t.__("Uploading"))+" ... ",1)):l("",!0),g(u,{modelValue:o.showCropper,"onUpdate:modelValue":e[1]||(e[1]=n=>o.showCropper=n),"upload-handler":i.uploadHandler,"output-options":o.outputOptions},null,8,["modelValue","upload-handler","output-options"])],64)}const U=c(C,[["render",b]]);export{U};
