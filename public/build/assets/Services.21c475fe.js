import{_ as p,c as a,a as l,b as e,F as c,r as f,f as i,o,t as d,y as h,d as g}from"./app.4060d334.js";import"./bootstrap.esm.c86673ca.js";const v={props:["data"]},b={class:"container page-container"},k={class:"row"},x={class:"border p-2 h-100 d-flex flex-column justify-content-between"},y=["src","alt"],L={class:"title h4"},T=["innerHTML"],H={class:"d-grid gap-2"},B=e("i",{class:"fa-solid fa-angle-right"},null,-1);function N(n,S,s,V,A,C){const r=i("AppHead"),_=i("PageTitle"),u=i("Link");return o(),a(c,null,[l(r,{title:s.data.title},null,8,["title"]),e("div",b,[l(_,{title:s.data.title},null,8,["title"]),e("div",k,[(o(!0),a(c,null,f(s.data.services,(t,m)=>(o(),a("div",{class:"col-md-4 text-center mb-4",key:m},[e("div",x,[e("div",null,[e("div",null,[e("img",{src:n.asset(t.image),alt:t.name,class:"img-fluid rounded-circle flex-shrink-0"},null,8,y)]),e("h2",L,d(t.name),1),e("p",{class:"description nl2br",innerHTML:t.description},null,8,T)]),e("div",H,[l(u,{href:n.route(s.data.route_name,t.slug),class:"align-self-end btn btn-primary"},{default:h(()=>[g(d(n.__("Select"))+" ",1),B]),_:2},1032,["href"])])])]))),128))])])],64)}const P=p(v,[["render",N]]);export{P as default};