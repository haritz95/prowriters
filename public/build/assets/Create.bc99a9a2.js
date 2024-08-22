import{I as v}from"./Input.47b7b4a9.js";import{T as g}from"./TextArea.c22bdae9.js";import{C as y}from"./CheckBox.949c07b7.js";import{S as q}from"./Select.3a870b54.js";import{_ as C,g as a,y as d,f as u,o as _,b as r,t as U,h as w,a as m}from"./app.4060d334.js";import{S as N}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import{F as x}from"./FileChooser.90bd4def.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const S={components:{Input:v,TextArea:g,FileChooser:x,CheckBox:y,SubmitButton:N,Select:q},props:["data","existing_record"],data(){return{form:this.$inertia.form(this.prepareForm()),formConfig:{preserveScroll:!1}}},methods:{prepareForm(){let i={service_type_id:null,name:null,description:null,assignment_label:null,image:null,unit_name:null,minimum_order_quantity:null,maximum_file_size:null,maximum_number_of_files_to_upload:null,allowed_file_extensions:null,inactive:null,not_available_for_direct_order:null,not_available_for_bidding:null,commission:null,commission_from_bid:null};return this.existing_record&&(i={...i,...this.existing_record}),i}}},k={class:"row"},B={class:"col-md-6"},c={class:"col-md-6"},z={class:"row"},F={class:"col-md-6"},I={class:"col-md-6"},K={class:"row"},T={class:"col-md-6"},A={class:"col-md-6"},M={class:"mt-3"},D=["disabled"];function O(i,e,s,Y,l,j){const f=u("Select"),n=u("Input"),p=u("TextArea"),b=u("FileChooser"),t=u("CheckBox"),V=u("Modal");return _(),a(V,{title:s.data.title,size:"large"},{footer:d(()=>[r("button",{onClick:e[20]||(e[20]=o=>s.existing_record?l.form.patch(i.route("admin.services.update",s.existing_record.slug)):l.form.post(i.route("admin.services.store"))),disabled:l.form.processing,type:"submit",class:"btn btn-primary"},U(i.__("Submit")),9,D)]),default:d(()=>[r("form",null,[r("div",k,[r("div",B,[s.existing_record&&s.existing_record.slug?w("",!0):(_(),a(f,{key:0,searchable:!0,options:s.data.dropdowns.service_types,modelValue:l.form.service_type_id,"onUpdate:modelValue":e[0]||(e[0]=o=>l.form.service_type_id=o),label:i.__("Service Order Form Type"),required:!0,name:"service_type_id"},null,8,["options","modelValue","label"])),m(n,{modelValue:l.form.name,"onUpdate:modelValue":e[1]||(e[1]=o=>l.form.name=o),name:"name",label:i.__("Name"),required:!0},null,8,["modelValue","label"]),m(p,{modelValue:l.form.description,"onUpdate:modelValue":e[2]||(e[2]=o=>l.form.description=o),name:"description",label:i.__("Description"),required:!0},null,8,["modelValue","label"]),m(n,{modelValue:l.form.assignment_label,"onUpdate:modelValue":e[3]||(e[3]=o=>l.form.assignment_label=o),name:"assignment_label",label:i.__("Assignment Label"),required:!0,placeholder:i.__("example: Type of writing")},null,8,["modelValue","label","placeholder"]),m(n,{modelValue:l.form.commission,"onUpdate:modelValue":e[4]||(e[4]=o=>l.form.commission=o),name:"commission",label:i.__("Your earning commission rate from an order"),onKeypress:e[5]||(e[5]=o=>i.onlyNumber(o,l.form.commission)),tooltip:i.__("Commission you would like to receive from the order total"),note:i.__("In Percentage")},null,8,["modelValue","label","tooltip","note"]),m(n,{modelValue:l.form.commission_from_bid,"onUpdate:modelValue":e[6]||(e[6]=o=>l.form.commission_from_bid=o),name:"commission_from_bid",label:i.__("Your earning commission rate from a bid"),onKeypress:e[7]||(e[7]=o=>i.onlyNumber(o,l.form.commission_from_bid)),tooltip:i.__("Commission you would like to receive from bidding service")},null,8,["modelValue","label","tooltip"])]),r("div",c,[m(b,{modelValue:l.form.image,"onUpdate:modelValue":e[8]||(e[8]=o=>l.form.image=o),name:"image",label:i.__("Cover image"),required:!0},null,8,["modelValue","label"]),r("div",z,[r("div",F,[m(n,{modelValue:l.form.unit_name,"onUpdate:modelValue":e[9]||(e[9]=o=>l.form.unit_name=o),name:"unit_name",label:i.__("Unit Name"),required:!0,placeholder:i.__("example: words")},null,8,["modelValue","label","placeholder"])]),r("div",I,[m(n,{modelValue:l.form.minimum_order_quantity,"onUpdate:modelValue":e[10]||(e[10]=o=>l.form.minimum_order_quantity=o),name:"minimum_order_quantity",label:i.__("Minimum order quantity"),required:!0,onKeypress:e[11]||(e[11]=o=>i.onlyNumber(o,l.form.minimum_order_quantity))},null,8,["modelValue","label"])])]),r("div",K,[r("div",T,[m(n,{modelValue:l.form.maximum_file_size,"onUpdate:modelValue":e[12]||(e[12]=o=>l.form.maximum_file_size=o),name:"maximum_file_size",label:i.__("Maximum file size in KB"),required:!0,onKeypress:e[13]||(e[13]=o=>i.onlyNumber(o,l.form.maximum_file_size))},null,8,["modelValue","label"])]),r("div",A,[m(n,{modelValue:l.form.maximum_number_of_files_to_upload,"onUpdate:modelValue":e[14]||(e[14]=o=>l.form.maximum_number_of_files_to_upload=o),name:"maximum_number_of_files_to_upload",label:i.__("Maximum number of files"),required:!0,onKeypress:e[15]||(e[15]=o=>i.onlyNumber(o,l.form.maximum_number_of_files_to_upload))},null,8,["modelValue","label"])])]),m(n,{modelValue:l.form.allowed_file_extensions,"onUpdate:modelValue":e[16]||(e[16]=o=>l.form.allowed_file_extensions=o),name:"allowed_file_extensions",label:i.__("Allowed file extensions"),required:!0,placeholder:i.__("example: .jpg,.png,.gif, .doc,.docx,.xls")},null,8,["modelValue","label","placeholder"]),r("div",M,[m(t,{modelValue:l.form.inactive,"onUpdate:modelValue":e[17]||(e[17]=o=>l.form.inactive=o),name:"inactive",label:i.__("Inactive")},null,8,["modelValue","label"]),m(t,{modelValue:l.form.not_available_for_bidding,"onUpdate:modelValue":e[18]||(e[18]=o=>l.form.not_available_for_bidding=o),name:"not_available_for_bidding",label:i.__("Not available for Bidding")},null,8,["modelValue","label"]),m(t,{modelValue:l.form.not_available_for_direct_order,"onUpdate:modelValue":e[19]||(e[19]=o=>l.form.not_available_for_direct_order=o),name:"not_available_for_direct_order",label:i.__("Not available for Direct Order")},null,8,["modelValue","label"])])])])])]),_:1},8,["title"])}const Z=C(S,[["render",O]]);export{Z as default};