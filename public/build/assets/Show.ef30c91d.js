import{_ as i,c as r,a as s,b as t,y as n,t as e,F as m,f as d,o as h,d as u}from"./app.4060d334.js";import"./bootstrap.esm.c86673ca.js";const f={props:["data","wallet_adjustment"]},w={class:"container page-container"},j=t("i",{class:"fa-solid fa-arrow-left-long"},null,-1),b={class:"row"},g={class:"col-md-12"},y={class:"table table-striped-columns"},A={class:"col-md-4"},k={class:"col-md-4"},B={class:"col-md-4"},p={class:"col-md-4"},v={class:"col-md-4"},D={class:"col-md-4"},N={class:"col-md-4"};function T(l,H,a,V,C,F){const _=d("AppHead"),o=d("Link"),c=d("PageTitle");return h(),r(m,null,[s(_,{title:a.data.title},null,8,["title"]),t("div",w,[s(c,{title:a.data.title},{default:n(()=>[s(o,{class:"btn btn-sm btn-light",href:l.route("admin.walletAdjustments.index")},{default:n(()=>[j,u(" "+e(l.__("Back to Wallet Adjustments")),1)]),_:1},8,["href"])]),_:1},8,["title"]),t("div",b,[t("div",g,[t("table",y,[t("tbody",null,[t("tr",null,[t("th",A,e(l.__("Number")),1),t("td",null,e(a.wallet_adjustment.number),1)]),t("tr",null,[t("th",k,e(l.__("Date")),1),t("td",null,e(l.localDate(a.wallet_adjustment.created_at)),1)]),t("tr",null,[t("th",B,e(l.__("Type")),1),t("td",null,e(a.wallet_adjustment.type),1)]),t("tr",null,[t("th",p,e(l.__("Account Holder")),1),t("td",null,[s(o,{href:l.route("admin.customers.show",a.wallet_adjustment.user.uuid)},{default:n(()=>[u(e(a.wallet_adjustment.user.full_name),1)]),_:1},8,["href"])])]),t("tr",null,[t("th",v,e(l.__("Description")),1),t("td",null,e(a.wallet_adjustment.description),1)]),t("tr",null,[t("th",D,e(l.__("Amount")),1),t("td",null,e(l.formatMoney(a.wallet_adjustment.amount)),1)]),t("tr",null,[t("th",N,e(l.__("Adjusted By")),1),t("td",null,[s(o,{href:l.route("admin.users.show",a.wallet_adjustment.adjuster.uuid)},{default:n(()=>[u(e(a.wallet_adjustment.adjuster.full_name),1)]),_:1},8,["href"])])])])])])])])],64)}const S=i(f,[["render",T]]);export{S as default};
