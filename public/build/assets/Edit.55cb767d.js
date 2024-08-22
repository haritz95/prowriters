import{I as d}from"./Input.47b7b4a9.js";import{_ as f,g as p,y as b,f as t,o as x,b as u,a as m,w as V}from"./app.4060d334.js";import{C as v}from"./CheckBox.949c07b7.js";import{S as c}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const g={components:{Input:d,CheckBox:v,SubmitButton:c},props:["data","existing_record"],data(){return{form:this.$inertia.form(this.prepareForm()),formConfig:{preserveScroll:!1}}},methods:{prepareForm(){let o={name:null,minimum_order_quantity:null,allowed_file_extensions:null,maximum_file_size:null,minimum_number_of_files_to_upload:null,maximum_number_of_files_to_upload:null,disable_writing:null,disable_editing:null,disable_proofreading:null,inactive:null};return this.existing_record&&(o={...o,...this.existing_record}),o}}},w={class:"mt-4"};function B(o,e,r,C,l,S){const n=t("Input"),a=t("CheckBox"),s=t("SubmitButton"),_=t("Modal");return x(),p(_,{title:r.data.title},{default:b(()=>[u("form",{onSubmit:e[5]||(e[5]=V(i=>l.form.post(o.route("admin.services.update",r.existing_record.id)),["prevent"]))},[m(n,{modelValue:l.form.name,"onUpdate:modelValue":e[0]||(e[0]=i=>l.form.name=i),name:"name",label:o.__("Name"),required:!0},null,8,["modelValue","label"]),m(n,{modelValue:l.form.allowed_file_extensions,"onUpdate:modelValue":e[1]||(e[1]=i=>l.form.allowed_file_extensions=i),name:"allowed_file_extensions",label:o.__("Allowed file extensions"),required:!0},null,8,["modelValue","label"]),m(n,{modelValue:l.form.maximum_file_size,"onUpdate:modelValue":e[2]||(e[2]=i=>l.form.maximum_file_size=i),name:"maximum_file_size",label:o.__("Maximum file size"),required:!0},null,8,["modelValue","label"]),m(n,{modelValue:l.form.maximum_number_of_files_to_upload,"onUpdate:modelValue":e[3]||(e[3]=i=>l.form.maximum_number_of_files_to_upload=i),name:"maximum_number_of_files_to_upload",label:o.__("Maximum number of files to upload"),required:!0},null,8,["modelValue","label"]),u("div",w,[m(a,{modelValue:l.form.inactive,"onUpdate:modelValue":e[4]||(e[4]=i=>l.form.inactive=i),name:"inactive",label:o.__("Inactive")},null,8,["modelValue","label"])]),m(s,{disabled:l.form.processing},null,8,["disabled"])],32)]),_:1},8,["title"])}const y=f(g,[["render",B]]);export{y as default};
