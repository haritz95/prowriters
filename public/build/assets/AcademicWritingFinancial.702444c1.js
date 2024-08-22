import{_ as o,c as l,b as t,t as e,h as d,F as _,o as a}from"./app.4060d334.js";import"./bootstrap.esm.c86673ca.js";const i={props:["task"]},r={class:"table"},u={class:"text-end"},c={class:"text-end"},h={class:"table table-sm"},m={class:"text-end"},k={class:"text-end"},f={class:"text-end"},y={class:"text-end"},b={class:"text-end"},M={class:"text-end"},g={class:"text-end"},v={class:"text-end"},w={key:0},B={class:"text-end"},P={key:0},A={key:1},C={class:"text-end"},S={class:"text-end"},F={class:"text-end"};function T(s,j,n,D,N,U){return a(),l(_,null,[t("table",r,[t("thead",null,[t("tr",null,[t("th",null,e(s.__("Price per word")),1),t("th",null,e(s.__("Word Count")),1),t("th",u,e(s.__("Base Price")),1)])]),t("tbody",null,[t("tr",null,[t("td",null,e(n.task.details.per_word_price),1),t("td",null,e(n.task.details.number_of_words),1),t("td",c,e(s.formatMoney(n.task.details.per_word_price*n.task.details.number_of_words)),1)])])]),t("table",h,[t("thead",null,[t("tr",null,[t("th",null,e(s.__("Description")),1),t("th",m,e(s.__("Price")),1)])]),t("tbody",null,[t("tr",null,[t("td",null,e(s.__("Base Price")),1),t("td",k,e(s.formatMoney(n.task.details.per_word_price*n.task.details.number_of_words)),1)]),t("tr",null,[t("td",null,e(s.__("Assignment")),1),t("td",f,e(s.formatMoney(n.task.details.assignment_price)),1)]),t("tr",null,[t("td",null,e(s.__("Subject")),1),t("td",y,e(s.formatMoney(n.task.details.subject_price)),1)]),t("tr",null,[t("td",null,e(s.__("Urgency")),1),t("td",b,e(s.formatMoney(n.task.details.urgency_price)),1)]),t("tr",null,[t("th",null,e(s.__("Basic Price")),1),t("th",M,e(s.formatMoney(n.task.basic_price)),1)]),t("tr",null,[t("td",null,e(s.__("Additional Services")),1),t("td",g,e(s.formatMoney(n.task.additional_services_price)),1)]),t("tr",null,[t("td",null,e(s.__("Customer Service")),1),t("td",v,e(s.formatMoney(n.task.service_level_price)),1)]),n.task.is_total_overridden?(a(),l("tr",w,[t("th",null,e(s.__("Original Total")),1),t("th",B,e(s.formatMoney(n.task.original_total)),1)])):d("",!0),t("tr",null,[t("th",null,[n.task.is_total_overridden?(a(),l("span",P,e(s.__("Updated Total")),1)):(a(),l("span",A,e(s.__("Total")),1))]),t("th",C,e(s.formatMoney(n.task.total)),1)]),t("tr",null,[t("td",null,e(s.__("Author Cost")),1),t("td",S,e(s.formatMoney(n.task.author_payment_amount)),1)]),t("tr",null,[t("th",null,e(s.__("Profit")),1),t("th",F,e(s.formatMoney(n.task.total-n.task.author_payment_amount)),1)])])])],64)}const E=o(i,[["render",T]]);export{E as default};
