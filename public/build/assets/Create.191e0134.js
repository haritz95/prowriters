import{A as b}from"./Attachment.a35ebc48.js";import{Q as g}from"./vue-quill.snow.151e10a0.js";import{_ as v,g as k,y as x,f as l,o as y,b as e,d as c,t as m,a as n,e as _,a4 as d,w}from"./app.4060d334.js";import"./bootstrap.esm.c86673ca.js";const A={components:{Attachment:b,QuillEditor:g},props:["data"],data(){return{form:this.$inertia.form({feedback:"",message:this.data.config.default_message,files:this.data.config.existing_files?this.data.config.existing_files:[]})}},methods:{handleAttachment(i){this.form.files=i}}},V={class:"mb-3"},E={class:"form-label"},C=e("span",{class:"required"},"*",-1),j={class:"mb-3"},z={class:"mt-3"},M={class:"form-check form-check-inline"},Q={class:"form-check-label",for:"approve"},B={class:"form-check form-check-inline"},N={class:"form-check-label",for:"reject"},S={class:"mt-3"},U=["disabled"],D=e("i",{class:"far fa-paper-plane"},null,-1);function O(i,t,s,R,o,f){const u=l("QuillEditor"),r=l("ValidationError"),p=l("Attachment"),h=l("Modal");return y(),k(h,{title:s.data.title,size:"regular"},{default:x(()=>[e("form",{onSubmit:t[3]||(t[3]=w(a=>o.form.post(s.data.config.urls.submit_form),["prevent"]))},[e("div",V,[e("label",E,[c(m(i.__("Enter your findings"))+" ",1),C]),n(u,{style:{height:"150px"},content:o.form.message,"onUpdate:content":t[0]||(t[0]=a=>o.form.message=a),contentType:"html",theme:"snow",toolbar:"essential"},null,8,["content"]),n(r,{name:"message"})]),e("div",j,[n(p,{onOnChange:f.handleAttachment,upload_attachment_url:s.data.config.urls.upload_attachment,allowed_file_extensions:s.data.config.allowed_file_extensions,maximum_number_of_files_to_upload:s.data.config.maximum_number_of_files_to_upload,maximum_file_size:s.data.config.maximum_file_size,existing_files:s.data.config.existing_files},null,8,["onOnChange","upload_attachment_url","allowed_file_extensions","maximum_number_of_files_to_upload","maximum_file_size","existing_files"]),n(r,{name:"files"})]),e("div",z,[e("div",M,[_(e("input",{class:"form-check-input",type:"radio",id:"approve",value:"approve","onUpdate:modelValue":t[1]||(t[1]=a=>o.form.feedback=a)},null,512),[[d,o.form.feedback]]),e("label",Q,m(i.__("Approve")),1)]),e("div",B,[_(e("input",{class:"form-check-input",type:"radio",id:"reject",value:"reject","onUpdate:modelValue":t[2]||(t[2]=a=>o.form.feedback=a)},null,512),[[d,o.form.feedback]]),e("label",N,m(i.__("Reject")),1)]),n(r,{name:"feedback"})]),e("div",S,[e("button",{disabled:o.form.processing,class:"btn btn-primary",type:"submit"},[D,c(" "+m(i.__("Submit")),1)],8,U)])],32)]),_:1},8,["title"])}const H=v(A,[["render",O]]);export{H as default};
