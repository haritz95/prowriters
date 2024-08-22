import S from"./SettingsLayout.4c1fc6b3.js";import v from"./ActionToolBar.efa9c694.js";import b from"./PayPal.60562280.js";import C from"./Stripe.dbd9ea5f.js";import N from"./Braintree.44180c5c.js";import T from"./PayStack.3e3d063c.js";import L from"./PayU.fb00e9de.js";import O from"./TwoCheckout.da3440cf.js";import V from"./NOWPayments.4e962700.js";import{_ as A,g as o,y as i,f as a,o as e,a as m,b as _,c as l,r as U,d as W,t as x,n as F,F as z,h as r}from"./app.4060d334.js";import"./Menu.e7c29a6b.js";import"./bootstrap.esm.c86673ca.js";import"./Input.47b7b4a9.js";import"./CheckBox.949c07b7.js";import"./Select.3a870b54.js";import"./SubmitButton.94bb04f8.js";import"./InputSpinner.vue_vue_type_style_index_0_scoped_f3ddc209_lang.9416471f.js";import"./debounce.b911da70.js";const D={components:{SettingsLayout:S,ActionToolBar:v,PayPal:b,Stripe:C,Braintree:N,PayStack:T,PayU:L,TwoCheckout:O,NOWPayments:V},props:["data"],provide(){return{data:this.data}},data(){return{toolbar:{title:this.data.title,hide_save_button:!0}}}},E={class:"nav nav-tabs"},j={class:"p-4"};function q(n,G,t,H,u,I){const y=a("ActionToolBar"),p=a("Link"),d=a("PayPal"),k=a("Stripe"),f=a("Braintree"),g=a("PayStack"),w=a("PayU"),h=a("TwoCheckout"),P=a("NOWPayments"),B=a("SettingsLayout");return e(),o(B,{title:t.data.title},{default:i(()=>[m(y,{toolbar:u.toolbar},null,8,["toolbar"]),_("ul",E,[(e(!0),l(z,null,U(t.data.gateways,(c,s)=>(e(),l("li",{key:s,class:"nav-item"},[m(p,{href:n.route("admin.settings.payment.gateways",{gateway:c.slug}),class:F(["nav-link",{active:t.data.current_gateway==s}])},{default:i(()=>[W(x(n.__(c.name)),1)]),_:2},1032,["href","class"])]))),128))]),_("div",j,[t.data.current_gateway=="paypal_checkout"?(e(),o(d,{key:0})):r("",!0),t.data.current_gateway=="stripe"?(e(),o(k,{key:1})):r("",!0),t.data.current_gateway=="braintree"?(e(),o(f,{key:2})):r("",!0),t.data.current_gateway=="paystack"?(e(),o(g,{key:3})):r("",!0),t.data.current_gateway=="payu"?(e(),o(w,{key:4})):r("",!0),t.data.current_gateway=="twocheckout"?(e(),o(h,{key:5})):r("",!0),t.data.current_gateway=="nowpayments"?(e(),o(P,{key:6})):r("",!0)])]),_:1},8,["title"])}const mt=A(D,[["render",q]]);export{mt as default};