import{I as b}from"./Input.47b7b4a9.js";import{T as c}from"./TextArea.c22bdae9.js";import{C as V}from"./CheckBox.949c07b7.js";import{_ as h,g as s,y as g,f as t,o as u,b as B,h as a,a as n,w as C}from"./app.4060d334.js";import{S as w}from"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./bootstrap.esm.c86673ca.js";import"./debounce.b911da70.js";const k={components:{Input:b,TextArea:c,CheckBox:V,SubmitButton:w},props:["data","existing_record"],data(){return{form:this.$inertia.form(this.prepareForm())}},methods:{prepareForm(){let r={stripe_id:null,title:null,description:null,number_of_characters_allowed_per_month:null,price:null,is_free:!1};return this.existing_record&&(r={...r,...this.existing_record}),r}}};function S(r,o,m,x,e,I){const i=t("Input"),p=t("TextArea"),d=t("CheckBox"),f=t("SubmitButton"),_=t("Modal");return u(),s(_,{title:m.data.title},{default:g(()=>[B("form",{onSubmit:o[6]||(o[6]=C(l=>m.existing_record?e.form.patch(r.route("admin.settings.subscriptionPlans.update",m.existing_record.uuid)):e.form.post(r.route("admin.settings.subscriptionPlans.store")),["prevent"]))},[e.form.is_free?a("",!0):(u(),s(i,{key:0,modelValue:e.form.stripe_id,"onUpdate:modelValue":o[0]||(o[0]=l=>e.form.stripe_id=l),name:"stripe_id",label:r.__("Stripe API ID"),required:!0,tooltip:r.__("Collect it from your Stripe dashboard")},null,8,["modelValue","label","tooltip"])),n(i,{modelValue:e.form.title,"onUpdate:modelValue":o[1]||(o[1]=l=>e.form.title=l),name:"title",label:r.__("Title"),required:!0},null,8,["modelValue","label"]),n(p,{modelValue:e.form.description,"onUpdate:modelValue":o[2]||(o[2]=l=>e.form.description=l),name:"description",label:r.__("Description")},null,8,["modelValue","label"]),n(i,{modelValue:e.form.number_of_characters_allowed_per_month,"onUpdate:modelValue":o[3]||(o[3]=l=>e.form.number_of_characters_allowed_per_month=l),name:"number_of_characters_allowed_per_month",label:r.__("Number of characters allowed per month"),required:!0},null,8,["modelValue","label"]),e.form.is_free?a("",!0):(u(),s(i,{key:1,modelValue:e.form.price,"onUpdate:modelValue":o[4]||(o[4]=l=>e.form.price=l),name:"price",label:r.__("Price"),required:!0},null,8,["modelValue","label"])),n(d,{modelValue:e.form.is_free,"onUpdate:modelValue":o[5]||(o[5]=l=>e.form.is_free=l),label:r.__("Free Plan"),name:"is_free"},null,8,["modelValue","label"]),n(f,{disabled:e.form.processing},null,8,["disabled"])],32)]),_:1},8,["title"])}const F=h(k,[["render",S]]);export{F as default};
